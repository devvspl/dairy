@extends('layouts.app')

@section('title', 'Referral Codes')
@section('page-title', 'Referral Codes')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Total Codes</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['total_codes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Active Codes</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['active_codes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(59, 130, 246, 0.1);">
                <svg class="w-6 h-6" style="color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Total Referrals</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['total_referrals'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(139, 92, 246, 0.1);">
                <svg class="w-6 h-6" style="color: #8b5cf6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Total Earnings</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">₹{{ number_format($stats['total_earnings'], 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(245, 158, 11, 0.1);">
                <svg class="w-6 h-6" style="color: #f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
            <h2 class="text-xl font-bold" style="color: var(--text);">Referral Codes</h2>
            <div class="flex flex-col sm:flex-row gap-3">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search codes or users..." class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                    <select name="status" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--green);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
                <a href="{{ route('admin.referral-codes.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--green);">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Referral Code
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
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Referrals</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Earnings</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Created</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="divide-color: var(--border);">
                @forelse($referralCodes as $code)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div>
                            <div class="text-sm font-medium" style="color: var(--text);">{{ $code->user->name }}</div>
                            <div class="text-xs" style="color: var(--muted);">{{ $code->user->email }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100" style="color: var(--text);">
                            {{ $code->code }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text);">{{ $code->total_referrals }}</td>
                    <td class="px-4 py-3 text-sm font-medium" style="color: var(--green);">₹{{ number_format($code->total_earnings, 2) }}</td>
                    <td class="px-4 py-3">
                        @if($code->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm" style="color: var(--muted);">{{ $code->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right text-sm font-medium">
                        <a href="{{ route('admin.referral-codes.edit', $code) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="{{ route('admin.referral-codes.destroy', $code) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-sm" style="color: var(--muted);">
                        No referral codes found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($referralCodes->hasPages())
    <div class="px-4 py-3 border-t" style="border-color: var(--border);">
        {{ $referralCodes->links() }}
    </div>
    @endif
</div>
@endsection
