@extends('layouts.app')

@section('title', 'Add Loyalty Points')
@section('page-title', 'Add Loyalty Points')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold" style="color: var(--text);">Add Loyalty Points</h2>
        <a href="{{ route('admin.loyalty-points.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.loyalty-points.store') }}">
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
                        @error('user_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Points *</label>
                            <input type="number" name="points" value="{{ old('points') }}" required min="1" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="100">
                            @error('points')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text);">Type *</label>
                            <select name="type" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                                <option value="earned" {{ old('type') == 'earned' ? 'selected' : '' }}>Earned</option>
                                <option value="redeemed" {{ old('type') == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                                <option value="expired" {{ old('type') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="adjusted" {{ old('type') == 'adjusted' ? 'selected' : '' }}>Adjusted</option>
                            </select>
                            @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Reason *</label>
                        <input type="text" name="reason" value="{{ old('reason') }}" required class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="e.g., Welcome Bonus, Order Purchase">
                        @error('reason')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Description</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);" placeholder="Additional details...">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Expiry Date</label>
                        <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="w-full px-3 py-2 border rounded-lg" style="border-color: var(--border);">
                        <p class="text-xs mt-1" style="color: var(--muted);">Leave empty for points that never expire</p>
                        @error('expires_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--border);">
                <button type="submit" class="px-6 py-2 rounded-lg text-white font-medium transition-colors hover:opacity-90" style="background-color: var(--green);">
                    Add Points
                </button>
                <a href="{{ route('admin.loyalty-points.index') }}" class="px-6 py-2 rounded-lg font-medium transition-colors hover:bg-gray-50" style="color: var(--text); border: 1px solid var(--border);">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
