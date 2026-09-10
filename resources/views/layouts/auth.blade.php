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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-2/dist/css/AdminLTE.min.css') }}">
    
    <!-- Restaurant Modern CSS -->
    <link rel="stylesheet" href="{{ asset('css/restaurant-modern.css') }}">

    <style>
        body.login-page {
            background: radial-gradient(circle at 20% 20%, #1e293b 0%, #0f172a 100%) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            padding: 20px;
        }
        .login-box {
            width: 440px;
            margin: 0 auto;
        }
        .login-box-body {
            background: #ffffff;
            border-radius: 16px;
            padding: 32px 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .login-logo {
            margin-bottom: 20px;
        }
        .login-logo h3 {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin: 10px 0 2px 0;
            letter-spacing: -0.02em;
        }
        .login-logo p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        .form-control-icon-wrapper {
            position: relative;
            margin-bottom: 16px;
        }
        .form-control-icon-wrapper input {
            height: 46px;
            border-radius: 10px;
            padding-left: 42px;
            border: 1px solid #cbd5e1;
            font-size: 13.5px;
        }
        .form-control-icon-wrapper i {
            position: absolute;
            left: 14px;
            top: 14px;
            color: #94a3b8;
            font-size: 16px;
        }
        .btn-restaurant-login {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
            color: #ffffff !important;
            border: none !important;
            height: 46px;
            border-radius: 10px !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            width: 100%;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
            transition: all 0.2s ease;
        }
        .btn-restaurant-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(249, 115, 22, 0.45);
        }
        .quick-demo-pill {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 10px 12px;
            margin-top: 20px;
            font-size: 12px;
            color: #64748b;
        }
        .quick-demo-btn {
            display: inline-block;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 3px 8px;
            border-radius: 6px;
            color: #0f172a;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
            transition: all 0.15s ease;
        }
        .quick-demo-btn:hover {
            border-color: #f97316;
            color: #f97316;
        }
    </style>
</head>
<body class="hold-transition login-page">
    
    @yield('login')

    <!-- jQuery 3 -->
    <script src="{{ asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Validator -->
    <script src="{{ asset('js/validator.min.js') }}"></script>
    <script>
        $('.form-login').validator();
    </script>
</body>
</html>
