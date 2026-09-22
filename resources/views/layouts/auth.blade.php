<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $setting->nama_perusahaan ?? 'Restaurant POS' }} - Sign In</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="icon" href="{{ url($setting->path_logo ?? 'img/logo.png') }}" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') }}">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body.login-page {
            min-height: 100vh;
            margin: 0;
            padding: 24px 14px;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Fullscreen Infinite Background Video */
        .bg-video-element {
            position: fixed;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
            z-index: 0;
            pointer-events: none;
        }

        /* Dark Radial Gradient Overlay for High Readability */
        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(15, 23, 42, 0.45) 0%, rgba(15, 23, 42, 0.78) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .login-card-container {
            width: 420px;
            max-width: 95vw;
            position: relative;
            z-index: 10;
            margin: auto;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.94);
            border-radius: 20px;
            padding: 36px 32px 30px 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all 0.3s ease;
        }

        /* Centered Boxed Logo */
        .logo-box-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-frame-box {
            width: 76px;
            height: 76px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        }

        .logo-frame-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .login-brand-title {
            font-weight: 800;
            font-size: 20px;
            color: #0f172a;
            margin-top: 12px;
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        .login-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
            margin: 4px 0 0 0;
        }

        /* Form Controls */
        .form-group-custom {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-label-custom {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
            letter-spacing: 0.01em;
        }

        .input-custom-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 15px;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .input-custom {
            width: 100%;
            height: 48px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 0 42px 0 38px;
            font-size: 14px;
            color: #0f172a;
            background: #ffffff;
            transition: all 0.2s ease;
            outline: none;
            font-weight: 500;
        }

        .input-custom:focus {
            border-color: #ea580c;
            box-shadow: 0 0 0 3.5px rgba(234, 88, 12, 0.16);
        }

        .input-custom-wrapper:focus-within .input-icon {
            color: #ea580c;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 15px;
            padding: 6px;
            outline: none;
            transition: color 0.15s ease;
        }

        .password-toggle-btn:hover {
            color: #1e293b;
        }

        /* Modern Gradient Submit Button */
        .btn-login-submit {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #ea580c 0%, #f97316 100%) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 10px !important;
            font-size: 15.5px !important;
            font-weight: 700 !important;
            letter-spacing: 0.01em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.35);
            transition: all 0.2s ease;
            margin-top: 24px;
        }

        .btn-login-submit:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 8px 24px rgba(234, 88, 12, 0.45);
            color: #ffffff !important;
        }

        .btn-login-submit:active {
            transform: scale(0.99);
        }

        .login-footer-copy {
            text-align: center;
            margin-top: 22px;
            font-size: 11.5px;
            color: #64748b;
            font-weight: 600;
        }
    </style>
</head>
<body class="login-page">
    
    <!-- Background Video with AutoPlay, Loop & Muted -->
    <video autoplay muted loop playsinline preload="auto" class="bg-video-element">
        <source src="{{ asset('bg.mp4') }}" type="video/mp4">
    </video>

    <!-- Ambient Dark Overlay -->
    <div class="video-overlay"></div>

    @yield('login')

    <!-- jQuery 3 -->
    <script src="{{ asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
