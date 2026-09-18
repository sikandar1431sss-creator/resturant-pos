<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $setting->nama_perusahaan ?? 'Restaurant POS' }} | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="icon" href="{{ url($setting->path_logo ?? 'img/logo.png') }}" type="image/png">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/dist/css/skins/_all-skins.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    
    <!-- Modern Restaurant UI Overhaul Stylesheet -->
    <link rel="stylesheet" href="{{ asset('/css/restaurant-modern.css') }}?v={{ time() }}">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('css')
</head>
<body class="hold-transition skin-black fixed">
    <div class="wrapper">

        @includeIf('layouts.header')

        @includeIf('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    @yield('title')
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        @includeIf('layouts.footer')
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="{{ asset('AdminLTE-2/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('AdminLTE-2/bower_components/moment/min/moment.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('AdminLTE-2/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE-2/dist/js/adminlte.min.js') }}"></script>
    <!-- Validator -->
    <script src="{{ asset('js/validator.min.js') }}"></script>

    <script>
        function preview(selector, temporaryFile, width = 200)  {
            $(selector).empty();
            $(selector).append(`<img src="${window.URL.createObjectURL(temporaryFile)}" width="${width}">`);
        }

        // Live Real-Time Clock updater
        function updateLiveClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            $('.live-time-display').text(timeStr);
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // Strict Button-Only Sidebar Toggle (No hover auto-open/close)
        $(function() {
            // Disable AdminLTE hover expansion plugin feature completely
            if ($.AdminLTE && $.AdminLTE.pushMenu) {
                $.AdminLTE.pushMenu.options = $.AdminLTE.pushMenu.options || {};
                $.AdminLTE.pushMenu.options.expandOnHover = false;
            }

            // Restore user's manual preference if explicitly toggled
            var savedSidebar = localStorage.getItem('app_sidebar_collapsed');
            if (savedSidebar === 'true') {
                $('body').addClass('sidebar-collapse');
            } else {
                $('body').removeClass('sidebar-collapse');
            }

            $(document).on('click', '[data-toggle="push-menu"]', function(e) {
                setTimeout(function() {
                    var isCollapsed = $('body').hasClass('sidebar-collapse');
                    localStorage.setItem('app_sidebar_collapsed', isCollapsed ? 'true' : 'false');
                }, 150);
            });
        });
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Custom Styled SweetAlert Toast
        const SwalToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Global Alert Helper functions
        function showSuccessToast(message = 'Action completed successfully!') {
            SwalToast.fire({
                icon: 'success',
                title: message
            });
        }

        function showErrorToast(message = 'Something went wrong!') {
            SwalToast.fire({
                icon: 'error',
                title: message
            });
        }

        function showWarningToast(message) {
            SwalToast.fire({
                icon: 'warning',
                title: message
            });
        }

        function showConfirmDialog(title, text, confirmBtnText, onConfirmCallback, icon = 'warning') {
            Swal.fire({
                title: title || 'Are you sure?',
                text: text || "You won't be able to revert this!",
                icon: icon,
                showCancelButton: true,
                confirmButtonText: confirmBtnText || 'Yes, proceed!',
                cancelButtonText: 'Cancel',
                reverseButtons: false,
                focusCancel: true,
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (typeof onConfirmCallback === 'function') {
                        onConfirmCallback();
                    }
                }
            });
        }

        @if (session()->has('success'))
            showSuccessToast("{{ session('success') }}");
        @endif
        @if (session()->has('error'))
            showErrorToast("{{ session('error') }}");
        @endif
        @if (session()->has('warning'))
            showWarningToast("{{ session('warning') }}");
        @endif
    </script>
    @stack('scripts')
</body>
</html>
