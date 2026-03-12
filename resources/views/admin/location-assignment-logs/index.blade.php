@extends('layouts.app')

@section('title', 'Location Assignment Logs')
@section('page-title', 'Location Assignment Logs')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div>
                <h2 class="text-xl font-bold" style="color: var(--text);">Location Assignment History</h2>
                <p class="text-sm mt-1" style="color: var(--muted);">Track all location assignments and changes for delivery persons</p>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: rgba(47, 74, 30, 0.05);">
                    <tr>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text);">Date & Time</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text);">Action</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color: var(--text);">Delivery Person</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider hidden lg:table-cell" style="color: var(--text);">Location</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider hidden xl:table-cell" style="color: var(--text);">Assigned By</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider hidden 2xl:table-cell" style="color: var(--text);">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border);">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 lg:px-6 py-4">
                                <div class="text-sm">
                                    <p class="font-medium" style="color: var(--text);">{{ $log->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs" style="color: var(--muted);">{{ $log->created_at->format('h:i A') }}</p>
                                </div>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $log->action === 'assigned' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 hidden md:table-cell">
                                @if($log->user)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold" style="background-color: var(--green);">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium" style="color: var(--text);">{{ $log->user->name }}</p>
                                            <p class="text-xs" style="color: var(--muted);">{{ $log->user->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm" style="color: var(--muted);">User deleted</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 hidden lg:table-cell">
                                @if($log->location)
                                    <div>
                                        <p class="text-sm font-medium" style="color: var(--text);">{{ $log->location->name }}</p>
                                        <p class="text-xs" style="color: var(--muted);">{{ $log->location->area }}, {{ $log->location->city }}</p>
                                    </div>
                                @else
                                    <span class="text-sm" style="color: var(--muted);">Location deleted</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 hidden xl:table-cell">
                                @if($log->assignedBy)
                                    <span class="text-sm" style="color: var(--text);">{{ $log->assignedBy->name }}</span>
                                @else
                                    <span class="text-sm" style="color: var(--muted);">System</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 hidden 2xl:table-cell">
                                @if($log->notes)
                                    <p class="text-sm max-w-xs truncate" style="color: var(--text);" title="{{ $log->notes }}">{{ $log->notes }}</p>
                                @else
                                    <span class="text-sm" style="color: var(--muted);">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 lg:px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 mb-4" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium mb-1" style="color: var(--text);">No logs found</p>
                                    <p class="text-sm" style="color: var(--muted);">Location assignment logs will appear here</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-4 lg:px-6 py-4 border-t" style="border-color: var(--border);">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
