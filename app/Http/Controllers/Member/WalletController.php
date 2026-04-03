<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\UserSubscription;
use App\Services\PhonePeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function __construct(protected PhonePeService $phonePeService) {}

    /**
     * POST /wallet/initiate — first-time wallet creation (no plan required)
     * Collects: amount, milk_type, quantity_per_day, delivery_slot, location_id,
     *           delivery_address, start_date
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'amount'           => 'required|numeric|min:50|max:500000',
            'milk_type'        => 'required|string|max:50',
            'quantity_per_day' => 'required|numeric|min:0.5|max:20',
            'delivery_slot'    => 'required|in:morning,afternoon,evening',
            'location_id'      => 'required|exists:locations,id',
            'delivery_address' => 'required|string|max:500',
            'start_date'       => 'required|date|after_or_equal:today',
        ]);

        $user   = auth()->user();
        $amount = (float) $request->amount;

        // Look up price per litre from milk_prices table
        $milkPriceRow  = \App\Models\MilkPrice::forType($request->milk_type);
        $pricePerLitre = $milkPriceRow ? (float) $milkPriceRow->price_per_litre : null;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'        => $user->id,
                'order_type'     => 'wallet_topup',
                'order_id'       => Order::generateOrderId(),
                'amount'         => $amount,
                'status'         => 'pending',
                'payment_method' => 'phonepe',
            ]);

            // Store setup details in session for callback
            session([
                'wallet_init_milk_type_'        . $order->id => $request->milk_type,
                'wallet_init_qty_'              . $order->id => $request->quantity_per_day,
                'wallet_init_slot_'             . $order->id => $request->delivery_slot,
                'wallet_init_location_id_'      . $order->id => $request->location_id,
                'wallet_init_delivery_address_' . $order->id => $request->delivery_address,
                'wallet_init_start_date_'       . $order->id => $request->start_date,
                'wallet_init_price_per_litre_'  . $order->id => $pricePerLitre,
            ]);

            $paymentResponse = $this->phonePeService->initiatePayment(
                $order->order_id,
                $order->amount,
                $user->id,
                $user->name,
                $user->phone ?? '9999999999'
            );

            if ($paymentResponse['success']) {
                $order->update([
                    'transaction_id'   => $paymentResponse['data']['orderId'] ?? null,
                    'payment_response' => $paymentResponse,
                ]);
                DB::commit();
                return redirect($paymentResponse['redirect_url']);
            }

            DB::rollBack();
            return redirect()->route('member.dashboard')
                ->with('error', $paymentResponse['message'] ?? 'Payment initiation failed.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wallet Init Error', ['error' => $e->getMessage()]);
            return redirect()->route('member.dashboard')->with('error', 'An error occurred.');
        }
    }

    /** PATCH /wallet/{subscription}/pause  (action = pause|resume) */
    public function pause(Request $request, UserSubscription $subscription)
    {
        $this->authorizeSubscription($subscription);

        $action = $request->input('action', 'pause');
        $newStatus = $action === 'resume' ? 'active' : 'paused';

        $subscription->update(['delivery_status' => $newStatus]);

        $msg = $newStatus === 'paused' ? 'Deliveries paused.' : 'Deliveries resumed.';
        return redirect()->route('member.dashboard')->with('success', $msg);
    }

    /** PATCH /wallet/{subscription}/stop */
    public function stop(UserSubscription $subscription)
    {
        $this->authorizeSubscription($subscription);

        $subscription->update(['delivery_status' => 'stopped']);

        return redirect()->route('member.dashboard')->with('success', 'Deliveries stopped. Your wallet balance is safe.');
    }

    /** POST /wallet/{subscription}/topup — initiate PhonePe top-up */
    public function topup(Request $request, UserSubscription $subscription)
    {
        $this->authorizeSubscription($subscription);

        $request->validate([
            'amount' => 'required|numeric|min:50|max:50000',
        ]);

        $user   = auth()->user();
        $amount = (float) $request->amount;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'              => $user->id,
                'order_type'           => 'wallet_topup',
                'user_subscription_id' => $subscription->id,
                'order_id'             => Order::generateOrderId(),
                'amount'               => $amount,
                'status'               => 'pending',
                'payment_method'       => 'phonepe',
            ]);

            $paymentResponse = $this->phonePeService->initiatePayment(
                $order->order_id,
                $order->amount,
                $user->id,
                $user->name,
                $user->phone ?? '9999999999'
            );

            if ($paymentResponse['success']) {
                $order->update([
                    'transaction_id'   => $paymentResponse['data']['orderId'] ?? null,
                    'payment_response' => $paymentResponse,
                ]);
                DB::commit();
                return redirect($paymentResponse['redirect_url']);
            }

            DB::rollBack();
            return redirect()->route('member.dashboard')
                ->with('error', $paymentResponse['message'] ?? 'Top-up initiation failed.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wallet Top-up Error', ['error' => $e->getMessage()]);
            return redirect()->route('member.dashboard')->with('error', 'An error occurred.');
        }
    }

    private function authorizeSubscription(UserSubscription $subscription): void
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
