<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipBenefit;
use Illuminate\Http\Request;

class MembershipBenefitController extends Controller
{
    public function index()
    {
        $benefits = MembershipBenefit::orderBy('order')->get();
        return view('admin.membership-benefits.index', compact('benefits'));
    }

    public function create()
    {
        return view('admin.membership-benefits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        MembershipBenefit::create($validated);

        return redirect()->route('admin.membership-benefits.index')
            ->with('success', 'Benefit created successfully.');
    }

    public function edit($id)
    {
        $benefit = MembershipBenefit::findOrFail($id);
        return view('admin.membership-benefits.edit', compact('benefit'));
    }

    public function show($id)
    {
        $benefit = MembershipBenefit::findOrFail($id);
        return view('admin.membership-benefits.show', compact('benefit'));
    }

    public function update(Request $request, $id)
    {
        $benefit = MembershipBenefit::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $benefit->update($validated);

        return redirect()->route('admin.membership-benefits.index')
            ->with('success', 'Benefit updated successfully.');
    }

    public function destroy($id)
    {
        $benefit = MembershipBenefit::findOrFail($id);
        $benefit->delete();

        return redirect()->route('admin.membership-benefits.index')
            ->with('success', 'Benefit deleted successfully.');
    }
}
