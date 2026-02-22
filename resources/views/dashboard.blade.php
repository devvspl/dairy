@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-4 lg:space-y-6">
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border" style="border-color: var(--border);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                <div>
                    <h1 class="text-xl lg:text-2xl font-bold" style="color: var(--text);">Welcome, {{ auth()->user()->name }}!
                        👋</h1>
                    <p class="text-sm lg:text-base mt-1" style="color: var(--muted);">
                        {{ now()->format('l, F j, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
