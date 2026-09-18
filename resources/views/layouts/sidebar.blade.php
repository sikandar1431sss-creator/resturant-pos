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
            @if(auth()->user()->can('pos.access') || auth()->user()->can('sales.view_all') || auth()->user()->can('sales.view_own') || auth()->user()->can('sales.view') || auth()->user()->can('kitchen.access') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="header">FAST FOOD POS &amp; BILLING</li>

            @if(auth()->user()->can('pos.access') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('transaksi*') ? 'active' : '' }}">
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-shopping-cart" style="color: #10b981;"></i> <span>New Invoice</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('sales.view_all') || auth()->user()->can('sales.view_own') || auth()->user()->can('sales.view') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('penjualan*') ? 'active' : '' }}">
                <a href="{{ route('penjualan.index') }}">
                    <i class="fa fa-list-alt" style="color: #0284c7;"></i> <span>Invoices</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('kitchen.access') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('kitchen*') ? 'active' : '' }}">
                <a href="{{ route('kitchen.index') }}">
                    <i class="fa fa-fire" style="color: #ea580c;"></i> <span>Kitchen Orders</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('tables.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('meja*') ? 'active' : '' }}">
                <a href="{{ route('meja.index') }}">
                    <i class="fa fa-cutlery" style="color: #3b82f6;"></i> <span>Seating</span>
                </a>
            </li>
            @endif
            @endif

            {{-- MENU, DEALS & RECIPES --}}
            @if(auth()->user()->can('products.view') || auth()->user()->can('deals.view') || auth()->user()->can('categories.view') || auth()->user()->can('recipes.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="header">MENU &amp; RECIPES</li>
            
            @if(auth()->user()->can('deals.view') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('deal*') ? 'active' : '' }}">
                <a href="{{ route('deal.index') }}">
                    <i class="fa fa-tags" style="color: #ec4899;"></i> <span>Deals</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('products.view') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('produk*') ? 'active' : '' }}">
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-th-large" style="color: #8b5cf6;"></i> <span>Menu Items</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('categories.view') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('kategori*') ? 'active' : '' }}">
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-tags" style="color: #06b6d4;"></i> <span>Menu Categories</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('recipes.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('recipe*') ? 'active' : '' }}">
                <a href="{{ route('recipe.index') }}">
                    <i class="fa fa-book" style="color: #0284c7;"></i> <span>Recipes</span>
                </a>
            </li>
            @endif
            @endif

            {{-- INVENTORY, PURCHASES & SUPPLIERS --}}
            @if(auth()->user()->can('raw_materials.view') || auth()->user()->can('purchases.view') || auth()->user()->can('suppliers.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="header">INVENTORY &amp; PURCHASES</li>

            @if(auth()->user()->can('raw_materials.view') || auth()->user()->can('raw_materials.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('raw_material*') ? 'active' : '' }}">
                <a href="{{ route('raw_material.index') }}">
                    <i class="fa fa-cubes" style="color: #10b981;"></i> <span>Raw Stock</span>
                </a>
            </li>
            @endif
            
            @if(auth()->user()->can('purchases.view') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('pembelian*') ? 'active' : '' }}">
                <a href="{{ route('pembelian.index') }}">
                    <i class="fa fa-cart-arrow-down" style="color: #f59e0b;"></i> <span>Purchases</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('suppliers.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('supplier*') || request()->is('ledger/supplier*') ? 'active' : '' }}">
                <a href="{{ route('supplier.index') }}">
                    <i class="fa fa-truck" style="color: #64748b;"></i> <span>Suppliers</span>
                </a>
            </li>
            @endif
            @endif

            {{-- EXPENSES & CUSTOMERS --}}
            @if(auth()->user()->can('expenses.view') || auth()->user()->can('members.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="header">EXPENSES &amp; CUSTOMERS</li>

            @if(auth()->user()->can('expenses.view') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('pengeluaran*') ? 'active' : '' }}">
                <a href="{{ route('pengeluaran.index') }}">
                    <i class="fa fa-money" style="color: #ef4444;"></i> <span>Expenses</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('members.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('member*') || request()->is('ledger/customer*') ? 'active' : '' }}">
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-users" style="color: #6366f1;"></i> <span>Customers</span>
                </a>
            </li>
            @endif
            @endif

            {{-- ANALYTICS & REPORTS --}}
            @if(auth()->user()->can('reports.view') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
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
                    <i class="fa fa-line-chart" style="color: #f59e0b;"></i> <span>Profit Report</span>
                </a>
            </li>
            @endif

            {{-- ADMINISTRATION --}}
            @if(auth()->user()->can('users.manage') || auth()->user()->can('settings.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="header">ADMINISTRATION</li>
            
            @if(auth()->user()->can('users.manage') || auth()->user()->hasAnyRole(['admin', 'manager']) || auth()->user()->level == 1)
            <li class="{{ request()->is('user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users" style="color: #3b82f6;"></i> <span>Staff</span>
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