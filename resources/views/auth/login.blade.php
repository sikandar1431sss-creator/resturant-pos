@extends('layouts.auth')

@section('login')
<div class="login-card-container">
    <div class="login-card">
        
        <!-- Boxed Logo & Title -->
        <div class="logo-box-wrapper">
            <div class="logo-frame-box">
                @if(!empty($setting->path_logo) && file_exists(public_path($setting->path_logo)))
                    <img src="{{ url($setting->path_logo) }}" alt="{{ $setting->nama_perusahaan ?? 'Logo' }}">
                @else
                    <i class="fa fa-cutlery" style="font-size: 32px; color: #ea580c;"></i>
                @endif
            </div>
            <div class="login-brand-title">
                {{ $setting->nama_perusahaan ?? 'Restaurant POS' }}
            </div>
            <div class="login-subtitle">
                Please enter your credentials to sign in
            </div>
        </div>

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="post" id="loginForm">
            @csrf

            <!-- Email Input -->
            <div class="form-group-custom">
                <label class="form-label-custom" for="inputEmail">Email Address</label>
                <div class="input-custom-wrapper">
                    <i class="fa fa-envelope-o input-icon"></i>
                    <input type="email" 
                           name="email" 
                           id="inputEmail" 
                           class="input-custom" 
                           placeholder="Enter your email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus>
                </div>
                @error('email')
                    <div style="color: #ef4444; font-size: 12px; margin-top: 5px; font-weight: 600;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Input -->
            <div class="form-group-custom">
                <label class="form-label-custom" for="inputPassword">Password</label>
                <div class="input-custom-wrapper">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" 
                           name="password" 
                           id="inputPassword" 
                           class="input-custom" 
                           placeholder="Enter your password" 
                           required>
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility()" title="Show/Hide Password">
                        <i class="fa fa-eye" id="pwdToggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div style="color: #ef4444; font-size: 12px; margin-top: 5px; font-weight: 600;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-login-submit" id="btnLoginSubmit">
                <span>Sign In to POS</span>
                <i class="fa fa-arrow-right" style="margin-left: 8px;"></i>
            </button>
        </form>

        <div class="login-footer-copy">
            &copy; {{ date('Y') }} {{ $setting->nama_perusahaan ?? 'Restaurant POS' }}. All rights reserved.
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
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