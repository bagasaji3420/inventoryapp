<!-- Menu -->

@php $sidebarSettings = \App\Models\Settings::current(); @endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                @if ($sidebarSettings->logo)
                    <img src="{{ asset('storage/' . $sidebarSettings->logo) }}" alt="Logo"
                        style="max-width: 32px; max-height: 32px; object-fit: contain;">
                @else
                    <span class="text-primary">
                        <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <defs>
                                <path
                                    d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                    id="path-1"></path>
                                <path
                                    d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                    id="path-3"></path>
                                <path
                                    d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                                    id="path-4"></path>
                                <path
                                    d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                                    id="path-5"></path>
                            </defs>
                            <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                    <g id="Icon" transform="translate(27.000000, 15.000000)">
                                        <g id="Mask" transform="translate(0.000000, 8.000000)">
                                            <mask id="mask-2" fill="white">
                                                <use xlink:href="#path-1"></use>
                                            </mask>
                                            <use fill="currentColor" xlink:href="#path-1"></use>
                                            <g id="Path-3" mask="url(#mask-2)">
                                                <use fill="currentColor" xlink:href="#path-3"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                            </g>
                                            <g id="Path-4" mask="url(#mask-2)">
                                                <use fill="currentColor" xlink:href="#path-4"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                            </g>
                                        </g>
                                        <g id="Triangle"
                                            transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                            <use fill="currentColor" xlink:href="#path-5"></use>
                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                @endif
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">{{ $sidebarSettings->nama_perusahaan ?? config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base bx bx-chevron-left"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    @php
        $isDataMaster = request()->routeIs('suppliers.*', 'customers.*', 'item-types.*', 'units.*', 'unit-conversions.*', 'items.*');
        $isTransaksi = request()->routeIs('stock-ins.*', 'stock-outs.*', 'delivery-notes.*');
        $isInventoryMenu = request()->routeIs('stock-opnames.*', 'stock-cards.*');
        $isPenjualan = request()->routeIs('sales.*');
        $isUsers = request()->routeIs('users.*');
        $isRoles = request()->routeIs('roles.*', 'permissions.*');
        $isLogs = request()->routeIs('activity-logs');
    @endphp

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        @can('dashboard.read')
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-home-smile"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>
        @endcan

        @canany(['suppliers.read', 'customers.read', 'item-types.read', 'units.read', 'items.read', 'stock-ins.read', 'stock-outs.read', 'delivery-notes.read', 'stock-opnames.read', 'stock-cards.read', 'sales.read'])
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="Main">Main</span>
            </li>
        @endcanany

        @canany(['suppliers.read', 'customers.read', 'item-types.read', 'units.read', 'items.read'])
            <li class="menu-item {{ $isDataMaster ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base bx bx-archive"></i>
                    <div data-i18n="Data Master">Data Master</div>
                </a>

                <ul class="menu-sub">
                    @can('suppliers.read')
                        <li class="menu-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                            <a href="{{ route('suppliers.index') }}" class="menu-link">
                                <div data-i18n="Supplier">Supplier</div>
                            </a>
                        </li>
                    @endcan

                    @can('customers.read')
                        <li class="menu-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <a href="{{ route('customers.index') }}" class="menu-link">
                                <div data-i18n="Pelanggan">Pelanggan</div>
                            </a>
                        </li>
                    @endcan

                    @can('item-types.read')
                        <li class="menu-item {{ request()->routeIs('item-types.*') ? 'active' : '' }}">
                            <a href="{{ route('item-types.index') }}" class="menu-link">
                                <div data-i18n="Jenis Barang">Jenis Barang</div>
                            </a>
                        </li>
                    @endcan

                    @can('units.read')
                        <li class="menu-item {{ request()->routeIs('units.*') ? 'active' : '' }}">
                            <a href="{{ route('units.index') }}" class="menu-link">
                                <div data-i18n="Satuan">Satuan</div>
                            </a>
                        </li>
                    @endcan

                    @can('items.read')
                        <li class="menu-item {{ request()->routeIs('items.*') ? 'active' : '' }}">
                            <a href="{{ route('items.index') }}" class="menu-link">
                                <div data-i18n="Data Barang">Data Barang</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['stock-ins.read', 'stock-outs.read', 'delivery-notes.read'])
            <li class="menu-item {{ $isTransaksi ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base bx bx-receipt"></i>
                    <div data-i18n="Transaksi">Transaksi</div>
                </a>

                <ul class="menu-sub">
                    @can('stock-ins.read')
                        <li class="menu-item {{ request()->routeIs('stock-ins.*') ? 'active' : '' }}">
                            <a href="{{ route('stock-ins.index') }}" class="menu-link">
                                <div data-i18n="Barang Masuk">Barang Masuk</div>
                            </a>
                        </li>
                    @endcan

                    @can('stock-outs.read')
                        <li class="menu-item {{ request()->routeIs('stock-outs.*') ? 'active' : '' }}">
                            <a href="{{ route('stock-outs.index') }}" class="menu-link">
                                <div data-i18n="Barang Keluar">Barang Keluar</div>
                            </a>
                        </li>
                    @endcan

                    @can('delivery-notes.read')
                        <li class="menu-item {{ request()->routeIs('delivery-notes.*') ? 'active' : '' }}">
                            <a href="{{ route('delivery-notes.index') }}" class="menu-link">
                                <div data-i18n="Surat Jalan">Surat Jalan</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['stock-opnames.read', 'stock-cards.read'])
            <li class="menu-item {{ $isInventoryMenu ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base bx bx-package"></i>
                    <div data-i18n="Inventory">Inventory</div>
                </a>

                <ul class="menu-sub">
                    @can('stock-opnames.read')
                        <li class="menu-item {{ request()->routeIs('stock-opnames.*') ? 'active' : '' }}">
                            <a href="{{ route('stock-opnames.index') }}" class="menu-link">
                                <div data-i18n="Stok Opname">Stok Opname</div>
                            </a>
                        </li>
                    @endcan

                    @can('stock-cards.read')
                        <li class="menu-item {{ request()->routeIs('stock-cards.*') ? 'active' : '' }}">
                            <a href="{{ route('stock-cards.index') }}" class="menu-link">
                                <div data-i18n="Kartu Stok">Kartu Stok</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @can('sales.read')
            <li class="menu-item {{ $isPenjualan ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base bx bx-cart"></i>
                    <div data-i18n="Penjualan">Penjualan</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ $isPenjualan ? 'active' : '' }}">
                        <a href="{{ route('sales.index') }}" class="menu-link">
                            <div data-i18n="Kasir Penjualan">Kasir Penjualan</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @canany(['users.read', 'roles.read', 'permissions.read', 'logs.read'])
            <!-- Apps & Pages -->
            <li class="menu-header small">
                <span class="menu-header-text" data-i18n="System">System</span>
            </li>
        @endcan

        @canany(['users.read', 'users.create', 'users.update', 'users.delete'])
            <li class="menu-item {{ $isUsers ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base bx bx-user"></i>
                    <div data-i18n="Users">Users</div>
                </a>

                <ul class="menu-sub">
                    @can('users.read')
                        <li class="menu-item {{ $isUsers ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="menu-link">
                                <div data-i18n="List">List</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        @canany(['roles.read', 'permissions.read', 'roles.read'])
            <li class="menu-item {{ $isRoles ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base bx bx-check-shield"></i>
                    <div>Roles</div>
                </a>

                <ul class="menu-sub">

                    @can('roles.read')
                        <li class="menu-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}" class="menu-link">
                                <div>Roles</div>
                            </a>
                        </li>
                    @endcan

                    @can('permissions.read')
                        <li class="menu-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                            <a href="{{ route('permissions.index') }}" class="menu-link">
                                <div>Permission</div>
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcanany


        @canany(['logs.read'])

            <li class="menu-item {{ $isLogs ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base bx bx-box"></i>
                    <div>Logs</div>
                </a>

                <ul class="menu-sub">

                    @can('logs.read')
                        <li class="menu-item {{ $isLogs ? 'active' : '' }}">
                            <a href="{{ route('activity-logs') }}" class="menu-link">
                                <div>Activity</div>
                            </a>
                        </li>
                    @endcan

                    @hasrole('developer')
                        <li class="menu-item">
                            <a href="/log-viewer" target="_blank" class="menu-link">
                                <div>App</div>
                            </a>
                        </li>
                    @endhasrole

                </ul>
            </li>
        @endcanany


        @can('settings.read')
            <li class="menu-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <a href="{{ route('settings.codes') }}" class="menu-link">
                    <i class="menu-icon icon-base bx bx-cog"></i>
                    <div data-i18n="Settings">Settings</div>
                </a>
            </li>
        @endcan



    </ul>
</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
        <i class="bx bx-menu icon-base"></i>
        <i class="bx bx-chevron-right icon-base"></i>
    </a>
</div>
<!-- / Menu -->
