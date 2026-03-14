@extends('layouts.member-auth-cover')

@section('title', 'Member Login')

@section('content')
<!-- Member Badge -->
<div class="text-center mb-3 sm:mb-4">
    <div class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full" style="background-color: rgba(47, 74, 30, 0.1);">
        <i class="fa-solid fa-mobile-screen-button text-xs" style="color: var(--primary-green);"></i>
        <span class="text-xs font-bold" style="color: var(--primary-green);">MEMBER LOGIN</span>
    </div>
</div>

<div class="text-center mb-4 sm:mb-6">
    <h2 class="text-xl sm:text-2xl font-bold" style="color: var(--text-dark);">Welcome Back!</h2>
    <p class="mt-1 text-xs" style="color: var(--text-muted);">Login with your mobile number</p>
</div>

    <!-- Success/Error Messages -->
    <div id="messageContainer"></div>

    @if(!($otpEnabled ?? true))
    <div class="mb-3 sm:mb-4 p-2.5 sm:p-3 rounded-lg border text-xs" style="background-color: #fffbeb; border-color: #f59e0b; color: #92400e;">
        <i class="fa-solid fa-info-circle mr-1"></i>
        <strong>Development Mode:</strong> OTP service not configured. Login will proceed without OTP verification.
    </div>
    @endif

    <!-- Login Form -->
    <div id="phoneStep">
    <div class="mb-2.5 sm:mb-3">
        <label for="phone" class="block text-xs font-medium mb-1 sm:mb-1.5" style="color: var(--text-dark);">
            <i class="fa-solid fa-mobile-screen-button mr-1" style="color: var(--primary-green);"></i>
            Mobile Number
        </label>
        <div class="relative">
            <span class="absolute left-2.5 sm:left-3 top-1/2 transform -translate-y-1/2 text-xs font-semibold" style="color: var(--text-dark);">+91</span>
            <input 
                type="tel" 
                id="phone" 
                maxlength="10"
                pattern="[0-9]{10}"
                class="w-full pl-10 sm:pl-12 pr-2.5 sm:pr-3 py-2 sm:py-2.5 text-xs sm:text-sm border rounded-lg transition-all"
                style="border-color: var(--border-color); color: var(--text-dark);"
                placeholder="Enter 10-digit mobile number"
                required 
                autofocus
            >
        </div>
        <p class="mt-0.5 sm:mt-1 text-xs" style="color: var(--text-muted);">
            <i class="fa-solid fa-info-circle mr-1"></i>Enter your registered mobile number
        </p>
    </div>

    <button 
        type="button"
        id="sendOtpBtn"
        onclick="sendOtp()"
        class="btn-primary w-full text-white py-2 sm:py-2.5 text-xs sm:text-sm rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
    >
        <i class="fa-solid fa-@if($otpEnabled ?? true)paper-plane @else sign-in-alt @endif mr-1.5 sm:mr-2"></i>
        <span id="sendOtpText">@if($otpEnabled ?? true)Send OTP @else Login @endif</span>
    </button>
    </div>

    <!-- OTP Verification Step -->
    <div id="otpStep" class="hidden">
    <div class="mb-2.5 sm:mb-3">
        <label for="otp" class="block text-xs font-medium mb-1 sm:mb-1.5" style="color: var(--text-dark);">
            <i class="fa-solid fa-key mr-1" style="color: var(--primary-green);"></i>
            Enter OTP
        </label>
        <input 
            type="text" 
            id="otp" 
            maxlength="6"
            pattern="[0-9]{6}"
            class="w-full px-2.5 sm:px-3 py-2 sm:py-2.5 border rounded-lg transition-all text-center text-lg sm:text-xl font-bold tracking-widest"
            style="border-color: var(--border-color); color: var(--text-dark);"
            placeholder="000000"
        >
        <p class="mt-0.5 sm:mt-1 text-xs" style="color: var(--text-muted);">
            <i class="fa-solid fa-info-circle mr-1"></i>
            OTP sent to <span id="displayPhone" class="font-semibold"></span>
        </p>
    </div>

    <button 
        type="button"
        id="verifyOtpBtn"
        onclick="verifyOtp()"
        class="btn-primary w-full text-white py-2 sm:py-2.5 text-xs sm:text-sm rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center mb-1.5 sm:mb-2"
    >
        <i class="fa-solid fa-check-circle mr-1.5 sm:mr-2"></i>
        <span id="verifyOtpText">Verify & Login</span>
    </button>

    <button 
        type="button"
        onclick="resendOtp()"
        class="w-full py-2 sm:py-2.5 text-xs sm:text-sm rounded-lg font-semibold border transition-colors hover:bg-gray-50"
        style="border-color: var(--border-color); color: var(--text-dark);"
    >
        <i class="fa-solid fa-rotate-right mr-1.5 sm:mr-2"></i>
        Resend OTP
    </button>

    <button 
        type="button"
        onclick="changeNumber()"
        class="w-full mt-1.5 sm:mt-2 py-1 sm:py-1.5 text-xs hover:underline transition-colors"
        style="color: var(--primary-green);"
    >
        <i class="fa-solid fa-arrow-left mr-1"></i>
        Change Mobile Number
    </button>
    </div>

<!-- Register Link -->
<p class="text-center text-xs mt-3 sm:mt-4" style="color: var(--text-muted);">
    Don't have an account? 
    <a href="{{ route('member.register') }}" class="font-semibold hover:underline transition-colors" style="color: var(--primary-green);">Register Now</a>
</p>
@endsection

@push('scripts')
<script>
let currentPhone = '';

function showMessage(message, type = 'success') {
    const container = document.getElementById('messageContainer');
    const bgColor = type === 'success' ? '#f0f9f4' : '#fef2f2';
    const borderColor = type === 'success' ? 'var(--primary-green)' : '#dc2626';
    const textColor = type === 'success' ? 'var(--primary-green-dark)' : '#dc2626';
    
    container.innerHTML = `
        <div class="mb-3 sm:mb-4 p-2.5 sm:p-4 rounded-lg border text-xs sm:text-sm" style="background-color: ${bgColor}; border-color: ${borderColor}; color: ${textColor};">
            ${message}
        </div>
    `;
    
    setTimeout(() => {
        container.innerHTML = '';
    }, 5000);
}

function sendOtp() {
    const phone = document.getElementById('phone').value;
    
    if (!phone || phone.length !== 10) {
        showMessage('Please enter a valid 10-digit mobile number', 'error');
        return;
    }
    
    const btn = document.getElementById('sendOtpBtn');
    const btnText = document.getElementById('sendOtpText');
    btn.disabled = true;
    btnText.textContent = 'Processing...';
    
    fetch('{{ route("member.send-login-otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ phone: phone })
    })
    .then(response => response.json().then(data => ({ ok: response.ok, data })))
    .then(({ ok, data }) => {
        if (!ok) {
            showMessage(data.message || 'An error occurred. Please try again.', 'error');
            return;
        }
        if (data.success) {
            // If OTP is skipped (not configured), redirect directly
            if (data.skip_otp) {
                showMessage(data.message);
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 500);
            } else {
                // Show OTP step
                currentPhone = phone;
                document.getElementById('displayPhone').textContent = '+91 ' + phone;
                document.getElementById('phoneStep').classList.add('hidden');
                document.getElementById('otpStep').classList.remove('hidden');
                showMessage(data.message + (data.otp ? ' — OTP: <strong>' + data.otp + '</strong>' : ''));
                document.getElementById('otp').focus();
            }
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        showMessage('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Send OTP';
    });
}

function verifyOtp() {
    const otp = document.getElementById('otp').value;
    
    if (!otp || otp.length !== 6) {
        showMessage('Please enter a valid 6-digit OTP', 'error');
        return;
    }
    
    const btn = document.getElementById('verifyOtpBtn');
    const btnText = document.getElementById('verifyOtpText');
    btn.disabled = true;
    btnText.textContent = 'Verifying...';
    
    fetch('{{ route("member.verify-login-otp") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            phone: currentPhone,
            otp: otp 
        })
    })
    .then(response => response.json().then(data => ({ ok: response.ok, data })))
    .then(({ ok, data }) => {
        if (!ok) {
            showMessage(data.message || 'An error occurred. Please try again.', 'error');
            btn.disabled = false;
            btnText.textContent = 'Verify & Login';
            return;
        }
        if (data.success) {
            showMessage(data.message);
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            showMessage(data.message, 'error');
            btn.disabled = false;
            btnText.textContent = 'Verify & Login';
        }
    })
    .catch(error => {
        showMessage('An error occurred. Please try again.', 'error');
        btn.disabled = false;
        btnText.textContent = 'Verify & Login';
    });
}

function resendOtp() {
    document.getElementById('otp').value = '';
    sendOtp();
}

function changeNumber() {
    document.getElementById('otpStep').classList.add('hidden');
    document.getElementById('phoneStep').classList.remove('hidden');
    document.getElementById('phone').value = '';
    document.getElementById('otp').value = '';
    currentPhone = '';
}

// Auto-submit OTP when 6 digits entered
document.getElementById('otp')?.addEventListener('input', function(e) {
    if (e.target.value.length === 6) {
        verifyOtp();
    }
});

// Only allow numbers in phone input
document.getElementById('phone')?.addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});

// Only allow numbers in OTP input
document.getElementById('otp')?.addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});
</script>
@endpush
