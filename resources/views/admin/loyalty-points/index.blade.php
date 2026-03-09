@extends('layouts.app')

@section('title', 'Loyalty Points')
@section('page-title', 'Loyalty Points')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Total Earned</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ number_format($stats['total_earned']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Total Redeemed</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ number_format($stats['total_redeemed']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(239, 68, 68, 0.1);">
                <svg class="w-6 h-6" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Active Points</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ number_format($stats['active_points']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(59, 130, 246, 0.1);">
                <svg class="w-6 h-6" style="color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Total Users</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['total_users'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(139, 92, 246, 0.1);">
                <svg class="w-6 h-6" style="color: #8b5cf6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="bg-white rounded-xl shadow-sm border" style="border-color: var(--border);">
    <!-- Header -->
    <div class="p-4 lg:p-6 border-b" style="border-color: var(--border);">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <h2 class="text-xl font-bold" style="color: var(--text);">Loyalty Points History</h2>
            <div class="flex flex-col sm:flex-row gap-3">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                    <select name="type" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                        <option value="">All Types</option>
                        <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>Earned</option>
                        <option value="redeemed" {{ request('type') === 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                        <option value="expired" {{ request('type') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="adjusted" {{ request('type') === 'adjusted' ? 'selected' : '' }}>Adjusted</option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--green);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
                <a href="{{ route('admin.loyalty-points.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--green);">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Points
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b" style="border-color: var(--border); background-color: #f9fafb;">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Points</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Reason</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Expires</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="divide-color: var(--border);">
                @forelse($loyaltyPoints as $point)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div>
                            <div class="text-sm font-medium" style="color: var(--text);">{{ $point->user->name }}</div>
                            <div class="text-xs" style="color: var(--muted);">{{ $point->user->email }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm font-bold {{ $point->type === 'earned' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $point->type === 'earned' ? '+' : '-' }}{{ $point->points }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($point->type === 'earned')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Earned
                        </span>
                        @elseif($point->type === 'redeemed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Redeemed
                        </span>
                        @elseif($point->type === 'expired')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Expired
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Adjusted
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm" style="color: var(--text);">{{ $point->reason }}</div>
                        @if($point->description)
                        <div class="text-xs" style="color: var(--muted);">{{ Str::limit($point->description, 50) }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                        {{ $point->expires_at ? $point->expires_at->format('M d, Y') : 'Never' }}
                    </td>
                    <td class="px-4 py-3 text-sm" style="color: var(--muted);">{{ $point->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right text-sm font-medium">
                        <a href="{{ route('admin.loyalty-points.edit', $point) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="{{ route('admin.loyalty-points.destroy', $point) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-sm" style="color: var(--muted);">
                        No loyalty points found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($loyaltyPoints->hasPages())
    <div class="px-4 py-3 border-t" style="border-color: var(--border);">
        {{ $loyaltyPoints->links() }}
    </div>
    @endif
</div>
@endsection
