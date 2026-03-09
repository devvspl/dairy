@extends('layouts.app')

@section('title', 'Locations')
@section('page-title', 'Locations')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 rounded-lg p-4 flex items-start gap-3" style="border-color: var(--green);">
        <svg class="w-5 h-5 flex-shrink-0" style="color: var(--green);" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <div class="flex-1">
            <p class="font-semibold" style="color: var(--green);">Success!</p>
            <p class="text-sm" style="color: var(--text);">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Total Locations</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Active</p>
                    <p class="text-2xl font-bold mt-1 text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--muted);">Inactive</p>
                    <p class="text-2xl font-bold mt-1 text-gray-600">{{ $stats['inactive'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-gray-100">
                    <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold flex items-center" style="color: var(--text);">
                    <svg class="w-6 h-6 mr-2" style="color: var(--green);" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Manage Locations
                </h1>
                <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                    Create and manage location-specific delivery pages
                </p>
            </div>
            <a href="{{ route('admin.locations.create') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold transition-colors hover:opacity-90"
               style="background-color: var(--green); color: #fff;">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Add Location
            </a>
        </div>
    </div>

    <!-- Locations List -->
    <div class="bg-white rounded-xl shadow-sm border" style="border-color: var(--border);">
        @if($locations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border); background-color: rgba(47, 74, 30, 0.02);">
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Location</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Area</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Building</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Status</th>
                            <th class="px-4 lg:px-6 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--text);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $location)
                        <tr class="hover:bg-gray-50 transition-colors" style="border-bottom: 1px solid var(--border);">
                            <td class="px-4 lg:px-6 py-4">
                                <div>
                                    <span class="text-sm font-bold" style="color: var(--text);">{{ $location->name }}</span>
                                    <p class="text-xs mt-1" style="color: var(--muted);">/location/{{ $location->slug }}</p>
                                </div>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm" style="color: var(--text);">
                                    @if($location->sector)Sector {{ $location->sector }}, @endif
                                    {{ $location->area ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span class="text-sm" style="color: var(--text);">{{ $location->building_name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                @if($location->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('location.detail', $location->slug) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors hover:bg-gray-100"
                                       style="color: var(--text); border: 1px solid var(--border);">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('admin.locations.edit', $location) }}" 
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors hover:bg-gray-100"
                                       style="color: var(--green); border: 1px solid var(--border);">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this location?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors hover:bg-red-50"
                                                style="color: #dc2626; border: 1px solid var(--border);">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($locations->hasPages())
            <div class="px-4 lg:px-6 py-4 border-t" style="border-color: var(--border);">
                {{ $locations->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12 px-4">
                <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-10 h-10" style="color: var(--green);" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color: var(--text);">No Locations Yet</h3>
                <p class="text-sm mb-6" style="color: var(--muted);">Create your first location page to showcase delivery areas.</p>
                <a href="{{ route('admin.locations.create') }}" 
                   class="inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold transition-colors hover:opacity-90"
                   style="background-color: var(--green); color: #fff;">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Add First Location
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
