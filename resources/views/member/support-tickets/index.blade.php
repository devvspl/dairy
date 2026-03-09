@extends('layouts.app')

@section('title', 'My Support Tickets')
@section('page-title', 'My Support Tickets')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4 flex items-start gap-3" style="border-color: var(--green);">
        <i class="fa-solid fa-check-circle text-xl" style="color: var(--green);"></i>
        <div class="flex-1">
            <p class="font-semibold" style="color: var(--green);">Success!</p>
            <p class="text-sm" style="color: var(--text);">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">
                    <i class="fa-solid fa-ticket mr-2" style="color: var(--green);"></i>My Support Tickets
                </h1>
                <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                    View and manage your support requests
                </p>
            </div>
            <a href="{{ route('member.support-tickets.create') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-colors hover:opacity-90"
               style="background-color: var(--green); color: #fff;">
                <i class="fa-solid fa-plus mr-2"></i>Create New Ticket
            </a>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="bg-white rounded-xl shadow-sm border" style="border-color: var(--border);">
        @if($tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border); background-color: rgba(47, 74, 30, 0.02);">
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Ticket #</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Category</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Subject</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Status</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Priority</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Created</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition-colors" style="border-bottom: 1px solid var(--border);">
                            <td class="px-4 lg:px-6 py-4">
                                <span class="font-bold text-sm" style="color: var(--green);">{{ $ticket->ticket_number }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-xs" style="color: var(--text);">{{ $ticket->category_label }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm font-medium" style="color: var(--text);">{{ Str::limit($ticket->subject, 40) }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    @if($ticket->status === 'open') bg-blue-100 text-blue-800
                                    @elseif($ticket->status === 'in_progress') bg-yellow-100 text-yellow-800
                                    @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    @if($ticket->priority === 'low') bg-gray-100 text-gray-800
                                    @elseif($ticket->priority === 'medium') bg-blue-100 text-blue-800
                                    @elseif($ticket->priority === 'high') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm" style="color: var(--muted);">{{ $ticket->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <a href="{{ route('member.support-tickets.show', $ticket) }}" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors hover:bg-gray-100"
                                   style="color: var(--green); border: 1px solid var(--border);">
                                    <i class="fa-solid fa-eye mr-1"></i>View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
            <div class="px-4 lg:px-6 py-4 border-t" style="border-color: var(--border);">
                {{ $tickets->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12 px-4">
                <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <i class="fa-solid fa-ticket text-3xl" style="color: var(--green);"></i>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--text);">No Support Tickets Yet</h3>
                <p class="text-sm mb-6" style="color: var(--muted);">You haven't created any support tickets yet.</p>
                <a href="{{ route('member.support-tickets.create') }}" 
                   class="inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold transition-colors hover:opacity-90"
                   style="background-color: var(--green); color: #fff;">
                    <i class="fa-solid fa-plus mr-2"></i>Create Your First Ticket
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
