<aside class="main-sidebar" style="padding-top: 60px !important;">
    <section class="sidebar">
        <!-- Sidebar menu -->
        <ul class="sidebar-menu" data-widget="tree" style="padding-top: 15px; margin-top: 5px;">
            <li class="sidebar-dashboard-btn {{ request()->is('dashboard*') ? 'active' : '' }}" style="margin: 0 10px 12px 10px;">
                <a href="{{ route('dashboard') }}" style="border-radius: 10px; padding: 12px 14px;">
                    <i class="fa fa-th-large" style="color: #f97316;"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="header">POS & ORDERS</li>
            <li class="{{ request()->is('transaksi/baru*') ? 'active' : '' }}">
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-bolt" style="color: #f97316;"></i> <span>Fast POS Register</span>
                </a>
            </li>
            <li class="{{ request()->is('transaksi') || (request()->is('transaksi/*') && !request()->is('transaksi/baru*')) ? 'active' : '' }}">
                <a href="{{ route('transaksi.index') }}">
                    <i class="fa fa-shopping-basket"></i> <span>Active Orders</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
            <li class="{{ request()->is('penjualan*') ? 'active' : '' }}">
                <a href="{{ route('penjualan.index') }}">
                    <i class="fa fa-list-alt"></i> <span>Order History</span>
                </a>
            </li>

            <li class="header">MENU & DINING</li>
            <li class="{{ request()->is('produk*') ? 'active' : '' }}">
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cutlery"></i> <span>Food Menu & Dishes</span>
                </a>
            </li>
            <li class="{{ request()->is('kategori*') ? 'active' : '' }}">
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-tags"></i> <span>Menu Categories</span>
                </a>
            </li>

            <li class="header">CUSTOMERS & SUPPLIERS</li>
            <li class="{{ request()->is('member*') ? 'active' : '' }}">
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card-o"></i> <span>Diners & VIP Members</span>
                </a>
            </li>
            <li class="{{ request()->is('supplier*') ? 'active' : '' }}">
                <a href="{{ route('supplier.index') }}">
                    <i class="fa fa-truck"></i> <span>Food Suppliers</span>
                </a>
            </li>

            <li class="header">INVENTORY & FINANCE</li>
            <li class="{{ request()->is('pengeluaran*') ? 'active' : '' }}">
                <a href="{{ route('pengeluaran.index') }}">
                    <i class="fa fa-credit-card"></i> <span>Daily Expenses</span>
                </a>
            </li>
            <li class="{{ request()->is('pembelian*') ? 'active' : '' }}">
                <a href="{{ route('pembelian.index') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Inventory Purchases</span>
                </a>
            </li>

            <li class="header">ANALYTICS & REPORTS</li>
            <li class="{{ request()->is('laporan*') ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-line-chart"></i> <span>Income & Sales Report</span>
                </a>
            </li>

            <li class="header">ADMINISTRATION</li>
            <li class="{{ request()->is('user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users"></i> <span>Staff & Waiters</span>
                </a>
            </li>
            <li class="{{ request()->is('setting*') ? 'active' : '' }}">
                <a href="{{ route('setting.index') }}">
                    <i class="fa fa-sliders"></i> <span>Restaurant Settings</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
</aside>