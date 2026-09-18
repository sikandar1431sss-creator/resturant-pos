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
            font-family: 'Inter', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body.login-page {
            min-height: 100vh;
            margin: 0;
            padding: 20px 10px;
            background: url('{{ asset('img/login-bg.jpg') }}') no-repeat center center fixed !important;
            background-size: cover !important;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Subtle ambient vignette overlay */
        body.login-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.08);
            pointer-events: none;
        }

        .login-card-container {
            width: 440px;
            max-width: 94vw;
            position: relative;
            z-index: 2;
            margin: 10px auto;
        }

        .login-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 34px 34px 28px 34px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.35), 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* Centered Boxed Logo */
        .logo-box-wrapper {
            text-align: center;
            margin-bottom: 8px;
        }

        .logo-frame-box {
            width: 76px;
            height: 76px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .logo-frame-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .login-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 13.5px;
            font-weight: 500;
            margin: 10px 0 24px 0;
        }

        /* Form Controls */
        .form-group-custom {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-label-custom {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-custom-wrapper {
            position: relative;
        }

        .input-custom {
            width: 100%;
            height: 46px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            padding: 0 14px;
            font-size: 14px;
            color: #0f172a;
            background: #ffffff;
            transition: all 0.2s ease;
            outline: none;
        }

        .input-custom:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3.5px rgba(22, 163, 74, 0.15);
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 13px;
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 15px;
            padding: 0;
            outline: none;
            transition: color 0.15s ease;
        }

        .password-toggle-btn:hover {
            color: #334155;
        }

        /* Green Submit Button matching the reference screenshot */
        .btn-green-submit {
            width: 100%;
            height: 48px;
            background: #1b8a53 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 9px !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(27, 138, 83, 0.35);
            transition: all 0.2s ease;
            margin-top: 22px;
        }

        .btn-green-submit:hover {
            background: #157343 !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(27, 138, 83, 0.45);
            color: #ffffff !important;
        }

        .btn-green-submit:active {
            transform: scale(0.99);
        }

        /* Quick Demo Credentials Panel */
        .quick-credentials-panel {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 12px;
            margin-top: 22px;
        }

        .quick-cred-heading {
            font-size: 11.5px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .quick-role-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
        }

        .quick-role-chip {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 7px 8px;
            font-size: 11.5px;
            font-weight: 600;
            color: #1e293b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.15s ease;
            user-select: none;
        }

        .quick-role-chip:hover {
            border-color: #16a34a;
            color: #16a34a;
            background: #f0fdf4;
            transform: translateY(-1px);
        }

        .quick-role-chip.active-chip {
            border-color: #16a34a;
            background: #dcfce7;
            color: #15803d;
            font-weight: 700;
        }

        .pwd-hint-banner {
            font-size: 11px;
            color: #64748b;
            text-align: center;
            margin-top: 8px;
        }
    </style>
</head>
<body class="login-page">
    
    @yield('login')

    <!-- jQuery 3 -->
    <script src="{{ asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
