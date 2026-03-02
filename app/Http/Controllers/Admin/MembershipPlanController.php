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
            // Day-wise schedule validation - make it optional
            'day_wise_schedule' => 'nullable|array',
            'day_wise_schedule.*.qty' => 'nullable|numeric|min:0|max:10',
            'day_wise_schedule.*.delivery' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Process day-wise schedule if provided
        if ($request->has('day_wise_schedule')) {
            $validated['day_wise_schedule'] = $this->processDayWiseSchedule($request->day_wise_schedule);
        }

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
            // Day-wise schedule validation - make it optional
            'day_wise_schedule' => 'nullable|array',
            'day_wise_schedule.*.qty' => 'nullable|numeric|min:0|max:10',
            'day_wise_schedule.*.delivery' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Process day-wise schedule if provided
        if ($request->has('day_wise_schedule')) {
            $validated['day_wise_schedule'] = $this->processDayWiseSchedule($request->day_wise_schedule);
        }

        $plan->update($validated);

        return redirect()->route('admin.membership-plans.index')
            ->with('success', 'Membership plan updated successfully.');
    }

    /**
     * Process and validate day-wise schedule data
     */
    private function processDayWiseSchedule($schedule)
    {
        $validDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $processed = [];

        foreach ($validDays as $day) {
            // Check if this day exists in the schedule
            if (isset($schedule[$day])) {
                $data = $schedule[$day];
                $processed[$day] = [
                    'qty' => (float) ($data['qty'] ?? 0),
                    'delivery' => isset($data['delivery']) && $data['delivery'] ? true : false,
                ];
            } else {
                // If day is not in schedule, set default values
                $processed[$day] = [
                    'qty' => 0,
                    'delivery' => false,
                ];
            }
        }

        return $processed;
    }

    public function destroy($id)
    {
        $plan = MembershipPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.membership-plans.index')
            ->with('success', 'Membership plan deleted successfully.');
    }
}
