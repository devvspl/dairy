@extends('layouts.app')

@section('title', 'Ticket Details')
@section('page-title', 'Support Ticket Details')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('member.support-tickets.index') }}" 
           class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" 
           style="color: var(--text); border: 1px solid var(--border);">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Tickets
        </a>
    </div>

    <!-- Ticket Details -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="mb-6">
            <div class="flex items-start justify-between mb-2">
                <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">{{ $supportTicket->subject }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: rgba(47, 74, 30, 0.1); color: var(--green);">
                    {{ $supportTicket->ticket_number }}
                </span>
            </div>
            <p class="text-sm" style="color: var(--muted);">Created on {{ $supportTicket->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Category -->
            @if($supportTicket->category)
            <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: rgba(47, 74, 30, 0.02);">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium mb-1" style="color: var(--muted);">Issue Category</p>
                        <p class="text-base font-semibold" style="color: var(--text);">{{ $supportTicket->category_label }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Status & Priority -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                            <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium mb-1" style="color: var(--muted);">Status</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                @if($supportTicket->status === 'open') bg-blue-100 text-blue-800
                                @elseif($supportTicket->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($supportTicket->status === 'resolved') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                            <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium mb-1" style="color: var(--muted);">Priority</p>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                @if($supportTicket->priority === 'low') bg-gray-100 text-gray-800
                                @elseif($supportTicket->priority === 'medium') bg-blue-100 text-blue-800
                                @elseif($supportTicket->priority === 'high') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($supportTicket->priority) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Your Message -->
            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium mb-2" style="color: var(--muted);">Your Message</p>
                        <p class="text-base leading-relaxed" style="color: var(--text);">{{ $supportTicket->message }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Reply -->
            @if($supportTicket->admin_reply)
            <div class="p-4 rounded-lg border-2" style="border-color: var(--green); background-color: rgba(47, 74, 30, 0.02);">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: var(--green);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-bold" style="color: var(--green);">Admin Reply</p>
                            <p class="text-xs" style="color: var(--muted);">{{ $supportTicket->replied_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <p class="text-base leading-relaxed" style="color: var(--text);">{{ $supportTicket->admin_reply }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="p-4 rounded-lg border-2 border-dashed" style="border-color: #fbbf24; background-color: #fef3c7;">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: #fbbf24;">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-yellow-800">Waiting for Admin Response</p>
                        <p class="text-xs text-yellow-700">Our support team will respond to your ticket soon</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Timestamps -->
        <div class="mt-6 pt-6 border-t grid grid-cols-1 md:grid-cols-2 gap-4" style="border-color: var(--border);">
            <div class="p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Created At</p>
                <p class="text-sm" style="color: var(--text);">{{ $supportTicket->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Last Updated</p>
                <p class="text-sm" style="color: var(--text);">{{ $supportTicket->updated_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
