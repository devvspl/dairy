<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\DeliveryLog;
use App\Models\Order;
use App\Models\SubscriptionChangeLog;
use App\Models\SubscriptionDeliverySettings;
use App\Models\UserSubscription;
use App\Services\PhonePeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    public function __construct(protected PhonePeService $phonePeService) {}

    /** GET /wallet/calendar */
    public function calendar(Request $request)
    {
        $user  = auth()->user();
        $subId = $request->integer('subscription_id');
        $year  = $request->integer('year',  now()->year);
        $month = $request->integer('month', now()->month);

        $subscription = UserSubscription::where('id', $subId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $start   = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $end     = $start->copy()->endOfMonth();

        $txns = \App\Models\MilkWalletTransaction::where('user_subscription_id', $subscription->id)
            ->whereYear('transaction_date', $year)->whereMonth('transaction_date', $month)
            ->get()->keyBy(fn($t) => $t->transaction_date->format('Y-m-d'));

        $deliveries = DeliveryLog::where('user_subscription_id', $subscription->id)
            ->whereYear('delivery_date', $year)->whereMonth('delivery_date', $month)
            ->get()->keyBy(fn($d) => $d->delivery_date->format('Y-m-d'));

        $days = [];
        $cur  = $start->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
        while ($cur->lte($end->copy()->endOfWeek(\Carbon\Carbon::SUNDAY))) {
            $key = $cur->format('Y-m-d');
            $txn = $txns->get($key);
            $del = $deliveries->get($key);
            $days[] = [
                'date'     => $key,
                'day'      => $cur->day,
                'inMonth'  => $cur->month === $month,
                'isToday'  => $cur->isToday(),
                'txn'      => $txn ? ['type' => $txn->type, 'amount' => (float)$txn->amount, 'litres' => (float)$txn->litres] : null,
                'delivery' => $del ? ['status' => $del->status, 'qty' => (float)$del->quantity_delivered] : null,
            ];
            $cur->addDay();
        }

        return response()->json(['days' => $days]);
    }

    /** POST /wallet/initiate */
    public function initiate(Request $request)
    {
        $request->validate([
            'amount'           => 'required|numeric|min:1|max:500000',
            'milk_type'        => 'required|string|max:50',
            'quantity_per_day' => 'required|numeric|min:0.5|max:20',
            'delivery_slot'    => 'required|in:morning,afternoon,evening',
            'location_id'      => 'required|exists:locations,id',
            'delivery_address' => 'required|string|max:500',
            'start_date'       => 'required|date|after_or_equal:today',
        ]);

        $user          = auth()->user();
        $amount        = (float) $request->amount;
        $milkPriceRow  = \App\Models\MilkPrice::forType($request->milk_type);
        $pricePerLitre = $milkPriceRow ? (float) $milkPriceRow->price_per_litre : null;

        // Cutoff time validation
        $startDate = \Carbon\Carbon::parse($request->start_date);
        if ($milkPriceRow && $milkPriceRow->cutoff_time) {
            $startDate = $this->adjustStartDateForCutoff($startDate, $milkPriceRow->cutoff_time);
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'        => $user->id,
                'order_type'     => 'wallet_topup',
                'order_id'       => Order::generateOrderId(),
                'amount'         => $amount,
                'status'         => 'pending',
                'payment_method' => 'phonepe',
                'wallet_meta'    => [
                    'milk_type'        => $request->milk_type,
                    'quantity_per_day' => $request->quantity_per_day,
                    'delivery_slot'    => $request->delivery_slot,
                    'location_id'      => $request->location_id,
                    'delivery_address' => $request->delivery_address,
                    'start_date'       => $startDate->format('Y-m-d'),
                    'price_per_litre'  => $pricePerLitre,
                ],
            ]);

            $paymentResponse = $this->phonePeService->initiatePayment(
                $order->order_id, $amount, $user->id, $user->name, $user->phone ?? '9999999999'
            );

            if ($paymentResponse['success']) {
                $order->update(['transaction_id' => $paymentResponse['data']['orderId'] ?? null, 'payment_response' => $paymentResponse]);
                DB::commit();
                return redirect($paymentResponse['redirect_url']);
            }

            DB::rollBack();
            return redirect()->route('member.dashboard')->with('error', $paymentResponse['message'] ?? 'Payment initiation failed.');
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

        $action    = $request->input('action', 'pause');
        $newStatus = $action === 'resume' ? 'active' : 'paused';
        $oldStatus = $subscription->delivery_status;

        $subscription->update(['delivery_status' => $newStatus]);

        SubscriptionChangeLog::record(
            $subscription->id,
            auth()->id(),
            $newStatus === 'paused' ? 'pause' : 'resume',
            ['delivery_status' => $oldStatus],
            ['delivery_status' => $newStatus]
        );

        if ($newStatus === 'paused') {
            DeliveryLog::where('user_subscription_id', $subscription->id)
                ->whereDate('delivery_date', now())
                ->where('status', 'pending')
                ->update(['status' => 'skipped', 'notes' => 'Paused by member', 'marked_at' => now()]);
            $msg = "Deliveries paused. Today's delivery has been skipped.";
        } else {
            $tomorrow = now()->addDay()->format('Y-m-d');
            DeliveryLog::firstOrCreate(
                ['user_subscription_id' => $subscription->id, 'delivery_date' => $tomorrow],
                ['quantity_delivered' => (float)($subscription->quantity_per_day ?? 1), 'status' => 'pending']
            );
            $msg = "Deliveries resumed. Tomorrow's delivery is scheduled.";
        }

        return redirect()->route('member.dashboard')->with('success', $msg);
    }

    /** PATCH /wallet/{subscription}/stop */
    public function stop(UserSubscription $subscription)
    {
        $this->authorizeSubscription($subscription);

        $oldStatus = $subscription->delivery_status;

        DeliveryLog::where('user_subscription_id', $subscription->id)
            ->where('status', 'pending')
            ->whereDate('delivery_date', '>=', now()->toDateString())
            ->update(['status' => 'skipped', 'notes' => 'Stopped by member', 'marked_at' => now()]);

        $subscription->update(['delivery_status' => 'stopped']);

        SubscriptionChangeLog::record(
            $subscription->id,
            auth()->id(),
            'stop',
            ['delivery_status' => $oldStatus],
            ['delivery_status' => 'stopped'],
            'Member stopped all deliveries'
        );

        return redirect()->route('member.dashboard')
            ->with('success', 'Deliveries stopped. Your wallet balance is safe. Add money anytime to restart.');
    }

    /** PATCH /wallet/{subscription}/restart */
    public function restart(UserSubscription $subscription)
    {
        $this->authorizeSubscription($subscription);

        $oldStatus = $subscription->delivery_status;
        $subscription->update(['delivery_status' => 'active']);
        DeliveryLog::autoGenerate($subscription);

        SubscriptionChangeLog::record(
            $subscription->id,
            auth()->id(),
            'restart',
            ['delivery_status' => $oldStatus],
            ['delivery_status' => 'active'],
            'Member restarted deliveries'
        );

        return redirect()->route('member.dashboard')
            ->with('success', 'Deliveries restarted. Schedule has been regenerated from tomorrow.');
    }

    /** PATCH /wallet/{subscription}/update */
    public function update(Request $request, UserSubscription $subscription)
    {
        $this->authorizeSubscription($subscription);

        $data = $request->validate([
            'delivery_address'          => 'nullable|string|max:500',
            'delivery_instructions'     => 'nullable|string|max:500',
            'delivery_slot'             => 'nullable|in:morning,evening',
            'location_id'               => 'nullable|exists:locations,id',
            'milk_items'                => 'nullable|array|min:1',
            'milk_items.*.milk_type'    => 'required_with:milk_items|string|max:50',
            'milk_items.*.qty'          => 'required_with:milk_items|numeric|min:0.5|max:20',
            'milk_items.*.ppl'          => 'nullable|numeric|min:0',
        ]);

        // Enrich milk_items: apply shared slot + refresh price from DB
        if (!empty($data['milk_items'])) {
            $sharedSlot = $data['delivery_slot'] ?? 'morning';
            $enriched   = [];
            foreach ($data['milk_items'] as $item) {
                if (empty($item['milk_type'])) continue;
                $mp = \App\Models\MilkPrice::forType($item['milk_type']);
                $enriched[] = [
                    'milk_type' => $item['milk_type'],
                    'qty'       => (float) ($item['qty'] ?? 1),
                    'slot'      => $sharedSlot,
                    'ppl'       => $mp ? (float) $mp->price_per_litre : (float) ($item['ppl'] ?? 0),
                ];
            }
            $data['milk_items'] = $enriched;
        }

        if (empty($data['milk_items']) && !isset($data['delivery_address']) && !isset($data['delivery_slot']) && !isset($data['delivery_instructions'])) {
            return redirect()->route('member.dashboard')->with('error', 'No changes provided.');
        }

        // Capture old values for logging
        $oldSettings = $subscription->deliverySettings;
        $oldValues   = $oldSettings ? $oldSettings->only(['milk_type', 'quantity_per_day', 'delivery_slot', 'milk_items']) : [];

        // Update future delivery logs if milk_items changed
        if (!empty($data['milk_items'])) {
            $totalQty = array_sum(array_column($data['milk_items'], 'qty'));
            DeliveryLog::where('user_subscription_id', $subscription->id)
                ->where('status', 'pending')
                ->whereDate('delivery_date', '>=', now()->toDateString())
                ->update([
                    'quantity_delivered' => $totalQty,
                    'milk_items'         => json_encode($data['milk_items']),
                ]);

            // Update price_per_litre on subscription (first item, for legacy compat)
            $mp = \App\Models\MilkPrice::forType($data['milk_items'][0]['milk_type']);
            if ($mp) $subscription->update(['price_per_litre' => (float) $mp->price_per_litre]);
        }

        // Build settings payload
        $settingsData = array_filter([
            'milk_items'            => $data['milk_items'] ?? null,
            'delivery_slot'         => $data['delivery_slot'] ?? null,
            'delivery_address'      => $data['delivery_address'] ?? null,
            'delivery_instructions' => $data['delivery_instructions'] ?? null,
            'location_id'           => $data['location_id'] ?? null,
            // legacy single-milk fields derived from items
            'milk_type'             => $data['milk_items'][0]['milk_type'] ?? null,
            'quantity_per_day'      => !empty($data['milk_items']) ? array_sum(array_column($data['milk_items'], 'qty')) : null,
        ], fn($v) => $v !== null);

        // Save to delivery settings table
        $subscription->deliverySettings()->updateOrCreate(
            ['user_subscription_id' => $subscription->id],
            $settingsData
        );

        // Sync to subscription row for backward compat
        $syncToSub = array_filter([
            'milk_type'             => $settingsData['milk_type'] ?? null,
            'quantity_per_day'      => $settingsData['quantity_per_day'] ?? null,
            'delivery_slot'         => $data['delivery_slot'] ?? null,
            'delivery_address'      => $data['delivery_address'] ?? null,
            'delivery_instructions' => $data['delivery_instructions'] ?? null,
            'location_id'           => $data['location_id'] ?? null,
        ], fn($v) => $v !== null);

        if (!empty($syncToSub)) {
            $subscription->update($syncToSub);
        }

        SubscriptionChangeLog::record(
            $subscription->id,
            auth()->id(),
            'settings_update',
            $oldValues,
            $settingsData,
            'Member updated delivery settings'
        );

        return redirect()->route('member.dashboard')->with('success', 'Delivery preferences updated.');
    }

    /** DELETE /wallet/{subscription}/extra — remove extra milk for a pending day */
    public function removeExtra(Request $request, UserSubscription $subscription)
    {
        $this->authorizeSubscription($subscription);

        $data = $request->validate([
            'date' => 'required|date|after:today',
        ]);

        $settings = $subscription->deliverySettings;
        $baseQty  = $settings ? $settings->totalQtyPerDay() : (float)($subscription->quantity_per_day ?? 1);

        $log = DeliveryLog::where('user_subscription_id', $subscription->id)
            ->whereDate('delivery_date', $data['date'])
            ->where('status', 'pending')
            ->first();

        if (!$log) {
            return redirect()->route('member.dashboard')->with('error', 'No pending delivery found for that date.');
        }

        $currentQty = (float) $log->quantity_delivered;

        if ($currentQty <= $baseQty) {
            return redirect()->route('member.dashboard')->with('error', 'No extra milk to remove for that date.');
        }

        $log->update([
            'quantity_delivered' => $baseQty,
            'notes'              => ($log->notes ? $log->notes . ' | ' : '') . 'Extra milk removed by member',
        ]);

        SubscriptionChangeLog::record(
            $subscription->id,
            auth()->id(),
            'extra_milk_removed',
            ['date' => $data['date'], 'quantity' => $currentQty],
            ['date' => $data['date'], 'quantity' => $baseQty],
            "Extra milk removed for {$data['date']}"
        );

        return redirect()->route('member.dashboard')
            ->with('success', 'Extra milk removed for ' . \Carbon\Carbon::parse($data['date'])->format('d M Y') . '.');
    }

    /** POST /wallet/{subscription}/extra */
    public function extra(Request $request, UserSubscription $subscription)
    {
        $this->authorizeSubscription($subscription);

        $data = $request->validate([
            'date'      => 'required|date|after_or_equal:today',
            'extra_qty' => 'required|numeric|min:1|max:20',
        ]);

        $date    = $data['date'];
        $extra   = (float) $data['extra_qty'];
        $baseQty = (float) ($subscription->quantity_per_day ?? 1);

        // Cutoff time validation - if ordering for today and past cutoff, push to tomorrow
        $milkPriceRow = \App\Models\MilkPrice::forType($subscription->milk_type);
        $requestedDate = \Carbon\Carbon::parse($date);
        if ($milkPriceRow && $milkPriceRow->cutoff_time) {
            $requestedDate = $this->adjustStartDateForCutoff($requestedDate, $milkPriceRow->cutoff_time);
            $date = $requestedDate->format('Y-m-d');
        }

        $log = DeliveryLog::firstOrCreate(
            ['user_subscription_id' => $subscription->id, 'delivery_date' => $date],
            ['quantity_delivered' => $baseQty, 'status' => 'pending']
        );

        $oldQty = (float) $log->quantity_delivered;
        $newQty = $oldQty + $extra;

        $log->update([
            'quantity_delivered' => $newQty,
            'notes'              => ($log->notes ? $log->notes . ' | ' : '') . "Extra {$extra}L requested by member",
        ]);

        SubscriptionChangeLog::record(
            $subscription->id,
            auth()->id(),
            'extra_milk',
            ['date' => $date, 'quantity' => $oldQty],
            ['date' => $date, 'quantity' => $newQty, 'extra_added' => $extra],
            "Extra {$extra}L requested for {$date}"
        );

        return redirect()->route('member.dashboard')
            ->with('success', "Extra {$extra}L added for " . \Carbon\Carbon::parse($date)->format('d M Y') . '.');
    }

    /** POST /wallet/{subscription}/topup */
    public function topup(Request $request, UserSubscription $subscription)
    {
        $this->authorizeSubscription($subscription);

        $request->validate(['amount' => 'required|numeric|min:1|max:500000']);

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
                $order->order_id, $amount, $user->id, $user->name, $user->phone ?? '9999999999'
            );

            if ($paymentResponse['success']) {
                $order->update(['transaction_id' => $paymentResponse['data']['orderId'] ?? null, 'payment_response' => $paymentResponse]);
                DB::commit();
                return redirect($paymentResponse['redirect_url']);
            }

            DB::rollBack();
            return redirect()->route('member.dashboard')->with('error', $paymentResponse['message'] ?? 'Top-up initiation failed.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Wallet Top-up Error', ['error' => $e->getMessage()]);
            return redirect()->route('member.dashboard')->with('error', 'An error occurred.');
        }
    }

    private function authorizeSubscription(UserSubscription $subscription): void
    {
        if ($subscription->user_id !== auth()->id()) abort(403);
    }

    /**
     * Adjust start date based on cutoff time
     * If current time is past cutoff and requested date is today, push to tomorrow
     */
    private function adjustStartDateForCutoff(\Carbon\Carbon $requestedDate, string $cutoffTime): \Carbon\Carbon
    {
        $now = now();
        $cutoff = \Carbon\Carbon::parse($cutoffTime);
        
        // Only apply cutoff logic if the requested date is today
        if ($requestedDate->isToday() && $now->greaterThan($cutoff)) {
            // Past cutoff time, push to tomorrow
            return $requestedDate->addDay();
        }
        
        return $requestedDate;
    }
}
