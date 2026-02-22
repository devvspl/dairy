@extends('layouts.app')

@section('title', 'Contact Inquiries')
@section('page-title', 'Contact Inquiries')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="text-sm font-medium" style="color: var(--muted);">Total</div>
            <div class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="text-sm font-medium" style="color: var(--muted);">New</div>
            <div class="text-2xl font-bold mt-1" style="color: #1e40af;">{{ $stats['new'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="text-sm font-medium" style="color: var(--muted);">Read</div>
            <div class="text-2xl font-bold mt-1" style="color: #92400e;">{{ $stats['read'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="text-sm font-medium" style="color: var(--muted);">Replied</div>
            <div class="text-2xl font-bold mt-1" style="color: #065f46;">{{ $stats['replied'] }}</div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="text-sm font-medium" style="color: var(--muted);">Closed</div>
            <div class="text-2xl font-bold mt-1" style="color: #4b5563;">{{ $stats['closed'] }}</div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <form method="GET" action="{{ route('admin.contact-inquiries.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone..." class="flex-1 min-w-[200px] px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
            
            <select name="status" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
                <option value="">All Status</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
            
            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To Date" class="px-3 py-2 border rounded-lg text-sm" style="border-color: var(--border);">
            
            <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm font-medium" style="background-color: var(--green);">
                Filter
            </button>
            
            @if(request('search') || request('status') || request('date_from') || request('date_to'))
            <a href="{{ route('admin.contact-inquiries.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium" style="color: var(--text); border: 1px solid var(--border);">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Inquiries Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #f9fafb; border-bottom: 1px solid var(--border);">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Sr.No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Subject</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color: var(--muted);">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color: var(--border);">
                    @forelse($inquiries as $inquiry)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium" style="color: var(--text);">{{ $inquiries->firstItem() + $loop->index }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-sm" style="color: var(--text);">{{ $inquiry->name }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm" style="color: var(--text);">{{ $inquiry->email }}</div>
                            <div class="text-xs" style="color: var(--muted);">{{ $inquiry->phone }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm" style="color: var(--text);">{{ $inquiry->subject ?: '—' }}</div>
                            @if($inquiry->plan)
                            <div class="text-xs mt-1" style="color: var(--muted);">Plan: {{ $inquiry->plan->name }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            {!! $inquiry->status_badge !!}
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm" style="color: var(--text);">{{ $inquiry->created_at->format('M d, Y') }}</div>
                            <div class="text-xs" style="color: var(--muted);">{{ $inquiry->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.contact-inquiries.show', $inquiry) }}" class="p-2 rounded-lg hover:bg-gray-100" style="color: var(--green);" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.contact-inquiries.destroy', $inquiry) }}" onsubmit="return confirm('Are you sure you want to delete this inquiry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg hover:bg-red-50 text-red-600" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center" style="color: var(--muted);">
                            No inquiries found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inquiries->hasPages())
        <div class="px-4 py-3 border-t" style="border-color: var(--border);">
            {{ $inquiries->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
