@extends('layouts.app')

@section('title', 'View Inquiry')
@section('page-title', 'Contact Inquiry Details')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color: var(--text);">Inquiry Details</h2>
            <p class="text-sm mt-1" style="color: var(--muted);">Submitted on {{ $contactInquiry->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
        
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.contact-inquiries.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <!-- Name -->
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-start space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium mb-2" style="color: var(--muted);">Name</p>
                    <p class="text-lg font-semibold" style="color: var(--text);">{{ $contactInquiry->name }}</p>
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
                        <a href="mailto:{{ $contactInquiry->email }}" class="text-base font-semibold hover:underline" style="color: var(--green);">{{ $contactInquiry->email }}</a>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-start space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium mb-2" style="color: var(--muted);">Phone</p>
                        <a href="tel:{{ $contactInquiry->phone }}" class="text-base font-semibold hover:underline" style="color: var(--green);">{{ $contactInquiry->phone }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject (if exists) -->
        @if($contactInquiry->subject)
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-start space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium mb-2" style="color: var(--muted);">Subject</p>
                    <p class="text-base font-semibold" style="color: var(--text);">{{ $contactInquiry->subject }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Membership Plan (if exists) -->
        @if($contactInquiry->plan)
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-start space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium mb-2" style="color: var(--muted);">Membership Plan</p>
                    <p class="text-base font-semibold" style="color: var(--text);">{{ $contactInquiry->plan->name }}</p>
                    <p class="text-sm mt-1" style="color: var(--muted);">₹{{ number_format($contactInquiry->plan->price, 0) }}/{{ $contactInquiry->plan->duration }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Message -->
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-start space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium mb-2" style="color: var(--muted);">Message</p>
                    <p class="text-base leading-relaxed" style="color: var(--text);">{{ $contactInquiry->message }}</p>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="p-4 rounded-lg border" style="border-color: var(--border);">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium mb-2" style="color: var(--muted);">Current Status</p>
                    {!! $contactInquiry->status_badge !!}
                </div>
            </div>
        </div>

        <!-- Update Status Form -->
        <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: rgba(47, 74, 30, 0.02);">
            <h3 class="text-base font-bold mb-4" style="color: var(--text);">Update Status</h3>
            
            <form method="POST" action="{{ route('admin.contact-inquiries.update-status', $contactInquiry) }}">
                @csrf
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors {{ $contactInquiry->status === 'new' ? 'border-blue-500 bg-blue-50' : '' }}" style="border-color: var(--border);">
                        <input type="radio" name="status" value="new" {{ $contactInquiry->status === 'new' ? 'checked' : '' }} class="mr-2" style="accent-color: var(--green);">
                        <span class="text-sm font-medium" style="color: var(--text);">New</span>
                    </label>

                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors {{ $contactInquiry->status === 'read' ? 'border-yellow-500 bg-yellow-50' : '' }}" style="border-color: var(--border);">
                        <input type="radio" name="status" value="read" {{ $contactInquiry->status === 'read' ? 'checked' : '' }} class="mr-2" style="accent-color: var(--green);">
                        <span class="text-sm font-medium" style="color: var(--text);">Read</span>
                    </label>

                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors {{ $contactInquiry->status === 'replied' ? 'border-green-500 bg-green-50' : '' }}" style="border-color: var(--border);">
                        <input type="radio" name="status" value="replied" {{ $contactInquiry->status === 'replied' ? 'checked' : '' }} class="mr-2" style="accent-color: var(--green);">
                        <span class="text-sm font-medium" style="color: var(--text);">Replied</span>
                    </label>

                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors {{ $contactInquiry->status === 'closed' ? 'border-gray-500 bg-gray-50' : '' }}" style="border-color: var(--border);">
                        <input type="radio" name="status" value="closed" {{ $contactInquiry->status === 'closed' ? 'checked' : '' }} class="mr-2" style="accent-color: var(--green);">
                        <span class="text-sm font-medium" style="color: var(--text);">Closed</span>
                    </label>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2" style="color: var(--text);">Admin Notes (Internal)</label>
                    <textarea name="admin_notes" rows="3" class="w-full px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);" placeholder="Add internal notes about this inquiry...">{{ old('admin_notes', $contactInquiry->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="px-6 py-2 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                    Update Status & Notes
                </button>
            </form>
        </div>

        <!-- Admin Notes Display (if exists) -->
        @if($contactInquiry->admin_notes)
        <div class="p-4 rounded-lg border" style="border-color: var(--border); background-color: rgba(47, 74, 30, 0.02);">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium mb-2" style="color: var(--muted);">Admin Notes</p>
                    <p class="text-sm leading-relaxed" style="color: var(--text);">{{ $contactInquiry->admin_notes }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Timestamps -->
    <div class="mt-6 pt-6 border-t grid grid-cols-1 md:grid-cols-2 gap-4" style="border-color: var(--border);">
        <div class="p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Submitted At</p>
            <p class="text-sm" style="color: var(--text);">{{ $contactInquiry->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <div class="p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
            <p class="text-sm font-medium mb-1" style="color: var(--muted);">Last Updated</p>
            <p class="text-sm" style="color: var(--text);">{{ $contactInquiry->updated_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    <!-- Delete Button -->
    <div class="mt-6 pt-6 border-t" style="border-color: var(--border);">
        <form method="POST" action="{{ route('admin.contact-inquiries.destroy', $contactInquiry) }}" onsubmit="return confirm('Are you sure you want to delete this inquiry? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">Delete Inquiry</button>
        </form>
    </div>
</div>
@endsection
