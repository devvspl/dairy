<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\MilkWalletTransaction;
use App\Models\Order;
use App\Models\MembershipPlan;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\UserSubscription;
use App\Services\PhonePeService;
use App\Services\ShiprocketService;
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
            'plan_id'          => 'required|exists:membership_plans,id',
            'location_id'      => 'required|exists:locations,id',
            'delivery_address' => 'required|string|max:500',
            'milk_type'        => 'nullable|string|max:100',
            'quantity_per_day' => 'nullable|numeric|min:0.5|max:20',
            'start_date'       => 'nullable|date|after_or_equal:today',
            'delivery_slot'    => 'nullable|string|max:50',
            'coupon_code'      => 'nullable|string|max:50',
        ]);

        $user = auth()->user();
        $plan = MembershipPlan::findOrFail($request->plan_id);
        $location = \App\Models\Location::findOrFail($request->location_id);

        // For scheduled plans only: block if already has an active scheduled subscription
        if ($plan->isScheduled()) {
            $activeSubscription = $user->activeSubscription()->first();
            if ($activeSubscription && $activeSubscription->membershipPlan->isScheduled()) {
                return redirect()->route('member.dashboard')
                    ->with('error', 'You already have an active scheduled subscription.');
            }
        }

        // Apply coupon if provided
        $couponCode     = null;
        $discountAmount = 0;
        $finalAmount    = $plan->price;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
            if ($coupon && $coupon->isValid() && $coupon->applicable_to !== 'products') {
                if ($coupon->canBeUsedBy($user->id)) {
                    $calculated = $coupon->calculateDiscount($plan->price);
                    if ($calculated > 0) {
                        $discountAmount = min($calculated, $plan->price - 1);
                        $couponCode     = $coupon->code;
                        $finalAmount    = $plan->price - $discountAmount;
                    }
                }
            }
        }

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'user_id'            => $user->id,
                'membership_plan_id' => $plan->id,
                'order_id'           => Order::generateOrderId(),
                'amount'             => $finalAmount,
                'coupon_code'        => $couponCode,
                'discount_amount'    => $discountAmount,
                'status'             => 'pending',
                'payment_method'     => 'phonepe',
            ]);

            // Store delivery details in session for later use
            session([
                'delivery_address_' . $order->id => $request->delivery_address,
                'location_id_' . $order->id      => $request->location_id,
                'milk_type_' . $order->id         => $request->milk_type,
                'quantity_per_day_' . $order->id  => $request->quantity_per_day,
                'start_date_' . $order->id        => $request->start_date,
                'delivery_slot_' . $order->id     => $request->delivery_slot,
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
                // Update order with PhonePe's internal orderId (OMO...) from v2 response
                $order->update([
                    'transaction_id'   => $paymentResponse['data']['orderId'] ?? null,
                    'payment_response' => $paymentResponse,
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
        // Log everything PhonePe sends back for debugging
        Log::info('PhonePe: Callback received', [
            'method'  => $request->method(),
            'query'   => $request->query(),
            'input'   => $request->input(),
            'headers' => [
                'Authorization' => $request->header('Authorization'),
                'X-VERIFY'      => $request->header('X-VERIFY'),
            ],
        ]);

        try {
            // v2 API: merchantOrderId is embedded in the redirectUrl as a query param
            $merchantOrderId = $request->query('merchantOrderId')
                ?? $request->query('transactionId')
                ?? $request->input('merchantOrderId')
                ?? $request->input('transactionId');

            if (!$merchantOrderId) {
                Log::error('PhonePe: Callback missing merchantOrderId', [
                    'all_input' => $request->all(),
                    'all_query' => $request->query(),
                ]);
                return redirect()->route('payment.failure')->with('error', 'Invalid payment response.');
            }

            // Try our order_id first (ORD...), then PhonePe's orderId (OMO...) stored as transaction_id
            $order = Order::where('order_id', $merchantOrderId)->first()
                ?? Order::where('transaction_id', $merchantOrderId)->first();

            if (!$order) {
                Log::error('PhonePe: Order not found', ['merchant_order_id' => $merchantOrderId]);
                return redirect()->route('payment.failure')->with('error', 'Order not found.');
            }

            // Server-side verification using our original order_id
            $verification = $this->phonePeService->verifyPayment($order->order_id);

            Log::info('PhonePe: Callback verification result', [
                'order_id'     => $order->order_id,
                'state'        => $verification['state'] ?? null,
                'success'      => $verification['success'],
            ]);

            DB::beginTransaction();
            try {
                $order->update([
                    'payment_response' => array_merge(
                        $order->payment_response ?? [],
                        ['verification' => $verification]
                    ),
                ]);

                if ($verification['success'] && ($verification['state'] ?? '') === 'COMPLETED') {
                    $order->update([
                        'status'         => 'success',
                        'transaction_id' => $verification['data']['orderId'] ?? $merchantOrderId,
                        'paid_at'        => now(),
                    ]);

                    if ($order->isWalletTopup()) {
                        $this->processWalletTopup($order);
                    } else {
                        $this->activateMembership($order);
                    }
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
     * Process wallet top-up after successful payment
     */
    private function processWalletTopup(Order $order): void
    {
        $user = $order->user;

        // ── First-time wallet creation (no subscription yet) ──────────
        if (!$order->user_subscription_id) {
            $meta = $order->wallet_meta ?? [];

            $milkType        = $meta['milk_type']        ?? null;
            $qtyPerDay       = $meta['quantity_per_day'] ?? null;
            $slot            = $meta['delivery_slot']    ?? null;
            $locationId      = $meta['location_id']      ?? null;
            $deliveryAddress = $meta['delivery_address'] ?? null;
            $startDate       = $meta['start_date']       ?? null;
            $pricePerLitre   = $meta['price_per_litre']  ?? null;

            // Fallback: look up from milk_prices if not stored
            if (!$pricePerLitre && $milkType) {
                $mp = \App\Models\MilkPrice::forType($milkType);
                $pricePerLitre = $mp ? (float) $mp->price_per_litre : null;
            }

            $start = $startDate ? \Carbon\Carbon::parse($startDate) : now();
            // Open-ended: 10 years — admin controls actual delivery via DeliveryLog
            $end   = $start->copy()->addYears(10);

            $subscription = UserSubscription::create([
                'user_id'            => $user->id,
                'membership_plan_id' => null,
                'location_id'        => $locationId,
                'start_date'         => $start,
                'end_date'           => $end,
                'status'             => 'active',
                'delivery_status'    => 'active',
                'payment_method'     => 'phonepe',
                'payment_status'     => 'paid',
                'delivery_address'   => $deliveryAddress,
                'amount_paid'        => $order->amount,
                'transaction_id'     => $order->transaction_id,
                'wallet_total'       => (float) $order->amount,
                'wallet_balance'     => (float) $order->amount,
                'price_per_litre'    => $pricePerLitre,
                'milk_type'          => $milkType,
                'quantity_per_day'   => $qtyPerDay,
                'delivery_slot'      => $slot,
                'notes'              => 'Wallet created via PhonePe | Order: ' . $order->order_id,
            ]);

            // Link order to subscription
            $order->update(['user_subscription_id' => $subscription->id]);

            // Record initial credit transaction
            MilkWalletTransaction::create([
                'user_id'              => $user->id,
                'user_subscription_id' => $subscription->id,
                'type'                 => 'credit',
                'amount'               => (float) $order->amount,
                'balance_after'        => (float) $order->amount,
                'description'          => 'Wallet opened | Order: ' . $order->order_id,
                'transaction_date'     => now()->toDateString(),
            ]);

            // Auto-generate 90 days of delivery logs
            \App\Models\DeliveryLog::autoGenerate($subscription);

            Log::info('Wallet Created', ['user_id' => $user->id, 'subscription_id' => $subscription->id]);
            return;
        }

        // ── Top-up existing wallet ────────────────────────────────────
        $subscription = $order->subscription;
        if (!$subscription) return;

        $subscription->creditWallet(
            (float) $order->amount,
            'Wallet top-up | Order: ' . $order->order_id
        );

        // Extend delivery log window by 90 more days from the last existing entry
        \App\Models\DeliveryLog::autoGenerate($subscription);

        Log::info('Wallet Top-up Processed', [
            'user_id'         => $order->user_id,
            'subscription_id' => $subscription->id,
            'amount'          => $order->amount,
        ]);
    }

    /**
     * Activate membership after successful payment
     */
    private function activateMembership(Order $order)
    {
        $plan = $order->membershipPlan;
        $user = $order->user;

        // Get delivery details from session
        $deliveryAddress = session('delivery_address_' . $order->id, '');
        $locationId      = session('location_id_' . $order->id);
        $milkType        = session('milk_type_' . $order->id);
        $quantityPerDay  = session('quantity_per_day_' . $order->id);
        $requestedStart  = session('start_date_' . $order->id);
        $deliverySlot    = session('delivery_slot_' . $order->id);

        // Calculate start and end dates
        $startDate = $requestedStart ? \Carbon\Carbon::parse($requestedStart) : now();
        $endDate   = $startDate->copy()->addDays($plan->duration_days - 1);

        // Build notes
        $notes = 'Activated via PhonePe. Order ID: ' . $order->order_id;
        if ($milkType)       $notes .= ' | Milk: ' . $milkType;
        if ($quantityPerDay) $notes .= ' | Qty/day: ' . $quantityPerDay . 'L';
        if ($deliverySlot)   $notes .= ' | Slot: ' . $deliverySlot;

        // For on-demand plans: use price_per_litre from milk_prices table
        $walletTotal    = null;
        $walletBalance  = null;
        $pricePerLitre  = null;
        if ($plan->isOnDemand()) {
            $walletTotal   = (float) $order->amount;
            $walletBalance = (float) $order->amount;
            // Look up price per litre from milk_prices config table
            $milkPriceRow  = $milkType ? \App\Models\MilkPrice::forType($milkType) : null;
            $pricePerLitre = $milkPriceRow ? (float) $milkPriceRow->price_per_litre : null;
            // Fallback: derive from plan price if no config found
            if (!$pricePerLitre) {
                $qtyPerDay     = (float) ($quantityPerDay ?: 1);
                $totalLitres   = $plan->duration_days * $qtyPerDay;
                $pricePerLitre = $totalLitres > 0 ? round($walletTotal / $totalLitres, 2) : null;
            }
        }

        // Check if user already has an on-demand subscription for this plan (top-up scenario)
        $existingSub = null;
        if ($plan->isOnDemand()) {
            $existingSub = UserSubscription::where('user_id', $user->id)
                ->where('membership_plan_id', $plan->id)
                ->whereIn('status', ['active', 'pending'])
                ->first();
        }

        if ($existingSub && $plan->isOnDemand()) {
            // Top-up: extend end date and credit wallet
            $existingSub->update([
                'end_date' => $existingSub->end_date->addDays($plan->duration_days - 1),
                'status'   => 'active',
            ]);
            $existingSub->creditWallet((float) $order->amount, 'Pack top-up: ' . $plan->name . ' | Order: ' . $order->order_id);
            $subscription = $existingSub;
        } else {
            // Create new subscription
            $subscription = UserSubscription::create([
                'user_id'            => $user->id,
                'membership_plan_id' => $plan->id,
                'location_id'        => $locationId,
                'start_date'         => $startDate,
                'end_date'           => $endDate,
                'status'             => 'active',
                'payment_method'     => 'phonepe',
                'payment_status'     => 'paid',
                'delivery_address'   => $deliveryAddress,
                'amount_paid'        => $order->amount,
                'transaction_id'     => $order->transaction_id,
                'notes'              => $notes,
                'wallet_total'       => $walletTotal,
                'wallet_balance'     => $walletBalance,
                'price_per_litre'    => $pricePerLitre,
                'milk_type'          => $milkType,
                'quantity_per_day'   => $quantityPerDay,
                'delivery_slot'      => $deliverySlot,
            ]);

            // Record initial wallet credit transaction for on-demand
            if ($plan->isOnDemand()) {
                \App\Models\MilkWalletTransaction::create([
                    'user_id'              => $user->id,
                    'user_subscription_id' => $subscription->id,
                    'type'                 => 'credit',
                    'amount'               => $walletTotal,
                    'balance_after'        => $walletBalance,
                    'description'          => 'Pack purchased: ' . $plan->name . ' | Order: ' . $order->order_id,
                    'transaction_date'     => now()->toDateString(),
                ]);
            }
        }

        // Clear session data
        session()->forget([
            'delivery_address_' . $order->id,
            'location_id_' . $order->id,
            'milk_type_' . $order->id,
            'quantity_per_day_' . $order->id,
            'start_date_' . $order->id,
            'delivery_slot_' . $order->id,
        ]);

        Log::info('Membership Activated', [
            'user_id'    => $user->id,
            'plan_id'    => $plan->id,
            'plan_type'  => $plan->plan_type,
            'location_id' => $locationId,
            'order_id'   => $order->order_id,
        ]);

        // Record coupon usage
        if ($order->coupon_code) {
            $coupon = Coupon::where('code', $order->coupon_code)->first();
            if ($coupon) {
                CouponUsage::create([
                    'coupon_id'       => $coupon->id,
                    'user_id'         => $user->id,
                    'discount_amount' => $order->discount_amount,
                ]);
                $coupon->increment('times_used');
            }
        }
    }

    /**
     * Calculate end date based on plan duration_days accessor
     */
    private function calculateEndDate($startDate, $plan)
    {
        return $startDate->copy()->addDays($plan->duration_days - 1);
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

    // =========================================================
    // Product Cart Payment
    // =========================================================

    /**
     * Get price per litre for a milk type (AJAX)
     */
    public function milkPrice(Request $request)
    {
        $price = \App\Models\MilkPrice::forType($request->milk_type);
        return response()->json([
            'price_per_litre' => $price ? (float) $price->price_per_litre : null,
            'label'           => $price ? $price->label : null,
        ]);
    }

    /**
     * Validate and apply a coupon to a membership/milk order (AJAX)
     */
    public function applyCouponMembership(Request $request)
    {
        $request->validate([
            'code'    => 'required|string',
            'plan_id' => 'required|exists:membership_plans,id',
        ]);

        $plan   = MembershipPlan::findOrFail($request->plan_id);
        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
        }
        if (!$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'This coupon has expired or is no longer active.']);
        }
        if ($coupon->applicable_to === 'products') {
            return response()->json(['success' => false, 'message' => 'This coupon is only valid for product orders.']);
        }
        if (!$coupon->canBeUsedBy(auth()->id())) {
            return response()->json(['success' => false, 'message' => 'You have already used this coupon.']);
        }

        $discount = $coupon->calculateDiscount($plan->price);
        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount of ₹' . number_format($coupon->min_purchase_amount, 0) . ' required.',
            ]);
        }

        $discount    = min($discount, $plan->price - 1);
        $finalAmount = $plan->price - $discount;

        return response()->json([
            'success'      => true,
            'discount'     => $discount,
            'final_amount' => $finalAmount,
            'coupon_code'  => $coupon->code,
            'coupon_name'  => $coupon->name,
            'message'      => 'Coupon applied! You save ₹' . number_format($discount, 2),
        ]);
    }

    /**
     * Validate and apply a coupon to a product cart (AJAX)
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code'  => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
        }

        if (!$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'This coupon has expired or is no longer active.']);
        }

        // Check applicable_to
        if ($coupon->applicable_to === 'membership') {
            return response()->json(['success' => false, 'message' => 'This coupon is only valid for membership plans.']);
        }

        // Check per-user usage if logged in
        if (auth()->check()) {
            if (!$coupon->canBeUsedBy(auth()->id())) {
                return response()->json(['success' => false, 'message' => 'You have already used this coupon.']);
            }
        }

        $total    = (float) $request->total;
        $discount = $coupon->calculateDiscount($total);

        // Ensure at least ₹1 remains payable
        $discount = min($discount, $total - 1);

        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount of ₹' . number_format($coupon->min_purchase_amount, 0) . ' required.',
            ]);
        }

        return response()->json([
            'success'         => true,
            'discount'        => $discount,
            'final_total'     => max(0, $total - $discount),
            'coupon_code'     => $coupon->code,
            'coupon_name'     => $coupon->name,
            'message'         => 'Coupon applied! You save ₹' . number_format($discount, 2),
        ]);
    }

    /**
     * Initiate payment for a product cart order (guest or logged-in)
     */
    public function initiateProductOrder(Request $request)
    {
        // Must be a logged-in member
        if (!auth()->check() || !auth()->user()->isMember()) {
            return response()->json(['success' => false, 'message' => 'Please login as a member to proceed.', 'redirect' => route('member.login')], 401);
        }

        $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.id'       => 'required|integer|exists:products,id',
            'items.*.name'     => 'required|string',
            'items.*.price'    => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:255',
            'delivery_address' => 'required|string|max:500',
            'coupon_code'      => 'nullable|string|max:50',
        ]);

        // Verify prices server-side and check stock
        $total = 0;
        $items = [];
        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['id']);

            if ($product->stock_status === 'out_of_stock') {
                return response()->json(['success' => false, 'message' => "{$product->name} is out of stock."], 422);
            }

            $items[] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => (float) $product->price,
                'quantity' => (int) $item['quantity'],
                'image'    => $product->main_image,
            ];
            $total += $product->price * $item['quantity'];
        }

        // Apply coupon server-side
        $couponCode     = null;
        $discountAmount = 0;
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
            if ($coupon && $coupon->isValid() && $coupon->applicable_to !== 'membership') {
                $user = auth()->user();
                if (!$user || $coupon->canBeUsedBy($user->id)) {
                    $calculated = $coupon->calculateDiscount($total);
                    if ($calculated > 0) {
                        // Ensure at least ₹1 remains payable (PhonePe minimum = 100 paise)
                        $discountAmount = min($calculated, $total - 1);
                        if ($discountAmount > 0) {
                            $couponCode = $coupon->code;
                            $total      = $total - $discountAmount;
                        }
                    }
                }
            }
        }

        DB::beginTransaction();
        try {
            $user  = auth()->user();
            $order = ProductOrder::create([
                'user_id'          => $user?->id,
                'order_id'         => ProductOrder::generateOrderId(),
                'amount'           => $total,
                'discount_amount'  => $discountAmount,
                'coupon_code'      => $couponCode,
                'status'           => 'pending',
                'payment_method'   => 'phonepe',
                'items'            => $items,
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'customer_email'   => $request->customer_email,
                'delivery_address' => $request->delivery_address,
            ]);

            $paymentResponse = $this->phonePeService->initiatePayment(
                $order->order_id,
                $order->amount,
                $user?->id ?? 0,
                $request->customer_name,
                $request->customer_phone,
                'payment.product.callback'
            );

            if ($paymentResponse['success']) {
                $order->update([
                    'transaction_id'   => $paymentResponse['data']['orderId'] ?? null,
                    'payment_response' => $paymentResponse,
                ]);
                DB::commit();

                return response()->json([
                    'success'      => true,
                    'redirect_url' => $paymentResponse['redirect_url'],
                ]);
            }

            DB::rollBack();
            return response()->json(['success' => false, 'message' => $paymentResponse['message'] ?? 'Payment initiation failed.'], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product Order Payment Error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    /**
     * Handle PhonePe callback for product orders
     */
    public function productOrderCallback(Request $request)
    {
        $merchantOrderId = $request->query('merchantOrderId')
            ?? $request->input('merchantOrderId');

        if (!$merchantOrderId) {
            return redirect()->route('products')->with('error', 'Invalid payment response.');
        }

        $order = ProductOrder::where('order_id', $merchantOrderId)->first()
            ?? ProductOrder::where('transaction_id', $merchantOrderId)->first();

        if (!$order) {
            return redirect()->route('products')->with('error', 'Order not found.');
        }

        $verification = $this->phonePeService->verifyPayment($order->order_id);

        DB::beginTransaction();
        try {
            if ($verification['success'] && ($verification['state'] ?? '') === 'COMPLETED') {
                $order->update([
                    'status'           => 'success',
                    'transaction_id'   => $verification['data']['orderId'] ?? $merchantOrderId,
                    'paid_at'          => now(),
                    'payment_response' => array_merge($order->payment_response ?? [], ['verification' => $verification]),
                ]);

                // Deduct stock for each item
                foreach ($order->items as $item) {
                    Product::where('id', $item['id'])->each(function ($product) use ($item) {
                        if ($product->stock_quantity !== null) {
                            $newQty = max(0, $product->stock_quantity - $item['quantity']);
                            $product->update([
                                'stock_quantity' => $newQty,
                                'stock_status'   => $newQty === 0 ? 'out_of_stock' : ($newQty <= 10 ? 'limited' : 'available'),
                            ]);
                        }
                    });
                }

                // Record coupon usage
                if ($order->coupon_code && $order->user_id) {
                    $coupon = Coupon::where('code', $order->coupon_code)->first();
                    if ($coupon) {
                        CouponUsage::create([
                            'coupon_id'        => $coupon->id,
                            'user_id'          => $order->user_id,
                            'product_order_id' => $order->id,
                            'discount_amount'  => $order->discount_amount,
                        ]);
                        $coupon->increment('times_used');
                    }
                }

                // Assign shiprocket 
                $shiprocket = app(ShiprocketService::class);

                if ($shiprocket->isEnabled() && !$order->isShiprocketAssigned()) {
                    $shipment = $shiprocket->createOrder($order);

                    if ($shipment['success']) {
                        $order->update([
                            'shiprocket_order_id'    => $shipment['order_id'],
                            'shiprocket_shipment_id' => $shipment['shipment_id'],
                            'shiprocket_awb'         => $shipment['awb_code'],
                            'shiprocket_courier'     => $shipment['courier'],
                            'shiprocket_status'      => $shipment['status'] ?? 'NEW',
                            'shiprocket_assigned_at' => now(),
                        ]);
                    } else {
                        Log::error('Shiprocket Assign Failed', [
                            'order_id' => $order->id,
                            'error'    => $shipment['message'] ?? 'Unknown error',
                        ]);
                    }
                }


                DB::commit();
                return redirect()->route('payment.product.success', $order->id);
            }

            $order->update(['status' => 'failed']);
            DB::commit();
            return redirect()->route('payment.failure')->with('error', 'Payment verification failed.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product Order Callback Error', ['error' => $e->getMessage()]);
            return redirect()->route('payment.failure')->with('error', 'An error occurred.');
        }
    }

    /**
     * Product order success page
     */
    public function productOrderSuccess(ProductOrder $order)
    {
        if (!$order->isSuccess()) {
            return redirect()->route('products');
        }
        return view('payment.product-success', compact('order'));
    }
}
