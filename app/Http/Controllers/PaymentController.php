<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MembershipPlan;
use App\Models\UserSubscription;
use App\Services\PhonePeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $phonePeService;

    public function __construct(PhonePeService $phonePeService)
    {
        $this->phonePeService = $phonePeService;
    }

    /**
     * Initiate payment
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:membership_plans,id',
            'location_id' => 'required|exists:locations,id',
            'delivery_address' => 'required|string|max:500'
        ]);

        $user = auth()->user();
        $plan = MembershipPlan::findOrFail($request->plan_id);
        $location = \App\Models\Location::findOrFail($request->location_id);

        // Check if user already has an active subscription
        $activeSubscription = $user->activeSubscription()->first();
        if ($activeSubscription) {
            return redirect()->route('member.dashboard')
                ->with('error', 'You already have an active subscription.');
        }

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'membership_plan_id' => $plan->id,
                'order_id' => Order::generateOrderId(),
                'amount' => $plan->price,
                'status' => 'pending',
                'payment_method' => 'phonepe',
            ]);

            // Store delivery address and location in session for later use
            session([
                'delivery_address_' . $order->id => $request->delivery_address,
                'location_id_' . $order->id => $request->location_id
            ]);

            // Initiate PhonePe payment
            $paymentResponse = $this->phonePeService->initiatePayment(
                $order->order_id,
                $order->amount,
                $user->id,
                $user->name,
                $user->phone ?? '9999999999'
            );

            if ($paymentResponse['success']) {
                // Update order with transaction details
                $order->update([
                    'transaction_id' => $paymentResponse['data']['merchantTransactionId'] ?? null,
                    'payment_response' => $paymentResponse
                ]);

                DB::commit();

                // Redirect to PhonePe payment page
                return redirect($paymentResponse['redirect_url']);
            } else {
                DB::rollBack();
                return redirect()->route('member.dashboard')
                    ->with('error', $paymentResponse['message'] ?? 'Payment initiation failed.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Initiation Error', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('member.dashboard')
                ->with('error', 'An error occurred while processing your request.');
        }
    }


    /**
     * Handle payment callback from PhonePe
     */
    public function callback(Request $request)
    {
        try {
            $base64Response = $request->input('response');
            $xVerifyHeader  = $request->header('X-VERIFY');

            // Verify signature
            if ($xVerifyHeader && !$this->phonePeService->verifyCallbackSignature($base64Response, $xVerifyHeader)) {
                Log::warning('PhonePe: Callback signature mismatch');
            }

            // Decode response
            $response              = json_decode(base64_decode($base64Response), true);
            $merchantTransactionId = $response['data']['merchantTransactionId'] ?? null;

            if (!$merchantTransactionId) {
                Log::error('PhonePe: Callback missing merchantTransactionId', ['response' => $response]);
                return redirect()->route('payment.failure')->with('error', 'Invalid payment response.');
            }

            $order = Order::where('order_id', $merchantTransactionId)->first();
            if (!$order) {
                Log::error('PhonePe: Order not found', ['transaction_id' => $merchantTransactionId]);
                return redirect()->route('payment.failure')->with('error', 'Order not found.');
            }

            // Server-side verification
            $verification = $this->phonePeService->verifyPayment($merchantTransactionId);

            DB::beginTransaction();
            try {
                $order->update([
                    'payment_response' => array_merge(
                        $order->payment_response ?? [],
                        ['callback' => $response, 'verification' => $verification]
                    ),
                ]);

                if ($verification['success'] && ($verification['state'] ?? '') === 'COMPLETED') {
                    $order->update([
                        'status'         => 'success',
                        'transaction_id' => $verification['data']['transactionId'] ?? $order->transaction_id,
                        'paid_at'        => now(),
                    ]);

                    $this->activateMembership($order);
                    DB::commit();

                    return redirect()->route('payment.success', ['order' => $order->id]);
                }

                $order->update(['status' => 'failed']);
                DB::commit();

                return redirect()->route('payment.failure')->with('error', 'Payment verification failed.');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('PhonePe: Callback processing error', [
                    'order_id' => $order->id,
                    'error'    => $e->getMessage(),
                ]);
                return redirect()->route('payment.failure')->with('error', 'An error occurred while processing payment.');
            }

        } catch (\Exception $e) {
            Log::error('PhonePe: Callback error', ['error' => $e->getMessage()]);
            return redirect()->route('payment.failure')->with('error', 'Payment processing failed.');
        }
    }


    /**
     * Activate membership after successful payment
     */
    private function activateMembership(Order $order)
    {
        $plan = $order->membershipPlan;
        $user = $order->user;

        // Get delivery address and location from session
        $deliveryAddress = session('delivery_address_' . $order->id, '');
        $locationId = session('location_id_' . $order->id);

        // Calculate start and end dates
        $startDate = now();
        $endDate = $this->calculateEndDate($startDate, $plan->duration);

        // Create user subscription
        UserSubscription::create([
            'user_id' => $user->id,
            'membership_plan_id' => $plan->id,
            'location_id' => $locationId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'payment_method' => 'phonepe',
            'payment_status' => 'paid',
            'delivery_address' => $deliveryAddress,
            'amount_paid' => $order->amount,
            'transaction_id' => $order->transaction_id,
            'notes' => 'Activated via PhonePe payment. Order ID: ' . $order->order_id
        ]);

        // Clear session data
        session()->forget(['delivery_address_' . $order->id, 'location_id_' . $order->id]);

        Log::info('Membership Activated', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'location_id' => $locationId,
            'order_id' => $order->order_id
        ]);
    }

    /**
     * Calculate end date based on duration
     */
    private function calculateEndDate($startDate, $duration)
    {
        $duration = strtolower($duration);
        
        if (str_contains($duration, 'month')) {
            $months = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);
            return $startDate->copy()->addMonths($months ?: 1);
        }
        
        if (str_contains($duration, 'week')) {
            $weeks = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);
            return $startDate->copy()->addWeeks($weeks ?: 1);
        }
        
        if (str_contains($duration, 'day')) {
            $days = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);
            return $startDate->copy()->addDays($days ?: 1);
        }

        // Default to 1 month
        return $startDate->copy()->addMonth();
    }


    /**
     * Show payment success page
     */
    public function success(Order $order)
    {
        // Verify order belongs to current user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->isSuccess()) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Invalid order status.');
        }

        return view('payment.success', compact('order'));
    }

    /**
     * Show payment failure page
     */
    public function failure()
    {
        return view('payment.failure');
    }

    /**
     * Show payment history
     */
    public function history()
    {
        $orders = auth()->user()->orders()
            ->with('membershipPlan')
            ->latest()
            ->paginate(10);

        return view('payment.history', compact('orders'));
    }

    /**
     * Show invoice/bill for an order
     */
    public function invoice(Order $order)
    {
        // Verify order belongs to current user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->isSuccess()) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Invoice is only available for successful payments.');
        }

        return view('payment.invoice', compact('order'));
    }
}
