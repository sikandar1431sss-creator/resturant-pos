@extends('layouts.auth')

@section('login')
<div class="login-card-container">
    <div class="login-card">
        
        <!-- Boxed Logo -->
        <div class="logo-box-wrapper">
            <div class="logo-frame-box">
                @if(!empty($setting->path_logo) && file_exists(public_path($setting->path_logo)))
                    <img src="{{ url($setting->path_logo) }}" alt="{{ $setting->nama_perusahaan ?? 'Logo' }}">
                @else
                    <i class="fa fa-cutlery" style="font-size: 32px; color: #f97316;"></i>
                @endif
            </div>
        </div>

        <!-- Subtitle Text -->
        <div class="login-subtitle">
            Login To Your Account
        </div>

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="post" id="loginForm">
            @csrf

            <!-- Email Input -->
            <div class="form-group-custom">
                <label class="form-label-custom" for="inputEmail">Email Address</label>
                <div class="input-custom-wrapper">
                    <input type="email" 
                           name="email" 
                           id="inputEmail" 
                           class="input-custom" 
                           placeholder="Enter your email" 
                           value="{{ old('email', 'admin@mail.com') }}" 
                           required 
                           autofocus>
                </div>
                @error('email')
                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px; font-weight: 600;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Input -->
            <div class="form-group-custom">
                <label class="form-label-custom" for="inputPassword">Password</label>
                <div class="input-custom-wrapper">
                    <input type="password" 
                           name="password" 
                           id="inputPassword" 
                           class="input-custom" 
                           placeholder="Enter password" 
                           value="password123" 
                           required>
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility()" title="Show/Hide Password">
                        <i class="fa fa-eye" id="pwdToggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px; font-weight: 600;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Green Login Button (Matches reference image) -->
            <button type="submit" class="btn-green-submit" id="btnLoginSubmit">
                Login
            </button>
        </form>

        <!-- Quick 1-Click Role Login Credentials Panel -->
        <div class="quick-credentials-panel">
            <div class="quick-cred-heading">
                <span>⚡ Quick Login as (1-Click):</span>
                <span style="color: #16a34a; font-size: 11px; text-transform: none; font-weight: 600;">Click to autofill</span>
            </div>

            <div class="quick-role-grid">
                <div class="quick-role-chip active-chip" id="chip-admin" onclick="fillCreds('admin@mail.com', 'password123', 'chip-admin')">
                    <span>👑 Admin</span>
                    <i class="fa fa-check-circle" style="color: #16a34a;"></i>
                </div>

                <div class="quick-role-chip" id="chip-manager" onclick="fillCreds('manager@mail.com', 'password123', 'chip-manager')">
                    <span>👔 Manager</span>
                    <i class="fa fa-arrow-circle-right" style="opacity: 0.5;"></i>
                </div>

                <div class="quick-role-chip" id="chip-cashier" onclick="fillCreds('cashier@mail.com', 'password123', 'chip-cashier')">
                    <span>💳 Cashier</span>
                    <i class="fa fa-arrow-circle-right" style="opacity: 0.5;"></i>
                </div>

                <div class="quick-role-chip" id="chip-kitchen" onclick="fillCreds('kitchen@mail.com', 'password123', 'chip-kitchen')">
                    <span>👨‍🍳 Kitchen Chef</span>
                    <i class="fa fa-arrow-circle-right" style="opacity: 0.5;"></i>
                </div>

                <div class="quick-role-chip" id="chip-waiter" style="grid-column: span 2;" onclick="fillCreds('waiter@mail.com', 'password123', 'chip-waiter')">
                    <span>🧑‍💼 Dine-In Waiter (Table POS)</span>
                    <i class="fa fa-arrow-circle-right" style="opacity: 0.5;"></i>
                </div>
            </div>

            <div class="pwd-hint-banner">
                🔑 Password for all roles: <strong style="color: #0f172a;">password123</strong>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Autofill credentials on chip click
    function fillCreds(email, password, chipId) {
        document.getElementById('inputEmail').value = email;
        document.getElementById('inputPassword').value = password;

        // Highlight active chip
        $('.quick-role-chip').removeClass('active-chip').find('i').attr('class', 'fa fa-arrow-circle-right').css('color', '');
        $('#' + chipId).addClass('active-chip').find('i').attr('class', 'fa fa-check-circle').css('color', '#16a34a');

        // Focus submit button
        document.getElementById('btnLoginSubmit').focus();
    }

    // Toggle password visibility
    function togglePasswordVisibility() {
        const input = document.getElementById('inputPassword');
        const icon = document.getElementById('pwdToggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush