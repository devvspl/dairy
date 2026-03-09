@extends('layouts.app')

@section('title', 'Create Referral Code')
@section('page-title', 'Create Referral Code')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Create Referral Code</h2>
        <a href="{{ route('admin.referral-codes.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.referral-codes.store') }}">
        @csrf
        
        <div class="space-y-6">
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Basic Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Select User *</label>
                        <select name="user_id" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                            <option value="">Choose a member...</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        <p class="text-xs mt-1" style="color: var(--muted);">Select the member who will own this referral code</p>
                        @error('user_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Referral Code</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="w-full px-3 py-2 border rounded-lg uppercase" style="border-color: var(--border);" placeholder="Leave empty to auto-generate">
                        <p class="text-xs mt-1" style="color: var(--muted);">Leave empty to auto-generate a unique code</p>
                        @error('code')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div>
                <h3 class="text-lg font-bold mb-4" style="color: var(--text);">Status</h3>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded" style="accent-color: var(--green);">
                    <label for="is_active" class="ml-2 text-sm font-medium" style="color: var(--text);">Active</label>
                </div>
                <p class="text-xs mt-1" style="color: var(--muted);">Inactive codes cannot be used for referrals</p>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--border);">
                <button type="submit" class="px-6 py-2 rounded-lg text-white font-medium transition-colors hover:opacity-90" style="background-color: var(--green);">
                    Create Referral Code
                </button>
                <a href="{{ route('admin.referral-codes.index') }}" class="px-6 py-2 rounded-lg font-medium transition-colors hover:bg-gray-50" style="color: var(--text); border: 1px solid var(--border);">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
