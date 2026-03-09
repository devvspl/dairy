<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::active()->count(),
            'expired' => Coupon::where('valid_until', '<', now())->count(),
            'total_usage' => Coupon::sum('times_used'),
        ];
        
        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    public function create()
    {
        $membershipPlans = \App\Models\MembershipPlan::active()->orderBy('order')->get();
        $products = \App\Models\Product::active()->orderBy('name')->get();
        
        return view('admin.coupons.create', compact('membershipPlans', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
            'applicable_to' => 'required|in:all,membership,products',
            'apply_to_specific_items' => 'boolean',
            'membership_plan_ids' => 'nullable|array',
            'membership_plan_ids.*' => 'exists:membership_plans,id',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');
        $validated['apply_to_specific_items'] = $request->has('apply_to_specific_items');

        $coupon = Coupon::create($validated);

        // Attach specific membership plans if selected
        if ($validated['apply_to_specific_items'] && $request->has('membership_plan_ids')) {
            $coupon->membershipPlans()->attach($request->membership_plan_ids);
        }

        // Attach specific products if selected
        if ($validated['apply_to_specific_items'] && $request->has('product_ids')) {
            $coupon->products()->attach($request->product_ids);
        }

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully!');
    }

    public function edit(Coupon $coupon)
    {
        $membershipPlans = \App\Models\MembershipPlan::active()->orderBy('order')->get();
        $products = \App\Models\Product::active()->orderBy('name')->get();
        
        return view('admin.coupons.edit', compact('coupon', 'membershipPlans', 'products'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
            'applicable_to' => 'required|in:all,membership,products',
            'apply_to_specific_items' => 'boolean',
            'membership_plan_ids' => 'nullable|array',
            'membership_plan_ids.*' => 'exists:membership_plans,id',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');
        $validated['apply_to_specific_items'] = $request->has('apply_to_specific_items');

        $coupon->update($validated);

        // Sync specific membership plans
        if ($validated['apply_to_specific_items'] && $request->has('membership_plan_ids')) {
            $coupon->membershipPlans()->sync($request->membership_plan_ids);
        } else {
            $coupon->membershipPlans()->detach();
        }

        // Sync specific products
        if ($validated['apply_to_specific_items'] && $request->has('product_ids')) {
            $coupon->products()->sync($request->product_ids);
        } else {
            $coupon->products()->detach();
        }

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully!');
    }
}
