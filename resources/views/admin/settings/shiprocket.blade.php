@extends('layouts.app')

@section('title', 'Shiprocket Settings')
@section('page-title', 'Shiprocket Settings')

@section('content')
@php
    $envEmail    = config('services.shiprocket.email');
    $envPassword = config('services.shiprocket.password');
    $credOk      = $envEmail && $envPassword;
@endphp

<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Shiprocket Settings</h2>
        <a href="{{ route('admin.product-orders.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors"
           style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
        <p class="font-semibold text-sm" style="color: var(--green);">{{ session('success') }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.shiprocket.update') }}">
        @csrf

        <div class="space-y-6">

            <!-- Integration Status -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Integration Status</h3>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="enabled" id="enabled" value="1"
                           {{ $settings->enabled ? 'checked' : '' }}
                           class="w-4 h-4 rounded" style="accent-color: var(--green);">
                    <label for="enabled" class="text-sm font-medium" style="color: var(--text);">Enable Shiprocket</label>
                </div>
                <p class="text-xs mt-1" style="color: var(--muted);">When enabled, product orders can be assigned to Shiprocket for courier delivery.</p>
            </div>

            <div class="border-t" style="border-color: var(--border);"></div>

            <!-- API Credentials -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold" style="color: var(--text);">API Credentials</h3>
                    <span id="credBadge" class="text-xs px-2 py-1 rounded-full font-semibold {{ $credOk ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        {{ $credOk ? 'Configured' : 'Not set' }}
                    </span>
                </div>

                <div class="rounded-lg p-4 mb-4" style="background: rgba(47,74,30,0.05); border: 1px solid rgba(47,74,30,0.15);">
                    <p class="text-sm font-medium mb-2" style="color: var(--text);">
                        Set credentials in your <code class="font-mono text-xs px-1 py-0.5 rounded bg-gray-100">.env</code> file:
                    </p>
                    <pre class="font-mono text-xs rounded-lg p-3 bg-gray-900 text-green-400 overflow-x-auto">SHIPROCKET_EMAIL=your@email.com
SHIPROCKET_PASSWORD=yourpassword</pre>
                    <p class="text-xs mt-2" style="color: var(--muted);">
                        After editing <code class="font-mono">.env</code>, run
                        <code class="font-mono text-xs px-1 py-0.5 rounded bg-gray-100">php artisan config:clear</code>
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Email</label>
                        <input type="text" value="{{ $envEmail ?: '' }}" disabled
                               class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-50"
                               style="border-color: var(--border); color: var(--muted);"
                               placeholder="Not set in .env">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Password</label>
                        <input type="password" value="{{ $envPassword ? '••••••••••' : '' }}" disabled
                               class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-50"
                               style="border-color: var(--border); color: var(--muted);"
                               placeholder="Not set in .env">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="testConnection()" id="testBtn"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors hover:opacity-90"
                            style="background-color: var(--green); color: #fff;">
                        <i class="fa-solid fa-plug" id="testIcon"></i>
                        <span id="testBtnText">Test Connection</span>
                    </button>
                    <div id="testResult" class="hidden text-sm font-medium px-3 py-2 rounded-lg"></div>
                </div>
            </div>

            <div class="border-t" style="border-color: var(--border);"></div>

            <!-- Pickup & Default Address -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Pickup & Default Address</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Pickup Location Name</label>
                        <input type="text" name="pickup_location"
                               value="{{ old('pickup_location', $settings->pickup_location) }}"
                               placeholder="Primary"
                               class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                        <p class="text-xs mt-1" style="color: var(--muted);">Must match the pickup location name in your Shiprocket account.</p>
                        @error('pickup_location')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Default City</label>
                            <input type="text" name="default_city"
                                   value="{{ old('default_city', $settings->default_city) }}"
                                   placeholder="Mumbai"
                                   class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                            @error('default_city')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Default State</label>
                            <input type="text" name="default_state"
                                   value="{{ old('default_state', $settings->default_state) }}"
                                   placeholder="Maharashtra"
                                   class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                            @error('default_state')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Default Pincode</label>
                            <input type="text" name="default_pincode"
                                   value="{{ old('default_pincode', $settings->default_pincode) }}"
                                   placeholder="400001"
                                   class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                            @error('default_pincode')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t" style="border-color: var(--border);"></div>

            <!-- Package Dimensions -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Default Package Dimensions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Length (cm)</label>
                        <input type="number" name="pkg_length" step="0.1" min="1"
                               value="{{ old('pkg_length', $settings->pkg_length) }}"
                               class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                        @error('pkg_length')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Breadth (cm)</label>
                        <input type="number" name="pkg_breadth" step="0.1" min="1"
                               value="{{ old('pkg_breadth', $settings->pkg_breadth) }}"
                               class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                        @error('pkg_breadth')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Height (cm)</label>
                        <input type="number" name="pkg_height" step="0.1" min="1"
                               value="{{ old('pkg_height', $settings->pkg_height) }}"
                               class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                        @error('pkg_height')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Weight (kg)</label>
                        <input type="number" name="pkg_weight" step="0.1" min="0.1"
                               value="{{ old('pkg_weight', $settings->pkg_weight) }}"
                               class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                        @error('pkg_weight')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <p class="text-xs mt-2" style="color: var(--muted);">Used as defaults when creating Shiprocket shipments. Can vary per order.</p>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--border);">
                <button type="submit"
                        class="px-6 py-2 rounded-lg text-white font-medium transition-colors hover:opacity-90"
                        style="background-color: var(--green);">
                    Save Settings
                </button>
                <a href="{{ route('admin.product-orders.index') }}"
                   class="px-6 py-2 rounded-lg font-medium transition-colors hover:bg-gray-50"
                   style="color: var(--text); border: 1px solid var(--border);">
                    Cancel
                </a>
            </div>

        </div>
    </form>
</div>

<script>
function testConnection() {
    const btn    = document.getElementById('testBtn');
    const icon   = document.getElementById('testIcon');
    const text   = document.getElementById('testBtnText');
    const result = document.getElementById('testResult');

    btn.disabled     = true;
    icon.className   = 'fa-solid fa-spinner fa-spin';
    text.textContent = 'Testing...';
    result.classList.add('hidden');

    fetch('{{ route('admin.settings.shiprocket.test') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        result.classList.remove('hidden');
        result.textContent = data.message;
        result.className   = data.success
            ? 'text-sm font-medium px-3 py-2 rounded-lg bg-green-50 text-green-700 border border-green-200'
            : 'text-sm font-medium px-3 py-2 rounded-lg bg-red-50 text-red-700 border border-red-200';

        // update badge
        const badge = document.getElementById('credBadge');
        if (badge && data.success) {
            badge.textContent = 'Configured';
            badge.className   = 'text-xs px-2 py-1 rounded-full font-semibold bg-green-100 text-green-700';
        }
    })
    .catch(() => {
        result.classList.remove('hidden');
        result.textContent = 'Request failed.';
        result.className   = 'text-sm font-medium px-3 py-2 rounded-lg bg-red-50 text-red-700 border border-red-200';
    })
    .finally(() => {
        btn.disabled     = false;
        icon.className   = 'fa-solid fa-plug';
        text.textContent = 'Test Connection';
    });
}
</script>
@endsection
