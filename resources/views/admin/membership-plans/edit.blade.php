@extends('layouts.app')

@section('title', 'Edit Membership Plan')
@section('page-title', 'Edit Membership Plan')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Edit Membership Plan</h2>
        <a href="{{ route('admin.membership-plans.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.membership-plans.update', $plan) }}">
        @csrf @method('PUT')
        <div class="space-y-5">

            {{-- Plan Type --}}
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text);">Plan Type *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="plan-type-card flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all"
                           style="border-color: var(--border);">
                        <input type="radio" name="plan_type" value="scheduled" class="plan-type-radio"
                               {{ old('plan_type', $plan->plan_type) === 'scheduled' ? 'checked' : '' }}>
                        <div>
                            <p class="font-bold text-sm" style="color: var(--text);">📅 Scheduled</p>
                            <p class="text-xs mt-0.5" style="color: var(--muted);">Daily delivery on fixed days (monthly/yearly plans)</p>
                        </div>
                    </label>
                    <label class="plan-type-card flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all"
                           style="border-color: var(--border);">
                        <input type="radio" name="plan_type" value="on_demand" class="plan-type-radio"
                               {{ old('plan_type', $plan->plan_type) === 'on_demand' ? 'checked' : '' }}>
                        <div>
                            <p class="font-bold text-sm" style="color: var(--text);">🛒 On-Demand</p>
                            <p class="text-xs mt-0.5" style="color: var(--muted);">Wallet-based pack — buy anytime, use anytime</p>
                        </div>
                    </label>
                </div>
                @error('plan_type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Name & Slug --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $plan->name) }}" required
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Slug <span class="text-xs font-normal" style="color:var(--muted);">(auto-generated)</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}"
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('slug')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Price, Duration, Order --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Price (₹) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}" required
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Duration *</label>
                    <select name="duration" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        @foreach(\App\Models\MembershipPlan::DURATIONS as $key => $d)
                        <option value="{{ $key }}" {{ old('duration', $plan->duration) === $key ? 'selected' : '' }}>
                            {{ $d['label'] }} ({{ $d['days'] }} days)
                        </option>
                        @endforeach
                    </select>
                    @error('duration')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Display Order</label>
                    <input type="number" name="order" value="{{ old('order', $plan->order) }}"
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('order')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Badge & Icon --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Badge <span class="text-xs font-normal" style="color:var(--muted);">(e.g. Most Popular)</span></label>
                    <input type="text" name="badge" value="{{ old('badge', $plan->badge) }}"
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('badge')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Icon <span class="text-xs font-normal" style="color:var(--muted);">(FontAwesome, e.g. fa-fire)</span></label>
                    <input type="text" name="icon" value="{{ old('icon', $plan->icon) }}" placeholder="fa-fire"
                           class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                    @error('icon')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">{{ old('description', $plan->description) }}</textarea>
                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Day-Wise Schedule (scheduled plans only) --}}
            <div id="schedule-section" class="border rounded-xl p-4" style="border-color: var(--border); background: #f9fdf7;">
                <h3 class="text-base font-bold mb-1" style="color: var(--green);">📅 Day-Wise Delivery Schedule</h3>
                <p class="text-xs mb-4" style="color: var(--muted);">Set milk quantity (litres) and delivery days for this plan</p>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                    @php
                        $days = ['Mon'=>'Mon','Tue'=>'Tue','Wed'=>'Wed','Thu'=>'Thu','Fri'=>'Fri','Sat'=>'Sat','Sun'=>'Sun'];
                        $schedule = old('day_wise_schedule', $plan->day_wise_schedule ?? []);
                    @endphp
                    @foreach($days as $key => $label)
                    <div class="border rounded-lg p-3 bg-white text-center" style="border-color: var(--border);">
                        <p class="font-bold text-xs mb-2" style="color: var(--text);">{{ $label }}</p>
                        <label class="flex items-center justify-center gap-1 mb-2 cursor-pointer">
                            <input type="hidden" name="day_wise_schedule[{{ $key }}][delivery]" value="0">
                            <input type="checkbox" name="day_wise_schedule[{{ $key }}][delivery]" value="1"
                                   {{ isset($schedule[$key]['delivery']) && $schedule[$key]['delivery'] ? 'checked' : '' }}
                                   class="delivery-toggle" data-day="{{ $key }}">
                            <span class="text-[10px]" style="color: var(--muted);">Deliver</span>
                        </label>
                        <input type="number" name="day_wise_schedule[{{ $key }}][qty]"
                               value="{{ $schedule[$key]['qty'] ?? 1 }}"
                               step="0.5" min="0" max="10"
                               class="w-full px-2 py-1.5 border rounded text-xs text-center qty-input"
                               style="border-color: var(--border);" id="qty-{{ $key }}">
                        <span class="text-[10px]" style="color: var(--muted);">L</span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 px-3 py-2 rounded-lg text-sm flex justify-between" style="background: rgba(47,74,30,0.08);">
                    <span style="color: var(--text);">Weekly Summary:</span>
                    <span style="color: var(--text);"><strong id="total-days">0</strong> days · <strong id="total-qty">0.0</strong> L/week</span>
                </div>
            </div>

            {{-- Features --}}
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Features</label>
                <div id="features-container" class="space-y-2">
                    @php $features = old('features', $plan->features ?? []); @endphp
                    @if($features && count($features) > 0)
                        @foreach($features as $feature)
                        <div class="flex gap-2">
                            <input type="text" name="features[]" value="{{ $feature }}"
                                   class="flex-1 px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);" placeholder="Feature">
                            <button type="button" class="px-3 py-2 rounded-lg border text-red-600 hover:bg-red-50 remove-feature text-sm" style="border-color: var(--border);">✕</button>
                        </div>
                        @endforeach
                    @else
                    <div class="flex gap-2">
                        <input type="text" name="features[]" class="flex-1 px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);" placeholder="e.g. Fresh A2 cow milk daily">
                        <button type="button" class="px-3 py-2 rounded-lg border text-red-600 hover:bg-red-50 remove-feature text-sm" style="border-color: var(--border);">✕</button>
                    </div>
                    @endif
                </div>
                <button type="button" id="add-feature" class="mt-2 px-3 py-2 rounded-lg text-sm font-medium" style="background: var(--green); color: #fff;">+ Add Feature</button>
            </div>

            {{-- Flags --}}
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $plan->is_featured) ? 'checked' : '' }}>
                    <span class="text-sm" style="color: var(--text);">Featured Plan</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                    <span class="text-sm" style="color: var(--text);">Active</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="px-5 py-2 rounded-lg text-white font-semibold" style="background: var(--green);">Update Plan</button>
            <a href="{{ route('admin.membership-plans.index') }}" class="px-5 py-2 rounded-lg border text-sm" style="border-color: var(--border);">Cancel</a>
        </div>
    </form>
</div>

<style>
.plan-type-card:has(.plan-type-radio:checked) {
    border-color: var(--green) !important;
    background: rgba(47,74,30,0.04);
}
</style>

<script>
// ── Plan type toggle ──────────────────────────────────────────
function toggleScheduleSection() {
    const isScheduled = document.querySelector('.plan-type-radio[value="scheduled"]').checked;
    document.getElementById('schedule-section').style.display = isScheduled ? '' : 'none';
}
document.querySelectorAll('.plan-type-radio').forEach(r => r.addEventListener('change', toggleScheduleSection));
toggleScheduleSection();

// ── Features ──────────────────────────────────────────────────
document.getElementById('add-feature').addEventListener('click', function() {
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `<input type="text" name="features[]" class="flex-1 px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);" placeholder="New feature">
        <button type="button" class="px-3 py-2 rounded-lg border text-red-600 hover:bg-red-50 remove-feature text-sm" style="border-color: var(--border);">✕</button>`;
    document.getElementById('features-container').appendChild(div);
});
document.addEventListener('click', e => { if (e.target.classList.contains('remove-feature')) e.target.closest('.flex').remove(); });

// ── Schedule summary ──────────────────────────────────────────
function updateScheduleSummary() {
    let days = 0, qty = 0;
    document.querySelectorAll('.delivery-toggle').forEach(cb => {
        const qtyInput = document.getElementById('qty-' + cb.dataset.day);
        if (cb.checked) { days++; qty += parseFloat(qtyInput.value) || 0; }
        qtyInput.disabled = !cb.checked;
        qtyInput.style.opacity = cb.checked ? '1' : '0.4';
    });
    document.getElementById('total-days').textContent = days;
    document.getElementById('total-qty').textContent = qty.toFixed(1);
}
updateScheduleSummary();
document.querySelectorAll('.delivery-toggle, .qty-input').forEach(el => {
    el.addEventListener('change', updateScheduleSummary);
    el.addEventListener('input', updateScheduleSummary);
});
</script>
@endsection
