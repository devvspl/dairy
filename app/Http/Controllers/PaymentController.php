<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\MembershipPlan;
use App\Models\Product;
use App\Models\ProductOrder;
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

    // =========================================================
    // Product Cart Payment
    // =========================================================

    /**
     * Validate and apply a coupon to a product cart (AJAX)
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code'  => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        $coupon = \App\Models\Coupon::where('code', strtoupper(trim($request->code)))->first();

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
                    $discountAmount = $coupon->calculateDiscount($total);
                    if ($discountAmount > 0) {
                        $couponCode = $coupon->code;
                        $total      = max(0, $total - $discountAmount);
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
                            'coupon_id'       => $coupon->id,
                            'user_id'         => $order->user_id,
                            'order_id'        => $order->id,
                            'discount_amount' => $order->discount_amount,
                        ]);
                        $coupon->increment('times_used');
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
