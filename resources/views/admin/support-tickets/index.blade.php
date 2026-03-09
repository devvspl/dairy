@extends('layouts.app')

@section('title', 'Support Tickets')
@section('page-title', 'Support Tickets')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="text-sm font-medium" style="color: var(--muted);">Total</div>
            <div class="text-2xl font-bold mt-1" style="color: var(--text);">{{ \App\Models\SupportTicket::count() }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="text-sm font-medium" style="color: var(--muted);">Open</div>
            <div class="text-2xl font-bold mt-1" style="color: #1e40af;">{{ \App\Models\SupportTicket::where('status', 'open')->count() }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="text-sm font-medium" style="color: var(--muted);">In Progress</div>
            <div class="text-2xl font-bold mt-1" style="color: #92400e;">{{ \App\Models\SupportTicket::where('status', 'in_progress')->count() }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="text-sm font-medium" style="color: var(--muted);">Resolved</div>
            <div class="text-2xl font-bold mt-1" style="color: #065f46;">{{ \App\Models\SupportTicket::where('status', 'resolved')->count() }}</div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" action="{{ route('admin.support-tickets.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by ticket #, subject, customer..." class="flex-1 min-w-[200px] px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
            
            <select name="status" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                <option value="">All Status</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>

            <select name="priority" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                <option value="">All Priority</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
            
            <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--green);">
                Filter
            </button>
            
            @if(request('search') || request('status') || request('priority'))
            <a href="{{ route('admin.support-tickets.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium" style="color: var(--text); border: 1px solid var(--border);">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #f9fafb; border-bottom: 1px solid var(--border);">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Ticket #</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Subject</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Priority</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color: var(--border);">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium" style="color: var(--text);">{{ $ticket->ticket_number }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-sm" style="color: var(--text);">{{ $ticket->user->name }}</div>
                            <div class="text-xs" style="color: var(--muted);">{{ $ticket->user->email }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm" style="color: var(--text);">{{ Str::limit($ticket->subject, 40) }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $ticket->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $ticket->priority === 'medium' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $ticket->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm" style="color: var(--text);">{{ $ticket->created_at->format('M d, Y') }}</div>
                            <div class="text-xs" style="color: var(--muted);">{{ $ticket->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.support-tickets.show', $ticket) }}" class="p-2 rounded-lg hover:bg-gray-100" style="color: var(--green);" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center" style="color: var(--muted);">
                            No support tickets found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
        <div class="px-4 py-3 border-t" style="border-color: var(--border);">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
