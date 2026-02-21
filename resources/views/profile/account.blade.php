@extends('layouts.app')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Account Overview -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold" style="color: var(--text);">Account Overview</h2>
                <p class="text-sm mt-1" style="color: var(--muted);">Manage your account settings and preferences</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" style="background-color: rgba(34, 197, 94, 0.1); color: #16a34a;">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Active Account
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">User ID</p>
                        <p class="text-lg font-bold" style="color: var(--text);">#{{ auth()->user()->id }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Joined</p>
                        <p class="text-lg font-bold" style="color: var(--text);">{{ auth()->user()->created_at->format('M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color: var(--muted);">Last Login</p>
                        <p class="text-lg font-bold" style="color: var(--text);">{{ now()->format('M d') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Security Settings</h3>
        
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-start space-x-3 mb-3 sm:mb-0">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium" style="color: var(--text);">Password</p>
                        <p class="text-sm" style="color: var(--muted);">Last changed {{ auth()->user()->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: rgba(47, 74, 30, 0.1); color: var(--green);">
                    Change Password
                </a>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-lg border" style="border-color: var(--border);">
                <div class="flex items-start space-x-3 mb-3 sm:mb-0">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium" style="color: var(--text);">Email Address</p>
                        <p class="text-sm" style="color: var(--muted);">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: rgba(34, 197, 94, 0.1); color: #16a34a;">
                    Verified
                </span>
            </div>


        </div>
    </div>

<!-- Sessions -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <h3 class="text-lg font-semibold mb-4" style="color: var(--text);">Active Sessions</h3>
        
        <div class="space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
                <div class="flex items-start space-x-3 mb-3 sm:mb-0">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: var(--green);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium" style="color: var(--text);">Windows PC - Chrome</p>
                        <p class="text-sm" style="color: var(--muted);">Current session • {{ now()->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: rgba(34, 197, 94, 0.1); color: #16a34a;">
                    Active Now
                </span>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-red-200">
        <h3 class="text-lg font-semibold mb-2 text-red-600">Danger Zone</h3>
        <p class="text-sm mb-4" style="color: var(--muted);">Once you delete your account, there is no going back. Please be certain.</p>
        
        <button 
            onclick="document.getElementById('deleteModal').classList.remove('hidden')"
            class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors"
        >
            Delete Account
        </button>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" x-data="{ password: '', loading: false }">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-center mb-2" style="color: var(--text);">Delete Account</h3>
        <p class="text-sm text-center mb-6" style="color: var(--muted);">This action cannot be undone. All your data will be permanently deleted.</p>
        
        <form method="POST" action="{{ route('account.delete') }}" @submit="loading = true">
            @csrf
            @method('DELETE')
            
            <div class="mb-4">
                <label for="delete_password" class="block text-sm font-medium mb-2" style="color: var(--text);">Confirm your password</label>
                <input 
                    type="password" 
                    id="delete_password" 
                    name="password"
                    x-model="password"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none transition-all"
                    style="border-color: var(--border); color: var(--text);"
                    required
                >
            </div>
            
            <div class="flex space-x-3">
                <button 
                    type="button"
                    onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="flex-1 px-4 py-3 border rounded-lg font-semibold hover:bg-gray-50 transition-colors"
                    style="border-color: var(--border); color: var(--text);"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    :disabled="loading || password.length === 0"
                    class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading">Delete Account</span>
                    <span x-show="loading">Deleting...</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
