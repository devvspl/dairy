<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MembershipPlan;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    /**
     * Subscribe user to a membership plan
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:membership_plans,id',
            'payment_method' => 'required|in:online,cod,bank_transfer',
            'delivery_address' => 'required|string|max:500',
        ]);

        $plan = MembershipPlan::findOrFail($validated['plan_id']);
        $user = auth()->user();

        // Check if user already has an active subscription
        $existingSubscription = $user->activeSubscription()->first();
        if ($existingSubscription) {
            return redirect()->route('member.dashboard')
                ->with('error', 'You already have an active subscription. Please cancel or wait for it to expire before subscribing to a new plan.');
        }

        try {
            DB::beginTransaction();

            // Calculate subscription dates based on plan duration
            $startDate = now();
            $endDate = $this->calculateEndDate($startDate, $plan->duration);

            // Create subscription record
            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'membership_plan_id' => $plan->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $validated['payment_method'] === 'online' ? 'pending' : 'active',
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'online' ? 'pending' : 'pending',
                'delivery_address' => $validated['delivery_address'],
                'amount_paid' => $validated['payment_method'] !== 'online' ? $plan->price : null,
            ]);

            // Log the subscription
            Log::info('Membership subscription created', [
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'payment_method' => $validated['payment_method'],
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ]);

            DB::commit();

            // Different messages based on payment method
            $message = match($validated['payment_method']) {
                'online' => "Subscription created! Please complete the payment to activate your '{$plan->name}' plan.",
                'cod' => "Subscription activated! Your '{$plan->name}' plan is now active. Payment will be collected on delivery.",
                'bank_transfer' => "Subscription created! Please complete the bank transfer to activate your '{$plan->name}' plan. Our team will contact you with bank details.",
                default => "Subscription request received for '{$plan->name}'!"
            };

            return redirect()->route('member.dashboard')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription creation failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'plan_id' => $plan->id,
            ]);

            return redirect()->route('member.dashboard')
                ->with('error', 'Failed to create subscription. Please try again or contact support.');
        }
    }

    /**
     * Calculate end date based on plan duration
     */
    private function calculateEndDate($startDate, $duration)
    {
        // Parse duration string (e.g., "month", "3 months", "year")
        $duration = strtolower(trim($duration));
        
        if (str_contains($duration, 'month')) {
            preg_match('/(\d+)/', $duration, $matches);
            $months = isset($matches[1]) ? (int)$matches[1] : 1;
            return $startDate->copy()->addMonths($months);
        }
        
        if (str_contains($duration, 'year')) {
            preg_match('/(\d+)/', $duration, $matches);
            $years = isset($matches[1]) ? (int)$matches[1] : 1;
            return $startDate->copy()->addYears($years);
        }
        
        if (str_contains($duration, 'week')) {
            preg_match('/(\d+)/', $duration, $matches);
            $weeks = isset($matches[1]) ? (int)$matches[1] : 1;
            return $startDate->copy()->addWeeks($weeks);
        }
        
        if (str_contains($duration, 'day')) {
            preg_match('/(\d+)/', $duration, $matches);
            $days = isset($matches[1]) ? (int)$matches[1] : 1;
            return $startDate->copy()->addDays($days);
        }
        
        // Default to 1 month if duration format is not recognized
        return $startDate->copy()->addMonth();
    }
}

