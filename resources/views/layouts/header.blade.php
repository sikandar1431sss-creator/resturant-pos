<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            @if(!empty($setting->path_logo) && file_exists(public_path($setting->path_logo)))
                <img src="{{ url($setting->path_logo) }}" alt="{{ $setting->nama_perusahaan ?? 'Logo' }}" style="max-height: 32px; max-width: 32px; object-fit: contain; vertical-align: middle; border-radius: 6px;">
            @else
                <i class="fa fa-cutlery" style="color: #f97316; font-size: 18px;"></i>
            @endif
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="display: flex; align-items: center; justify-content: center; gap: 8px; height: 100%;">
            @if(!empty($setting->path_logo) && file_exists(public_path($setting->path_logo)))
                <img src="{{ url($setting->path_logo) }}" alt="{{ $setting->nama_perusahaan ?? 'Logo' }}" style="max-height: 38px; max-width: 150px; object-fit: contain; vertical-align: middle; border-radius: 6px;">
            @else
                <i class="fa fa-cutlery" style="color: #f97316; font-size: 18px;"></i>
                <b style="font-weight: 800; color: #0f172a; font-size: 15.5px; letter-spacing: -0.02em;">{{ $setting->nama_perusahaan ?? 'Restaurant POS' }}</b>
            @endif
        </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu" style="margin-right: 18px;">
            <ul class="nav navbar-nav">
                <!-- User Account Dropdown -->
                <li class="dropdown user user-menu" style="display: flex; align-items: center;">
                    @php
                        $userName = auth()->user()->name ?? 'Admin';
                        $words = preg_split('/\s+/', trim($userName));
                        if (count($words) >= 2) {
                            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($userName, 0, 2));
                        }
                    @endphp

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display: flex; align-items: center; gap: 10px; background: transparent; border: none; cursor: pointer; text-decoration: none; padding: 4px 8px; border-radius: 8px;" title="{{ auth()->user()->name ?? 'Account' }}">
                        @if(!empty(auth()->user()->foto) && file_exists(public_path(auth()->user()->foto)))
                            <img src="{{ url(auth()->user()->foto) }}" alt="User Avatar" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; box-shadow: none;">
                        @else
                            <div style="width: 44px; height: 44px; border-radius: 50%; background: #3b82f6; color: #ffffff; font-weight: 800; font-size: 15px; display: flex; align-items: center; justify-content: center; box-shadow: none; border: 2px solid #e2e8f0; letter-spacing: 0.02em;">
                                {{ $initials }}
                            </div>
                        @endif
                        <span class="hidden-xs" style="font-weight: 700; font-size: 14px; color: #0f172a; letter-spacing: -0.01em;">{{ auth()->user()->name ?? 'Administrator' }}</span>
                        <i class="fa fa-angle-down hidden-xs" style="font-size: 13px; color: #94a3b8; margin-left: 2px;"></i>
                    </a>

                    <!-- Account Dropdown Menu -->
                    <ul class="dropdown-menu" style="width: 210px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden; padding: 6px 0; margin-top: 8px; right: 0; left: auto; background: #ffffff;">
                        <!-- Heading -->
                        <li style="padding: 10px 18px 4px 18px;">
                            <span style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em;">ACCOUNT</span>
                        </li>

                        <!-- Profile Link -->
                        <li style="padding: 2px 8px;">
                            <a href="{{ route('user.profil') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 8px; font-size: 14px; font-weight: 500; color: #334155; text-decoration: none; transition: all 0.15s ease;">
                                <i class="fa fa-user" style="font-size: 15px; color: #94a3b8; width: 18px; text-align: center;"></i>
                                <span>My Profile</span>
                            </a>
                        </li>

                        @if(auth()->user()->can('settings.manage') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
                        <li style="padding: 2px 8px;">
                            <a href="{{ route('setting.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 8px; font-size: 14px; font-weight: 500; color: #334155; text-decoration: none; transition: all 0.15s ease;">
                                <i class="fa fa-sliders" style="font-size: 15px; color: #94a3b8; width: 18px; text-align: center;"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        @endif

                        <li class="divider" style="margin: 4px 0; border-top: 1px solid #f1f5f9;"></li>

                        <!-- Logout Link -->
                        <li style="padding: 2px 8px;">
                            <a href="#" style="display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 8px; font-size: 14px; font-weight: 500; color: #334155; text-decoration: none; transition: all 0.15s ease;"
                               onclick="event.preventDefault(); $('#logout-form').submit();">
                                <i class="fa fa-sign-out" style="font-size: 16px; color: #ea580c; width: 18px; text-align: center;"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
    @csrf
</form>