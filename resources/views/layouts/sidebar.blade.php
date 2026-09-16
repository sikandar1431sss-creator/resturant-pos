<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar menu -->
        <ul class="sidebar-menu" data-widget="tree" style="padding-top: 15px; margin-top: 5px;">
            <li class="sidebar-dashboard-btn {{ request()->is('dashboard*') ? 'active' : '' }}" style="margin: 0 10px 12px 10px;">
                <a href="{{ route('dashboard') }}" style="border-radius: 10px; padding: 12px 14px;">
                    <i class="fa fa-th-large" style="color: #f97316;"></i> <span>Dashboard</span>
                </a>
            </li>

            {{-- FAST FOOD POS & BILLING --}}
            @if(auth()->user()->can('pos.access') || auth()->user()->can('sales.view_all') || auth()->user()->can('sales.view_own') || auth()->user()->can('kitchen.access') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="header">FAST FOOD POS &amp; BILLING</li>

            @if(auth()->user()->can('pos.access') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('transaksi*') ? 'active' : '' }}">
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-shopping-cart" style="color: #10b981;"></i> <span>Create Invoice (POS)</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('sales.view_all') || auth()->user()->can('sales.view_own') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('penjualan*') ? 'active' : '' }}">
                <a href="{{ route('penjualan.index') }}">
                    <i class="fa fa-list-alt" style="color: #0284c7;"></i> <span>Invoices &amp; Orders</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('kitchen.access') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('kitchen*') ? 'active' : '' }}">
                <a href="{{ route('kitchen.index') }}">
                    <i class="fa fa-cutlery" style="color: #ea580c;"></i> <span>Kitchen Display (KDS)</span>
                </a>
            </li>
            @endif
            @endif

            {{-- MENU & DEALS --}}
            @if(auth()->user()->can('products.view') || auth()->user()->can('deals.view') || auth()->user()->can('categories.view') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="header">MENU &amp; DEALS</li>
            
            @if(auth()->user()->can('deals.view') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('deal*') ? 'active' : '' }}">
                <a href="{{ route('deal.index') }}">
                    <i class="fa fa-tags"></i> <span>Deals &amp; Combos</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('products.view') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('produk*') ? 'active' : '' }}">
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-th-large"></i> <span>Menu Items</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('categories.view') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('kategori*') ? 'active' : '' }}">
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-tags"></i> <span>Menu Categories</span>
                </a>
            </li>
            @endif
            @endif

            {{-- PURCHASES & SUPPLIERS --}}
            @if(auth()->user()->can('purchases.view') || auth()->user()->can('suppliers.manage') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="header">PURCHASES &amp; SUPPLIERS</li>
            
            @if(auth()->user()->can('purchases.view') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('pembelian*') ? 'active' : '' }}">
                <a href="{{ route('pembelian.index') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Purchases (Stock-In)</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('suppliers.manage') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('supplier*') ? 'active' : '' }}">
                <a href="{{ route('supplier.index') }}">
                    <i class="fa fa-truck"></i> <span>Suppliers</span>
                </a>
            </li>
            @endif
            @endif

            {{-- EXPENSES & CUSTOMERS --}}
            @if(auth()->user()->can('expenses.view') || auth()->user()->can('members.manage') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="header">EXPENSES &amp; CUSTOMERS</li>

            @if(auth()->user()->can('expenses.view') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('pengeluaran*') ? 'active' : '' }}">
                <a href="{{ route('pengeluaran.index') }}">
                    <i class="fa fa-money" style="color: #ef4444;"></i> <span>Daily Expenses</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('members.manage') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('member*') ? 'active' : '' }}">
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-users"></i> <span>Customer Loyalty</span>
                </a>
            </li>
            @endif
            @endif

            {{-- ANALYTICS & REPORTS --}}
            @if(auth()->user()->can('reports.view') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="header">ANALYTICS &amp; REPORTS</li>
            <li class="{{ request()->is('laporan/penjualan*') ? 'active' : '' }}">
                <a href="{{ route('laporan.penjualan') }}">
                    <i class="fa fa-shopping-cart" style="color: #10b981;"></i> <span>Sales Report</span>
                </a>
            </li>
            <li class="{{ request()->is('laporan/pembelian*') ? 'active' : '' }}">
                <a href="{{ route('laporan.pembelian') }}">
                    <i class="fa fa-truck" style="color: #0284c7;"></i> <span>Purchase Report</span>
                </a>
            </li>
            <li class="{{ (request()->is('laporan') || (request()->is('laporan*') && !request()->is('laporan/penjualan*') && !request()->is('laporan/pembelian*'))) ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-line-chart" style="color: #f59e0b;"></i> <span>Income &amp; Profit Report</span>
                </a>
            </li>
            @endif

            {{-- ADMINISTRATION --}}
            @if(auth()->user()->can('users.manage') || auth()->user()->can('settings.manage') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="header">ADMINISTRATION</li>
            
            @if(auth()->user()->can('users.manage') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users" style="color: #3b82f6;"></i> <span>Staff Users</span>
                </a>
            </li>
            <li class="{{ request()->is('role*') ? 'active' : '' }}">
                <a href="{{ route('role.index') }}">
                    <i class="fa fa-shield" style="color: #ea580c;"></i> <span>Roles &amp; Permissions</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('settings.manage') || auth()->user()->hasRole('admin') || auth()->user()->level == 1)
            <li class="{{ request()->is('setting*') ? 'active' : '' }}">
                <a href="{{ route('setting.index') }}">
                    <i class="fa fa-sliders" style="color: #8b5cf6;"></i> <span>Restaurant Settings</span>
                </a>
            </li>
            @endif
            @endif
        </ul>
    </section>
</aside>