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

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Dropdown -->
                <li class="dropdown user user-menu" style="display: flex; align-items: center;">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display: flex; align-items: center; gap: 10px; padding: 6px 12px; margin: 0; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; transition: all 0.2s ease;">
                        @if(!empty(auth()->user()->foto) && file_exists(public_path(auth()->user()->foto)))
                            <img src="{{ url(auth()->user()->foto) }}" class="user-image" alt="User Image" style="width: 34px; height: 34px; border-radius: 8px; object-fit: cover; border: 1.5px solid #f97316; margin: 0;">
                        @else
                            <div style="width: 34px; height: 34px; border-radius: 8px; background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%); color: #ea580c; font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; border: 1px solid #fdba74;">
                                {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                            </div>
                        @endif
                        <div class="hidden-xs" style="text-align: left; line-height: 1.25;">
                            <span class="user-name" style="font-weight: 700; font-size: 13px; color: #0f172a; display: block;">{{ auth()->user()->name ?? 'Administrator' }}</span>
                            <span style="font-size: 10.5px; font-weight: 700; color: #ea580c; text-transform: uppercase; letter-spacing: 0.03em;">{{ auth()->user()->level == 1 ? 'Administrator' : (auth()->user()->getRoleNames()->first() ?? 'Staff') }}</span>
                        </div>
                        <i class="fa fa-angle-down" style="font-size: 12px; color: #94a3b8; margin-left: 2px;"></i>
                    </a>
                    <ul class="dropdown-menu" style="width: 270px; border-radius: 12px; box-shadow: 0 12px 30px rgba(0,0,0,0.12); border: 1px solid #e2e8f0; overflow: hidden; padding: 0; margin-top: 6px; right: 0; left: auto;">
                        <!-- Header / User Info -->
                        <li style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 18px 16px; color: #ffffff;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if(!empty(auth()->user()->foto) && file_exists(public_path(auth()->user()->foto)))
                                    <img src="{{ url(auth()->user()->foto) }}" style="width: 46px; height: 46px; border-radius: 10px; object-fit: cover; border: 2px solid #f97316;">
                                @else
                                    <div style="width: 46px; height: 46px; border-radius: 10px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #ffffff; font-weight: 800; font-size: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(249,115,22,0.3);">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                                    </div>
                                @endif
                                <div style="overflow: hidden;">
                                    <div style="font-weight: 800; font-size: 14px; color: #ffffff; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                        {{ auth()->user()->name ?? 'Administrator' }}
                                    </div>
                                    <div style="font-size: 11.5px; color: #94a3b8; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                        {{ auth()->user()->email ?? '' }}
                                    </div>
                                    <span style="display: inline-block; background: rgba(249,115,22,0.2); color: #fb923c; border: 1px solid rgba(249,115,22,0.3); font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px; margin-top: 5px; text-transform: uppercase;">
                                        {{ auth()->user()->level == 1 ? 'Administrator' : (auth()->user()->getRoleNames()->first() ?? 'Staff') }}
                                    </span>
                                </div>
                            </div>
                        </li>

                        <!-- Menu Body / Action Links -->
                        <li style="background: #ffffff; padding: 8px 10px;">
                            <a href="{{ route('user.profil') }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #334155; text-decoration: none; transition: background 0.15s ease;">
                                <i class="fa fa-user-circle-o" style="font-size: 15px; color: #f97316; width: 18px; text-align: center;"></i>
                                <span>My Profile</span>
                            </a>
                            @if(auth()->user()->hasRole('admin') || auth()->user()->level == 1)
                            <a href="{{ route('setting.index') }}" style="display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #334155; text-decoration: none; transition: background 0.15s ease;">
                                <i class="fa fa-sliders" style="font-size: 15px; color: #0284c7; width: 18px; text-align: center;"></i>
                                <span>Restaurant Settings</span>
                            </a>
                            @endif
                        </li>

                        <!-- Footer / Logout Button -->
                        <li style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 10px 14px; display: flex; justify-content: flex-end;">
                            <a href="#" class="btn btn-danger btn-sm btn-flat" style="border-radius: 6px !important; font-size: 12px; font-weight: 700; background: #ef4444; border-color: #ef4444; width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;"
                                onclick="event.preventDefault(); $('#logout-form').submit();">
                                <i class="fa fa-power-off"></i> Logout
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