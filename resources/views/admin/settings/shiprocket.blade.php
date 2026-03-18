@extends('layouts.app')

@section('title', 'Shiprocket Settings')
@section('page-title', 'Shiprocket Settings')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold" style="color: var(--text);">Shiprocket Integration</h2>
            <a href="{{ route('admin.product-orders.index') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors"
                style="color: var(--text); border: 1px solid var(--border);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 rounded-lg p-4" style="border-color: var(--green);">
                <p class="font-semibold" style="color: var(--green);">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.shiprocket.update') }}">
            @csrf
            <div class="space-y-6">

                <!-- Integration Status -->
                <div>
                    <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Integration Status</h3>
                    <div class="flex items-center">
                        <input type="checkbox" name="enabled" id="enabled" value="1"
                            {{ $settings->enabled ? 'checked' : '' }} class="w-4 h-4 rounded"
                            style="accent-color: var(--green);">
                        <label for="enabled" class="ml-2 text-sm font-medium" style="color: var(--text);">
                            Enable Shiprocket
                        </label>
                    </div>
                    <p class="text-xs mt-1" style="color: var(--muted);">
                        When enabled, product orders can be assigned to Shiprocket for courier delivery.
                    </p>
                </div>

                <!-- API Credentials -->
                <div>
                    <h3 class="text-lg font-bold mb-4" style="color: var(--text);">API Credentials</h3>

                    <div class="flex flex-wrap items-end gap-4">

                        <!-- Email -->
                        <div class="flex-1 min-w-[250px]">
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $settings->email) }}"
                                placeholder="your@shiprocket.com" class="w-full px-3 py-2 border rounded-lg"
                                style="border-color: var(--border);">
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="flex-1 min-w-[250px]">
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep existing"
                                class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        </div>

                        <!-- Button -->
                        <div class="flex items-end">
                            <button type="button" onclick="testConnection()" id="testBtn"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors"
                                style="border: 1px solid var(--border); color: var(--text);">
                                <i class="fa-solid fa-plug" id="testIcon"></i>
                                <span id="testBtnText">Test Connection</span>
                            </button>
                            <span id="testResult" class="ml-3 text-sm font-medium hidden"></span>
                        </div>

                    </div>
                </div>

                <!-- Pickup & Default Address -->
                <div>
                    <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Pickup & Default Address</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Pickup Location
                                Name</label>
                            <input type="text" name="pickup_location"
                                value="{{ old('pickup_location', $settings->pickup_location) }}" placeholder="Primary"
                                class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <p class="text-xs mt-1" style="color: var(--muted);">Must match the pickup location name in your
                                Shiprocket account.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Default
                                    City</label>
                                <input type="text" name="default_city"
                                    value="{{ old('default_city', $settings->default_city) }}" placeholder="Mumbai"
                                    class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Default
                                    State</label>
                                <input type="text" name="default_state"
                                    value="{{ old('default_state', $settings->default_state) }}" placeholder="Maharashtra"
                                    class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text);">Default
                                    Pincode</label>
                                <input type="text" name="default_pincode"
                                    value="{{ old('default_pincode', $settings->default_pincode) }}" placeholder="400001"
                                    class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Dimensions -->
                <div>
                    <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Default Package Dimensions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Length (cm)</label>
                            <input type="number" name="pkg_length" step="0.1" min="1"
                                value="{{ old('pkg_length', $settings->pkg_length) }}"
                                class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Breadth (cm)</label>
                            <input type="number" name="pkg_breadth" step="0.1" min="1"
                                value="{{ old('pkg_breadth', $settings->pkg_breadth) }}"
                                class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Height (cm)</label>
                            <input type="number" name="pkg_height" step="0.1" min="1"
                                value="{{ old('pkg_height', $settings->pkg_height) }}"
                                class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Weight (kg)</label>
                            <input type="number" name="pkg_weight" step="0.1" min="0.1"
                                value="{{ old('pkg_weight', $settings->pkg_weight) }}"
                                class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        </div>
                    </div>
                    <p class="text-xs mt-2" style="color: var(--muted);">Used as default dimensions when creating
                        shipments.</p>
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
            const btn = document.getElementById('testBtn');
            const icon = document.getElementById('testIcon');
            const text = document.getElementById('testBtnText');
            const result = document.getElementById('testResult');

            btn.disabled = true;
            icon.className = 'fa-solid fa-spinner fa-spin';
            text.textContent = 'Testing...';
            result.classList.add('hidden');

            fetch('{{ route('admin.settings.shiprocket.test') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(r => r.json())
                .then(data => {
                    result.classList.remove('hidden');
                    result.textContent = data.message;
                    result.style.color = data.success ? '#16a34a' : '#dc2626';
                })
                .catch(() => {
                    result.classList.remove('hidden');
                    result.textContent = 'Request failed.';
                    result.style.color = '#dc2626';
                })
                .finally(() => {
                    btn.disabled = false;
                    icon.className = 'fa-solid fa-plug';
                    text.textContent = 'Test Connection';
                });
        }
    </script>
@endsection
