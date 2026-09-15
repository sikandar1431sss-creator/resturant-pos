<aside class="main-sidebar" style="padding-top: 60px !important;">
    <section class="sidebar">
        <!-- Sidebar menu -->
        <ul class="sidebar-menu" data-widget="tree" style="padding-top: 15px; margin-top: 5px;">
            <li class="sidebar-dashboard-btn {{ request()->is('dashboard*') ? 'active' : '' }}" style="margin: 0 10px 12px 10px;">
                <a href="{{ route('dashboard') }}" style="border-radius: 10px; padding: 12px 14px;">
                    <i class="fa fa-th-large" style="color: #f97316;"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="header">FAST FOOD POS & BILLING</li>
            <li class="{{ request()->is('transaksi*') ? 'active' : '' }}">
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-shopping-cart" style="color: #10b981;"></i> <span>Create Invoice</span>
                </a>
            </li>
            <li class="{{ request()->is('penjualan*') ? 'active' : '' }}">
                <a href="{{ route('penjualan.index') }}">
                    <i class="fa fa-list-alt" style="color: #0284c7;"></i> <span>Invoices</span>
                </a>
            </li>

            @if(auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="header">MENU & DEALS</li>
            <li class="{{ request()->is('deal*') ? 'active' : '' }}">
                <a href="{{ route('deal.index') }}">
                    <i class="fa fa-gift" style="color: #f59e0b;"></i> <span>Deals</span>
                </a>
            </li>
            <li class="{{ request()->is('produk*') ? 'active' : '' }}">
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-th-large"></i> <span>Menu Items</span>
                </a>
            </li>
            <li class="{{ request()->is('kategori*') ? 'active' : '' }}">
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-tags"></i> <span>Menu Categories</span>
                </a>
            </li>

            <li class="header">PURCHASES &amp; SUPPLIERS</li>
            <li class="{{ request()->is('pembelian*') ? 'active' : '' }}">
                <a href="{{ route('pembelian.index') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Purchases (Stock-In)</span>
                </a>
            </li>
            <li class="{{ request()->is('supplier*') ? 'active' : '' }}">
                <a href="{{ route('supplier.index') }}">
                    <i class="fa fa-truck"></i> <span>Suppliers</span>
                </a>
            </li>

            <li class="header">EXPENSES & CUSTOMERS</li>
            <li class="{{ request()->is('pengeluaran*') ? 'active' : '' }}">
                <a href="{{ route('pengeluaran.index') }}">
                    <i class="fa fa-money" style="color: #ef4444;"></i> <span>Daily Expenses</span>
                </a>
            </li>
            <li class="{{ request()->is('member*') ? 'active' : '' }}">
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-users"></i> <span>Customers</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="header">ANALYTICS & REPORTS</li>
            <li class="{{ request()->is('laporan*') ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-line-chart" style="color: #059669;"></i> <span>Sales & Profit Reports</span>
                </a>
            </li>

            <li class="header">ADMINISTRATION</li>
            <li class="{{ request()->is('user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-user-circle-o"></i> <span>Staff & Roles (Spatie)</span>
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