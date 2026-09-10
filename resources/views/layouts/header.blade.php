<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        @php
            $words = explode(' ', $setting->nama_perusahaan ?? 'Restaurant');
            $word  = '';
            foreach ($words as $w) {
                if (!empty($w)) $word .= $w[0];
            }
        @endphp
        <span class="logo-mini"><i class="fa fa-cutlery"></i></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><i class="fa fa-cutlery text-orange" style="color: #f97316; margin-right: 6px;"></i> <b>{{ $setting->nama_perusahaan ?? 'Restaurant POS' }}</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle navigation</span>
        </a>

        <!-- Center Meta / Live Shift Indicator -->
        <div class="hidden-xs header-restaurant-meta">
            <div class="shift-status-pill">
                <span class="pulse-dot"></span>
                <span>Live Service</span>
            </div>
            <a href="{{ route('transaksi.baru') }}" class="btn-quick-pos">
                <i class="fa fa-bolt"></i> Fast POS Order
            </a>
        </div>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ url(auth()->user()->foto ?? 'img/user.jpg') }}" class="user-image img-profil"
                            alt="User Image" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=f97316&color=fff'">
                        <span class="hidden-xs user-name">{{ auth()->user()->name ?? 'Administrator' }}</span>
                        <i class="fa fa-angle-down" style="font-size: 12px; color: #94a3b8; margin-left: 4px;"></i>
                    </a>
                    <ul class="dropdown-menu" style="border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden; padding: 0;">
                        <!-- User image -->
                        <li class="user-header" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); height: auto; padding: 20px 15px; border-bottom: 1px solid #fed7aa;">
                            <img src="{{ url(auth()->user()->foto ?? 'img/user.jpg') }}" class="img-circle img-profil"
                                alt="User Image" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=f97316&color=fff'" style="border: 2px solid #f97316;">
                            <p style="color: #0f172a; margin-top: 10px; font-weight: 700; font-size: 15px;">
                                {{ auth()->user()->name ?? 'Admin' }}
                                <small style="color: #64748b; display: block; margin-top: 4px;">{{ auth()->user()->email ?? '' }}</small>
                                <span class="label" style="background: #f97316; color: #ffffff; font-size: 11px; margin-top: 6px; display: inline-block; font-weight: 600;">
                                    {{ auth()->user()->level == 1 ? 'Administrator' : 'Cashier / Staff' }}
                                </span>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer" style="background: #ffffff; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <a href="{{ route('user.profil') }}" class="btn btn-default btn-flat" style="border-radius: 6px !important; font-size: 12px; font-weight: 600;">
                                    <i class="fa fa-user"></i> My Profile
                                </a>
                            </div>
                            <div>
                                <a href="#" class="btn btn-danger btn-flat" style="border-radius: 6px !important; font-size: 12px; font-weight: 600; background: #ef4444; border-color: #ef4444;"
                                    onclick="event.preventDefault(); $('#logout-form').submit();">
                                    <i class="fa fa-power-off"></i> Logout
                                </a>
                            </div>
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