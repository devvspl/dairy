<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipStep;
use Illuminate\Http\Request;

class MembershipStepController extends Controller
{
    public function index()
    {
        $steps = MembershipStep::orderBy('order')->get();
        return view('admin.membership-steps.index', compact('steps'));
    }

    public function create()
    {
        return view('admin.membership-steps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        MembershipStep::create($validated);

        return redirect()->route('admin.membership-steps.index')
            ->with('success', 'Step created successfully.');
    }

    public function edit($id)
    {
        $step = MembershipStep::findOrFail($id);
        return view('admin.membership-steps.edit', compact('step'));
    }

    public function show($id)
    {
        $step = MembershipStep::findOrFail($id);
        return view('admin.membership-steps.show', compact('step'));
    }

    public function update(Request $request, $id)
    {
        $step = MembershipStep::findOrFail($id);

        $validated = $request->validate([
            'step_number' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $step->update($validated);

        return redirect()->route('admin.membership-steps.index')
            ->with('success', 'Step updated successfully.');
    }

    public function destroy($id)
    {
        $step = MembershipStep::findOrFail($id);
        $step->delete();

        return redirect()->route('admin.membership-steps.index')
            ->with('success', 'Step deleted successfully.');
    }
}
