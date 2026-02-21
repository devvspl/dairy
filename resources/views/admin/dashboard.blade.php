@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-4 lg:space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                    {{ now()->format('l, F j, Y') }} at {{ now()->format('g:i A') }}
                </p>
            </div>
            <button class="inline-flex items-center px-4 py-2 rounded-lg text-white text-sm font-medium hover:opacity-90 transition-opacity" style="background-color: var(--green);">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Report
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background-color: rgba(34, 197, 94, 0.1); color: #16a34a;">+12%</span>
            </div>
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Total Users</p>
                <p class="text-2xl lg:text-3xl font-bold" style="color: var(--text);">1,234</p>
                <p class="text-xs mt-2" style="color: var(--muted);">
                    <span style="color: #16a34a;">↑ 145</span> from last month
                </p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background-color: rgba(34, 197, 94, 0.1); color: #16a34a;">+8%</span>
            </div>
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Revenue</p>
                <p class="text-2xl lg:text-3xl font-bold" style="color: var(--text);">$45,231</p>
                <p class="text-xs mt-2" style="color: var(--muted);">
                    <span style="color: #16a34a;">↑ $3,420</span> from last month
                </p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background-color: rgba(239, 68, 68, 0.1); color: #dc2626;">-3%</span>
            </div>
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Orders</p>
                <p class="text-2xl lg:text-3xl font-bold" style="color: var(--text);">567</p>
                <p class="text-xs mt-2" style="color: var(--muted);">
                    <span style="color: #dc2626;">↓ 18</span> from last month
                </p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border hover:shadow-md transition-shadow" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                    <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background-color: rgba(34, 197, 94, 0.1); color: #16a34a;">+5%</span>
            </div>
            <div>
                <p class="text-sm font-medium mb-1" style="color: var(--muted);">Growth Rate</p>
                <p class="text-2xl lg:text-3xl font-bold" style="color: var(--text);">23.5%</p>
                <p class="text-xs mt-2" style="color: var(--muted);">
                    <span style="color: #16a34a;">↑ 1.2%</span> from last month
                </p>
            </div>
        </div>
    </div>

    <!-- Charts & Activity Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <h2 class="text-lg font-semibold mb-2 sm:mb-0" style="color: var(--text);">Revenue Overview</h2>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs font-medium rounded-lg text-white" style="background-color: var(--green);">Week</button>
                    <button class="px-3 py-1 text-xs font-medium rounded-lg hover:bg-gray-100 transition-colors" style="color: var(--muted);">Month</button>
                    <button class="px-3 py-1 text-xs font-medium rounded-lg hover:bg-gray-100 transition-colors" style="color: var(--muted);">Year</button>
                </div>
            </div>
            
            <!-- Simple Chart Placeholder -->
            <div class="h-48 lg:h-64 flex items-end justify-between space-x-2">
                <div class="flex-1 rounded-t-lg transition-all hover:opacity-80" style="background-color: rgba(47, 74, 30, 0.2); height: 45%;"></div>
                <div class="flex-1 rounded-t-lg transition-all hover:opacity-80" style="background-color: rgba(47, 74, 30, 0.3); height: 60%;"></div>
                <div class="flex-1 rounded-t-lg transition-all hover:opacity-80" style="background-color: var(--green); height: 85%;"></div>
                <div class="flex-1 rounded-t-lg transition-all hover:opacity-80" style="background-color: rgba(47, 74, 30, 0.4); height: 70%;"></div>
                <div class="flex-1 rounded-t-lg transition-all hover:opacity-80" style="background-color: rgba(47, 74, 30, 0.3); height: 55%;"></div>
                <div class="flex-1 rounded-t-lg transition-all hover:opacity-80" style="background-color: rgba(47, 74, 30, 0.5); height: 75%;"></div>
                <div class="flex-1 rounded-t-lg transition-all hover:opacity-80" style="background-color: rgba(47, 74, 30, 0.2); height: 40%;"></div>
            </div>
            <div class="flex justify-between mt-4 text-xs" style="color: var(--muted);">
                <span>Mon</span>
                <span>Tue</span>
                <span>Wed</span>
                <span>Thu</span>
                <span>Fri</span>
                <span>Sat</span>
                <span>Sun</span>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--text);">Recent Activity</h2>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-5 h-5" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium" style="color: var(--text);">New user registered</p>
                        <p class="text-xs mt-1" style="color: var(--muted);">2 minutes ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(34, 197, 94, 0.1);">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium" style="color: var(--text);">Order #1234 completed</p>
                        <p class="text-xs mt-1" style="color: var(--muted);">15 minutes ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(234, 179, 8, 0.1);">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium" style="color: var(--text);">System maintenance</p>
                        <p class="text-xs mt-1" style="color: var(--muted);">1 hour ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(59, 130, 246, 0.1);">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium" style="color: var(--text);">New message received</p>
                        <p class="text-xs mt-1" style="color: var(--muted);">3 hours ago</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(168, 85, 247, 0.1);">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium" style="color: var(--text);">Report generated</p>
                        <p class="text-xs mt-1" style="color: var(--muted);">5 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--text);">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <button class="flex flex-col items-center justify-center p-4 rounded-lg border hover:shadow-md transition-all" style="border-color: var(--border);">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium" style="color: var(--text);">Add User</span>
                </button>

                <button class="flex flex-col items-center justify-center p-4 rounded-lg border hover:shadow-md transition-all" style="border-color: var(--border);">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium" style="color: var(--text);">Generate Report</span>
                </button>

                <button class="flex flex-col items-center justify-center p-4 rounded-lg border hover:shadow-md transition-all" style="border-color: var(--border);">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium" style="color: var(--text);">Send Email</span>
                </button>

                <button class="flex flex-col items-center justify-center p-4 rounded-lg border hover:shadow-md transition-all" style="border-color: var(--border);">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2" style="background-color: rgba(47, 74, 30, 0.1);">
                        <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium" style="color: var(--text);">Settings</span>
                </button>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold" style="color: var(--text);">Recent Orders</h2>
                <a href="#" class="text-sm font-medium hover:underline" style="color: var(--green);">View all</a>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                            <span class="text-sm font-bold" style="color: var(--green);">#01</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color: var(--text);">Order #1234</p>
                            <p class="text-xs" style="color: var(--muted);">John Doe</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap" style="background-color: rgba(34, 197, 94, 0.1); color: #16a34a;">Completed</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                            <span class="text-sm font-bold" style="color: var(--green);">#02</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color: var(--text);">Order #1235</p>
                            <p class="text-xs" style="color: var(--muted);">Jane Smith</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap" style="background-color: rgba(234, 179, 8, 0.1); color: #ca8a04;">Pending</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                            <span class="text-sm font-bold" style="color: var(--green);">#03</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color: var(--text);">Order #1236</p>
                            <p class="text-xs" style="color: var(--muted);">Bob Johnson</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap" style="background-color: rgba(59, 130, 246, 0.1); color: #2563eb;">Processing</span>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: rgba(47, 74, 30, 0.1);">
                            <span class="text-sm font-bold" style="color: var(--green);">#04</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color: var(--text);">Order #1237</p>
                            <p class="text-xs" style="color: var(--muted);">Alice Brown</p>
                        </div>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap" style="background-color: rgba(34, 197, 94, 0.1); color: #16a34a;">Completed</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
