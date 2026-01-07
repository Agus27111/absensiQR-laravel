<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{ auth()->user()->sekolah && auth()->user()->sekolah->logo
                ? asset('storage/' . auth()->user()->sekolah->logo)
                : asset('img/AdminLTELogo.png') }}"
                class="brand-image img-circle elevation-3"
                style="opacity: .8; width: 33px; height: 33px; object-fit: cover;">
        </div>
        <div class="info">
            @if (auth()->user()->super_admin)
                <a href="#" class="d-block"><strong>Super Admin</strong></a>
                <small class="text-muted">Global Monitoring</small>
            @else
                <a href="#" class="d-block">{{ auth()->user()->sekolah->nama_sekolah ?? 'Nama Sekolah' }}</a>
                <small class="text-success"><i class="bi bi-circle-fill" style="font-size: 8px"></i> Online</small>
            @endif
        </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="/beranda"
                    class="nav-link {{ $title === 'Beranda' || $title === 'Dashboard Admin' ? 'active' : '' }}">
                    <i class="bi bi-house-fill"></i>
                    <p class="pl-1">
                        Beranda
                    </p>
                </a>
            </li>

            {{-- Show Scan QR only for non-super-admin --}}
            @if (!auth()->user()->super_admin)
                <li class="nav-item">
                    <a href="/scan-qr" class="nav-link {{ $title === 'Scan QR' ? 'active' : '' }}">
                        <i class="bi bi-qr-code-scan"></i>
                        <p class="pl-1">
                            Scan QR
                        </p>
                    </a>
                </li>
            @endif

            @can('admin')
                {{-- Murid Menu - visible to both super admin and tenant admin --}}
                <li
                    class="nav-item {{ $title === 'Daftar Murid' ? 'menu-open' : '' }}{{ $title === 'Input Murid' ? 'menu-open' : '' }}{{ $title === 'Detail Murid' ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ $title === 'Daftar Murid' ? 'active' : '' }}{{ $title === 'Input Murid' ? 'active' : '' }}{{ $title === 'Detail Murid' ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <p class="pl-1">
                            Murid
                            <i class="bi bi-caret-down-fill right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/daftar-murid"
                                class="nav-link {{ $title === 'Daftar Murid' ? 'active' : '' }}{{ $title === 'Detail Murid' ? 'active' : '' }}">
                                <p>Daftar Murid</p>
                            </a>
                        </li>
                        {{-- Input Murid - only for tenant admin --}}
                        @if (!auth()->user()->super_admin)
                            <li class="nav-item">
                                <a href="/input-murid" class="nav-link {{ $title === 'Input Murid' ? 'active' : '' }}">
                                    <p>Input Murid</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>

                {{-- Kelas Menu - only for tenant admin --}}
                @if (!auth()->user()->super_admin)
                    <li class="nav-item {{ $title === 'Daftar Kelas' ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $title === 'Daftar Kelas' ? 'active' : '' }}">
                            <i class="bi bi-collection-fill"></i>
                            <p class="pl-1">
                                Kelas
                                <i class="bi bi-caret-down-fill right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/kelas/daftar" class="nav-link {{ $title === 'Daftar Kelas' ? 'active' : '' }}">
                                    <p>Daftar Kelas</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- Tahun Menu - visible to both super admin and tenant admin --}}
                <li class="nav-item {{ $title === 'Data Tahun' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $title === 'Data Tahun' ? 'active' : '' }}">
                        <i class="bi bi-card-heading"></i>
                        <p class="pl-1">
                            Tahun
                            <i class="bi bi-caret-down-fill right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/tahun" class="nav-link {{ $title === 'Data Tahun' ? 'active' : '' }}">
                                <p>Daftar Tahun</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Pengaturan Menu - only for tenant admin --}}
                @if (!auth()->user()->super_admin)
                    <li class="nav-item">
                        <a href="/pengaturan" class="nav-link {{ $title === 'Pengaturan' ? 'active' : '' }}">
                            <i class="bi bi-gear-fill"></i>
                            <p class="pl-1">
                                Pengaturan
                            </p>
                        </a>
                    </li>
                @endif
            @endcan

            <hr>
            <div class="mt-4 ml-4">
                <form action="/keluar" method="post">
                    @csrf
                    <p>
                        <button class="btn btn-outline-danger" type="submit">Keluar</button>
                    </p>
                </form>
            </div>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
