@extends('layouts.member-auth-cover')

@section('title', 'Member Registration')

@section('content')
<!-- Member Badge -->
<div class="text-center mb-3 sm:mb-4">
    <div class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full" style="background-color: rgba(47, 74, 30, 0.1);">
        <i class="fa-solid fa-user-plus text-xs" style="color: var(--primary-green);"></i>
        <span class="text-xs font-bold" style="color: var(--primary-green);">MEMBER REGISTRATION</span>
    </div>
</div>

<div class="text-center mb-4 sm:mb-6">
    <h2 class="text-xl sm:text-2xl font-bold" style="color: var(--text-dark);">Create Account</h2>
    <p class="mt-1 text-xs" style="color: var(--text-muted);">Register with your mobile number</p>
</div>

    <!-- Success/Error Messages -->
    <div id="messageContainer"></div>

    @if(!($otpEnabled ?? true))
    <div class="mb-3 sm:mb-4 p-2.5 sm:p-3 rounded-lg border text-xs" style="background-color: #fffbeb; border-color: #f59e0b; color: #92400e;">
        <i class="fa-solid fa-info-circle mr-1"></i>
        <strong>Development Mode:</strong> OTP service not configured. Registration will proceed without OTP verification.
    </div>
    @endif

    <!-- Registration Form -->
    <div id="detailsStep">
    <div class="mb-2.5 sm:mb-3">
        <label for="name" class="block text-xs font-medium mb-1 sm:mb-1.5" style="color: var(--text-dark);">
            <i class="fa-solid fa-user mr-1" style="color: var(--primary-green);"></i>
            Full Name
        </label>
        <input 
            type="text" 
            id="name" 
            class="w-full px-2.5 sm:px-3 py-2 sm:py-2.5 text-xs sm:text-sm border rounded-lg transition-all"
            style="border-color: var(--border-color); color: var(--text-dark);"
            placeholder="Enter your full name"
            required 
            autofocus
        >
    </div>

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
            >
        </div>
    </div>

    <div class="mb-2.5 sm:mb-3">
        <label for="email" class="block text-xs font-medium mb-1 sm:mb-1.5" style="color: var(--text-dark);">
            <i class="fa-solid fa-envelope mr-1" style="color: var(--primary-green);"></i>
            Email Address <span class="text-xs" style="color: var(--text-muted);">(Optional)</span>
        </label>
        <input 
            type="email" 
            id="email" 
            class="w-full px-2.5 sm:px-3 py-2 sm:py-2.5 text-xs sm:text-sm border rounded-lg transition-all"
            style="border-color: var(--border-color); color: var(--text-dark);"
            placeholder="Enter your email (optional)"
        >
    </div>

    <button 
        type="button"
        id="sendOtpBtn"
        onclick="sendRegisterOtp()"
        class="btn-primary w-full text-white py-2 sm:py-2.5 text-xs sm:text-sm rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
    >
        <i class="fa-solid fa-@if($otpEnabled ?? true)paper-plane @else user-plus @endif mr-1.5 sm:mr-2"></i>
        <span id="sendOtpText">@if($otpEnabled ?? true)Send OTP @else Register @endif</span>
    </button>
    </div>

    <!-- OTP Verification Step -->
    <div id="otpStep" class="hidden">
    <div class="mb-2.5 sm:mb-3 p-2.5 sm:p-3 rounded-lg" style="background-color: rgba(47, 74, 30, 0.05);">
        <p class="text-xs font-semibold mb-1" style="color: var(--text-dark);">Registration Details:</p>
        <p class="text-xs" style="color: var(--text-muted);">
            <i class="fa-solid fa-user mr-1"></i>
            <span id="displayName"></span>
        </p>
        <p class="text-xs" style="color: var(--text-muted);">
            <i class="fa-solid fa-mobile-screen-button mr-1"></i>
            <span id="displayPhone"></span>
        </p>
        <p class="text-xs" id="displayEmailContainer" style="color: var(--text-muted); display: none;">
            <i class="fa-solid fa-envelope mr-1"></i>
            <span id="displayEmail"></span>
        </p>
    </div>

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
            OTP sent to your mobile number
        </p>
    </div>

    <button 
        type="button"
        id="registerBtn"
        onclick="completeRegistration()"
        class="btn-primary w-full text-white py-2 sm:py-2.5 text-xs sm:text-sm rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center mb-1.5 sm:mb-2"
    >
        <i class="fa-solid fa-check-circle mr-1.5 sm:mr-2"></i>
        <span id="registerText">Verify & Register</span>
    </button>

    <button 
        type="button"
        id="resendOtpBtn"
        onclick="resendOtp()"
        class="w-full py-2 sm:py-2.5 text-xs sm:text-sm rounded-lg font-semibold border transition-colors hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        style="border-color: var(--border-color); color: var(--text-dark);"
    >
        <i class="fa-solid fa-rotate-right mr-1.5 sm:mr-2"></i>
        <span id="resendOtpText">Resend OTP</span>
    </button>

    <button 
        type="button"
        onclick="changeDetails()"
        class="w-full mt-1.5 sm:mt-2 py-1 sm:py-1.5 text-xs hover:underline transition-colors"
        style="color: var(--primary-green);"
    >
        <i class="fa-solid fa-arrow-left mr-1"></i>
        Change Details
    </button>
    </div>

<!-- Login Link -->
<p class="text-center text-xs mt-3 sm:mt-4" style="color: var(--text-muted);">
    Already have an account? 
    <a href="{{ route('member.login') }}" class="font-semibold hover:underline transition-colors" style="color: var(--primary-green);">Login Now</a>
</p>
@endsection

@push('scripts')
<script>
let registrationData = {};
let resendTimer = 0;
let resendInterval = null;

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

function startResendTimer() {
    resendTimer = 60;
    const btn = document.getElementById('resendOtpBtn');
    const btnText = document.getElementById('resendOtpText');
    btn.disabled = true;
    resendInterval = setInterval(() => {
        resendTimer--;
        btnText.textContent = `Resend OTP (${resendTimer}s)`;
        if (resendTimer <= 0) {
            clearInterval(resendInterval);
            btn.disabled = false;
            btnText.textContent = 'Resend OTP';
        }
    }, 1000);
}

function sendRegisterOtp() {
    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value;
    const email = document.getElementById('email').value.trim();
    
    if (!name) {
        showMessage('Please enter your full name', 'error');
        return;
    }
    
    if (!phone || phone.length !== 10) {
        showMessage('Please enter a valid 10-digit mobile number', 'error');
        return;
    }
    
    registrationData = { name, phone, email };
    
    const btn = document.getElementById('sendOtpBtn');
    const btnText = document.getElementById('sendOtpText');
    btn.disabled = true;
    btnText.textContent = 'Processing...';
    
    fetch('{{ route('member.send-register-otp') }}', {
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
            // If OTP is skipped (not configured), register directly
            if (data.skip_otp) {
                completeRegistrationWithoutOtp();
            } else {
                // Show OTP step
                document.getElementById('displayName').textContent = name;
                document.getElementById('displayPhone').textContent = '+91 ' + phone;
                if (email) {
                    document.getElementById('displayEmail').textContent = email;
                    document.getElementById('displayEmailContainer').style.display = 'block';
                }
                document.getElementById('detailsStep').classList.add('hidden');
                document.getElementById('otpStep').classList.remove('hidden');
                showMessage(data.message);
                document.getElementById('otp').focus();
                startResendTimer();
            }
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error); // Debug log
        showMessage('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = '@if($otpEnabled ?? true)Send OTP @else Register @endif';
    });
}

function completeRegistrationWithoutOtp() {
    const payload = {
        name: registrationData.name,
        phone: registrationData.phone
    };
    
    // Only add email if it exists
    if (registrationData.email) {
        payload.email = registrationData.email;
    }
    
    fetch('{{ route('member.register.submit') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json().then(data => ({ ok: response.ok, data })))
    .then(({ ok, data }) => {
        if (!ok) {
            showMessage(data.message || 'An error occurred. Please try again.', 'error');
            return;
        }
        if (data.success) {
            showMessage(data.message);
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 500);
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Registration error:', error);
        showMessage('An error occurred. Please try again.', 'error');
    });
}

function completeRegistration() {
    const otp = document.getElementById('otp').value;
    
    if (!otp || otp.length !== 6) {
        showMessage('Please enter a valid 6-digit OTP', 'error');
        return;
    }
    
    const btn = document.getElementById('registerBtn');
    const btnText = document.getElementById('registerText');
    btn.disabled = true;
    btnText.textContent = 'Registering...';
    
    fetch('{{ route('member.register.submit') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            name: registrationData.name,
            phone: registrationData.phone,
            email: registrationData.email,
            otp: otp
        })
    })
    .then(response => response.json().then(data => ({ ok: response.ok, data })))
    .then(({ ok, data }) => {
        if (!ok) {
            showMessage(data.message || 'An error occurred. Please try again.', 'error');
            btn.disabled = false;
            btnText.textContent = 'Verify & Register';
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
            btnText.textContent = 'Verify & Register';
        }
    })
    .catch(error => {
        showMessage('An error occurred. Please try again.', 'error');
        btn.disabled = false;
        btnText.textContent = 'Verify & Register';
    });
}

function resendOtp() {
    if (resendInterval) clearInterval(resendInterval);
    document.getElementById('otp').value = '';
    sendRegisterOtp();
}

function changeDetails() {
    document.getElementById('otpStep').classList.add('hidden');
    document.getElementById('detailsStep').classList.remove('hidden');
    document.getElementById('otp').value = '';
}

// Auto-submit OTP when 6 digits entered
document.getElementById('otp')?.addEventListener('input', function(e) {
    if (e.target.value.length === 6) {
        completeRegistration();
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
