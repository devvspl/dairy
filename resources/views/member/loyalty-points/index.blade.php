@extends('layouts.app')

@section('title', 'My Loyalty Points')
@section('page-title', 'My Loyalty Points')

@section('content')
<!-- Points Balance Card -->
<div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-lg font-medium mb-1">Available Loyalty Points</h2>
            <p class="text-5xl font-bold">{{ number_format($stats['available_points']) }}</p>
            <p class="text-purple-100 mt-2">Keep earning points with every purchase!</p>
        </div>
        <div class="flex flex-col gap-2">
            <div class="bg-white/20 px-4 py-2 rounded-lg">
                <div class="text-sm">Total Earned</div>
                <div class="text-2xl font-bold">{{ number_format($stats['total_earned']) }}</div>
            </div>
            <div class="bg-white/20 px-4 py-2 rounded-lg">
                <div class="text-sm">Total Redeemed</div>
                <div class="text-2xl font-bold">{{ number_format($stats['total_redeemed']) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- How it Works -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6 border" style="border-color: var(--border);">
    <h3 class="text-lg font-bold mb-4" style="color: var(--text);">How Loyalty Points Work</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h4 class="font-semibold mb-1" style="color: var(--text);">Earn Points</h4>
                <p class="text-sm" style="color: var(--muted);">Get points with every purchase and subscription</p>
            </div>
        </div>
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                </svg>
            </div>
            <div>
                <h4 class="font-semibold mb-1" style="color: var(--text);">Redeem Rewards</h4>
                <p class="text-sm" style="color: var(--muted);">Use points for discounts on future orders</p>
            </div>
        </div>
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <h4 class="font-semibold mb-1" style="color: var(--text);">Bonus Points</h4>
                <p class="text-sm" style="color: var(--muted);">Get extra points on special occasions</p>
            </div>
        </div>
    </div>
</div>

<!-- Points History -->
<div class="bg-white rounded-xl shadow-sm border" style="border-color: var(--border);">
    <div class="p-4 lg:p-6 border-b" style="border-color: var(--border);">
        <h2 class="text-xl font-bold" style="color: var(--text);">Points History</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b" style="border-color: var(--border); background-color: #f9fafb;">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Points</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Reason</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Expires</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="divide-color: var(--border);">
                @forelse($loyaltyPoints as $point)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm" style="color: var(--muted);">{{ $point->created_at->format('M d, Y') }}</td>
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
                        <div class="text-xs" style="color: var(--muted);">{{ $point->description }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm" style="color: var(--muted);">
                        {{ $point->expires_at ? $point->expires_at->format('M d, Y') : 'Never' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-sm" style="color: var(--muted);">
                        No loyalty points history yet. Start earning points with your first purchase!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($loyaltyPoints->hasPages())
    <div class="px-4 py-3 border-t" style="border-color: var(--border);">
        {{ $loyaltyPoints->links() }}
    </div>
    @endif
</div>
@endsection
