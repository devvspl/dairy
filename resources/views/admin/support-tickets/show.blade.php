@extends('layouts.app')

@section('title', 'View Support Ticket')
@section('page-title', 'Support Ticket Details')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color: var(--text);">Ticket #{{ $supportTicket->ticket_number }}</h2>
            <p class="text-sm mt-1" style="color: var(--muted);">Submitted on {{ $supportTicket->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
        
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.support-tickets.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <!-- Customer Information -->
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-start space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium mb-2" style="color: var(--muted);">Customer Name</p>
                    <p class="text-lg font-semibold" style="color: var(--text);">{{ $supportTicket->user->name }}</p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-start space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium mb-2" style="color: var(--muted);">Email</p>
                        <a href="mailto:{{ $supportTicket->user->email }}" class="text-base font-semibold hover:underline" style="color: var(--green);">{{ $supportTicket->user->email }}</a>
                    </div>
                </div>
            </div>

            @if($supportTicket->user->phone)
            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-start space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium mb-2" style="color: var(--muted);">Phone</p>
                        <a href="tel:{{ $supportTicket->user->phone }}" class="text-base font-semibold hover:underline" style="color: var(--green);">{{ $supportTicket->user->phone }}</a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Category & Subject -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($supportTicket->category)
            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-start space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium mb-2" style="color: var(--muted);">Category</p>
                        <p class="text-base font-semibold" style="color: var(--text);">{{ $supportTicket->category_label }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Subject -->
            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-start space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium mb-2" style="color: var(--muted);">Subject</p>
                        <p class="text-base font-semibold" style="color: var(--text);">{{ $supportTicket->subject }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message -->
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-start space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium mb-2" style="color: var(--muted);">Customer Message</p>
                    <p class="text-base leading-relaxed" style="color: var(--text);">{{ $supportTicket->message }}</p>
                </div>
            </div>
        </div>

        <!-- Current Status & Priority -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium mb-2" style="color: var(--muted);">Current Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium mb-2" style="color: var(--muted);">Priority</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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

        <!-- Admin Reply (if exists) -->
        @if($supportTicket->admin_reply)
        <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: rgba(47, 74, 30, 0.02);">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium mb-2" style="color: var(--muted);">Admin Reply</p>
                    <p class="text-sm leading-relaxed" style="color: var(--text);">{{ $supportTicket->admin_reply }}</p>
                    @if($supportTicket->replied_at)
                    <p class="text-xs mt-2" style="color: var(--muted);">Replied on {{ $supportTicket->replied_at->format('M d, Y h:i A') }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Update Ticket Form -->
        <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: rgba(47, 74, 30, 0.02);">
            <h3 class="text-base font-bold mb-4" style="color: var(--text);">Update Ticket</h3>
            
            <form method="POST" action="{{ route('admin.support-tickets.update', $supportTicket) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Admin Reply</label>
                    <textarea name="admin_reply" rows="4" class="w-full px-3 py-2 border rounded-lg text-sm @error('admin_reply') border-red-500 @enderror" style="border-color: var(--border);" placeholder="Type your reply to the customer...">{{ old('admin_reply', $supportTicket->admin_reply) }}</textarea>
                    @error('admin_reply')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Status</label>
                        <select name="status" required class="w-full px-3 py-2 border rounded-lg text-sm @error('status') border-red-500 @enderror" style="border-color: var(--border);">
                            <option value="open" {{ $supportTicket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $supportTicket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $supportTicket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $supportTicket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2" style="color: var(--text);">Priority</label>
                        <select name="priority" required class="w-full px-3 py-2 border rounded-lg text-sm @error('priority') border-red-500 @enderror" style="border-color: var(--border);">
                            <option value="low" {{ $supportTicket->priority === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $supportTicket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $supportTicket->priority === 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $supportTicket->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="px-6 py-2 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Update Ticket
                </button>
            </form>
        </div>
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
@endsection
