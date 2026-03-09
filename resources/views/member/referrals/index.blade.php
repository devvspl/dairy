@extends('layouts.app')

@section('title', 'My Referrals')
@section('page-title', 'My Referrals')

@section('content')
<!-- Referral Code Card -->
<div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 mb-6 text-white">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-2">Your Referral Code</h2>
            <p class="text-green-100 mb-4">Share your code and earn rewards when friends join!</p>
            <div class="flex items-center gap-3">
                <div class="bg-white text-gray-900 px-6 py-3 rounded-lg font-mono text-2xl font-bold">
                    {{ $referralCode->code }}
                </div>
                <button onclick="copyCode()" class="bg-white/20 hover:bg-white/30 px-4 py-3 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy
                </button>
            </div>
        </div>
        <div class="flex gap-3">
            <button onclick="shareWhatsApp()" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path>
                    </svg>
                    WhatsApp
                </button>
                <button onclick="shareEmail()" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Email
                </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Total Referrals</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['total_referrals'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Total Earnings</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">₹{{ number_format($stats['total_earnings'], 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(245, 158, 11, 0.1);">
                <svg class="w-6 h-6" style="color: #f59e0b;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Pending</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['pending_referrals'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-between" style="background-color: rgba(59, 130, 246, 0.1);">
                <svg class="w-6 h-6" style="color: #3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border" style="border-color: var(--border);">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium" style="color: var(--muted);">Completed</p>
                <p class="text-2xl font-bold mt-1" style="color: var(--text);">{{ $stats['completed_referrals'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(47, 74, 30, 0.1);">
                <svg class="w-6 h-6" style="color: var(--green);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Referral History -->
<div class="bg-white rounded-xl shadow-sm border" style="border-color: var(--border);">
    <div class="p-4 lg:p-6 border-b" style="border-color: var(--border);">
        <h2 class="text-xl font-bold" style="color: var(--text);">Referral History</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="border-b" style="border-color: var(--border); background-color: #f9fafb;">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Referred User</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Your Reward</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Their Reward</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--muted);">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="divide-color: var(--border);">
                @forelse($referralUsages as $usage)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium" style="color: var(--text);">{{ $usage->referredUser->name }}</div>
                        <div class="text-xs" style="color: var(--muted);">{{ $usage->referredUser->email }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm font-medium" style="color: var(--green);">₹{{ number_format($usage->referrer_reward, 2) }}</td>
                    <td class="px-4 py-3 text-sm font-medium" style="color: var(--green);">₹{{ number_format($usage->referee_reward, 2) }}</td>
                    <td class="px-4 py-3">
                        @if($usage->status === 'completed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Completed
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm" style="color: var(--muted);">{{ $usage->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-sm" style="color: var(--muted);">
                        No referrals yet. Start sharing your code!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($referralUsages->hasPages())
    <div class="px-4 py-3 border-t" style="border-color: var(--border);">
        {{ $referralUsages->links() }}
    </div>
    @endif
</div>

<script>
function copyCode() {
    const code = '{{ $referralCode->code }}';
    navigator.clipboard.writeText(code).then(() => {
        alert('Referral code copied to clipboard!');
    });
}

function shareWhatsApp() {
    const code = '{{ $referralCode->code }}';
    const text = `Join our milk delivery service using my referral code: ${code} and get special rewards!`;
    const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

function shareEmail() {
    const code = '{{ $referralCode->code }}';
    const subject = 'Join our milk delivery service!';
    const body = `Hi,\n\nI'd like to invite you to join our milk delivery service. Use my referral code: ${code} to get special rewards!\n\nBest regards`;
    const url = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = url;
}
</script>
@endsection
