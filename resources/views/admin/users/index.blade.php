@extends('layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Header with Search and Add Button -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div>
                <h2 class="text-xl font-bold" style="color: var(--text);">All Users</h2>
                <p class="text-sm mt-1" style="color: var(--muted);">Manage and view all registered users</p>
            </div>
            
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 sm:flex-initial">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search users..."
                        class="w-full sm:w-64 px-3 py-2 border rounded-lg focus:outline-none transition-all text-sm"
                        style="border-color: var(--border); color: var(--text);"
                        onfocus="this.style.borderColor='var(--green)'; this.style.boxShadow='0 0 0 3px rgba(47, 74, 30, 0.1)'"
                        onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
                    >
                </form>
                
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add User
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="p-4 rounded-lg border" style="background-color: #fef2f2; border-color: #dc2626; color: #991b1b;">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: rgba(47, 74, 30, 0.05);">
                    <tr>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text);">User</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color: var(--text);">Phone</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider hidden lg:table-cell" style="color: var(--text);">Joined</th>
                        <th class="px-4 lg:px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider" style="color: var(--text);">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border);">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 lg:px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0" style="background-color: var(--green);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium truncate" style="color: var(--text);">{{ $user->name }}</p>
                                        <p class="text-sm truncate" style="color: var(--muted);">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 lg:px-6 py-4 hidden md:table-cell">
                                <span class="text-sm" style="color: var(--text);">{{ $user->phone ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 hidden lg:table-cell">
                                <span class="text-sm" style="color: var(--text);">{{ $user->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" style="color: var(--green);" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" style="color: var(--green);" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg hover:bg-red-50 transition-colors text-red-600" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 lg:px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 mb-4" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium mb-1" style="color: var(--text);">No users found</p>
                                    <p class="text-sm" style="color: var(--muted);">Get started by creating a new user</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 lg:px-6 py-4 border-t" style="border-color: var(--border);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
