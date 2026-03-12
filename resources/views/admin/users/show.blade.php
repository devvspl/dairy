@extends('layouts.app')

@section('title', 'View User')
@section('page-title', 'User Details')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold" style="background-color: var(--green);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold" style="color: var(--text);">{{ $user->name }}</h2>
                    <p class="text-sm" style="color: var(--muted);">{{ $user->email }}</p>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" style="color: var(--text); border: 1px solid var(--border);">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium" style="color: var(--muted);">Full Name</p>
                        <p class="text-sm font-semibold truncate" style="color: var(--text);">{{ $user->name }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium" style="color: var(--muted);">Email Address</p>
                        <p class="text-sm font-semibold truncate" style="color: var(--text);">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium" style="color: var(--muted);">Phone Number</p>
                        <p class="text-sm font-semibold truncate" style="color: var(--text);">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium" style="color: var(--muted);">User Type</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            @if($user->user_type === 'Admin') bg-purple-100 text-purple-800
                            @elseif($user->user_type === 'Delivery Person') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $user->user_type ?? 'Member' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium" style="color: var(--muted);">User ID</p>
                        <p class="text-sm font-semibold" style="color: var(--text);">#{{ $user->id }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium" style="color: var(--muted);">Joined Date</p>
                        <p class="text-sm font-semibold" style="color: var(--text);">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium" style="color: var(--muted);">Last Updated</p>
                        <p class="text-sm font-semibold" style="color: var(--text);">{{ $user->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($user->user_type === 'Delivery Person' && $user->locations->count() > 0)
            <div class="mt-6 pt-6 border-t" style="border-color: var(--border);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Assigned Locations</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($user->locations as $location)
                        <div class="p-3 rounded-lg border hover:shadow-sm transition-shadow" style="border-color: var(--border);">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 rounded flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                                    <svg class="w-4 h-4" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-sm truncate" style="color: var(--text);">{{ $location->name }}</p>
                                    <p class="text-xs truncate" style="color: var(--muted);">{{ $location->area }}, {{ $location->city }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($user->user_type === 'Delivery Person')
            <div class="mt-6 pt-6 border-t" style="border-color: var(--border);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Location Assignment History</h3>
                @if($user->locationLogs->count() > 0)
                    <div class="space-y-3">
                        @foreach($user->locationLogs as $log)
                            <div class="flex items-start space-x-4 p-4 rounded-lg border" style="border-color: var(--border);">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $log->action === 'assigned' ? 'bg-green-100' : 'bg-red-100' }}">
                                    @if($log->action === 'assigned')
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium" style="color: var(--text);">
                                                <span class="capitalize {{ $log->action === 'assigned' ? 'text-green-600' : 'text-red-600' }}">{{ $log->action }}</span>
                                                @if($log->location)
                                                    - {{ $log->location->name }}
                                                @else
                                                    - Location (deleted)
                                                @endif
                                            </p>
                                            @if($log->notes)
                                                <p class="text-sm mt-1" style="color: var(--muted);">{{ $log->notes }}</p>
                                            @endif
                                            <div class="flex items-center space-x-4 mt-2 text-xs" style="color: var(--muted);">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    By: {{ $log->assignedBy ? $log->assignedBy->name : 'System' }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $log->created_at->format('M d, Y h:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 rounded-lg border" style="border-color: var(--border); background-color: rgba(0,0,0,0.02);">
                        <svg class="w-12 h-12 mx-auto mb-3" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm font-medium" style="color: var(--text);">No assignment history yet</p>
                        <p class="text-xs mt-1" style="color: var(--muted);">Location assignments will be tracked here</p>
                    </div>
                @endif
            </div>
        @endif

        @if($user->id !== auth()->id())
            <div class="mt-6 pt-6 border-t" style="border-color: var(--border);">
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors">
                        Delete User
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
