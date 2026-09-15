@extends('layouts.auth')

@section('login')
<div class="login-box">
    <div class="login-box-body">
        <div class="login-logo text-center">
            <div style="min-width: 56px; min-height: 56px; max-width: 120px; background: #fff7ed; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 6px; padding: 6px;">
                @if(!empty($setting->path_logo) && file_exists(public_path($setting->path_logo)))
                    <img src="{{ url($setting->path_logo) }}" alt="{{ $setting->nama_perusahaan ?? 'Logo' }}" style="max-height: 50px; max-width: 110px; object-fit: contain;">
                @else
                    <i class="fa fa-cutlery" style="font-size: 26px; color: #f97316;"></i>
                @endif
            </div>
            <h3>{{ $setting->nama_perusahaan ?? 'Restaurant POS' }}</h3>
            <p>Sign in to manage kitchen, orders & billing</p>
        </div>

        <form action="{{ route('login') }}" method="post" class="form-login">
            @csrf
            <div class="form-group @error('email') has-error @enderror">
                <div class="form-control-icon-wrapper">
                    <i class="fa fa-envelope-o"></i>
                    <input type="email" name="email" id="input-email" class="form-control" placeholder="Email address" required value="{{ old('email', 'admin@mail.com') }}" autofocus>
                </div>
                @error('email')
                    <span class="help-block text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group @error('password') has-error @enderror">
                <div class="form-control-icon-wrapper">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" id="input-password" class="form-control" placeholder="Password" required value="codeastro.com">
                </div>
                @error('password')
                    <span class="help-block text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn-restaurant-login">
                    Sign In to Portal <i class="fa fa-arrow-right" style="margin-left: 6px;"></i>
                </button>
            </div>
        </form>

        <div class="quick-demo-pill text-center">
            <div><strong>Demo Quick Credentials:</strong></div>
            <div style="margin-top: 6px;">
                <span class="quick-demo-btn" onclick="fillCreds('admin@mail.com', 'codeastro.com')">
                    🔑 Admin: admin@mail.com
                </span>
                <span class="quick-demo-btn" onclick="fillCreds('astro@mail.com', 'codeastro.com')">
                    🔑 Cashier: astro@mail.com
                </span>
            </div>
        </div>
    </div>
</div>

<script>
function fillCreds(email, password) {
    document.getElementById('input-email').value = email;
    document.getElementById('input-password').value = password;
}
</script>
@endsection