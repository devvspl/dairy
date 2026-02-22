<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::orderBy('order')->get();
        return view('admin.membership-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.membership-plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:membership_plans',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string',
            'badge' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        MembershipPlan::create($validated);

        return redirect()->route('admin.membership-plans.index')
            ->with('success', 'Membership plan created successfully.');
    }

    public function edit($id)
    {
        $plan = MembershipPlan::findOrFail($id);
        return view('admin.membership-plans.edit', compact('plan'));
    }

    public function show($id)
    {
        $plan = MembershipPlan::findOrFail($id);
        return view('admin.membership-plans.show', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = MembershipPlan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:membership_plans,slug,' . $id,
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string',
            'badge' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'order' => 'nullable|integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $plan->update($validated);

        return redirect()->route('admin.membership-plans.index')
            ->with('success', 'Membership plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = MembershipPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.membership-plans.index')
            ->with('success', 'Membership plan deleted successfully.');
    }
}
