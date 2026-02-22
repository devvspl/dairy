@extends('layouts.app')

@section('title', 'About Sections Management')
@section('page-title', 'About Sections Management')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <div>
                <h2 class="text-xl font-bold" style="color: var(--text);">All About Sections</h2>
                <p class="text-sm mt-1" style="color: var(--muted);">Manage homepage about section content</p>
            </div>
            
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <form method="GET" action="{{ route('admin.about-sections.index') }}" class="flex-1 sm:flex-initial">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Search about sections..."
                        class="w-full sm:w-64 px-3 py-2 border rounded-lg focus:outline-none transition-all text-sm"
                        style="border-color: var(--border); color: var(--text);"
                    >
                </form>
                
                <a href="{{ route('admin.about-sections.create') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add About Section
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg border" style="background-color: #f0fdf4; border-color: var(--green); color: #166534;">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden" style="border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: rgba(47, 74, 30, 0.05);">
                    <tr>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text);">Title</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color: var(--text);">Order</th>
                        <th class="px-4 lg:px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider hidden lg:table-cell" style="color: var(--text);">Status</th>
                        <th class="px-4 lg:px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider" style="color: var(--text);">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border);">
                    @forelse($aboutSections as $aboutSection)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 lg:px-6 py-4">
                                <div>
                                    <p class="font-medium" style="color: var(--text);">{{ $aboutSection->title }}</p>
                                    @if($aboutSection->kicker)
                                        <p class="text-sm" style="color: var(--muted);">{{ $aboutSection->kicker }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 lg:px-6 py-4 hidden md:table-cell">
                                <span class="text-sm" style="color: var(--text);">{{ $aboutSection->order }}</span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 hidden lg:table-cell">
                                <span class="px-2 py-1 text-xs rounded-full {{ $aboutSection->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $aboutSection->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.about-sections.show', $aboutSection) }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" style="color: var(--green);" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.about-sections.edit', $aboutSection) }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" style="color: var(--green);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.about-sections.destroy', $aboutSection) }}" onsubmit="return confirm('Are you sure?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-50 transition-colors text-red-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 lg:px-6 py-12 text-center">
                                <p class="text-lg font-medium mb-1" style="color: var(--text);">No about sections found</p>
                                <p class="text-sm" style="color: var(--muted);">Get started by creating a new about section</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($aboutSections->hasPages())
            <div class="px-4 lg:px-6 py-4 border-t" style="border-color: var(--border);">
                {{ $aboutSections->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
