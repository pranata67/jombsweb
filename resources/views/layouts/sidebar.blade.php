@if (Auth::user()->level_user == '12' || Auth::user()->level_user == '5')
    <nav id="sidebar" aria-label="Main Navigation" style="background-color: #000">
@else
    <nav id="sidebar" aria-label="Main Navigation">
@endif
<!-- Side Header -->
@if (Auth::user()->level_user == '12' || Auth::user()->level_user == '5')
    <div class="bg-header-darks" style="background-color: #d9361c">
        <div class="content-header bg-white-5">
        <!-- Logo -->
        <a class="fw-semibold text-white tracking-wide">
            <span class="">BPN - Kab. JOMBANG</span>
        </a>
        <!-- END Logo -->
        </div>
    </div>
@else
    <div class="bg-header-dark">
        <div class="content-header bg-white-5">
        <!-- Logo -->
        <a class="fw-semibold text-white tracking-wide">
            <span class="">BPN - Kab. JOMBANG</span>
        </a>
        <!-- END Logo -->
        </div>
    </div>
@endif
<!-- END Side Header -->

<!-- Sidebar Scrolling -->
<div class="js-sidebar-scrolls">
    <!-- Side Navigation -->
    <div class="content-side">
        <ul class="nav-main">
            <li class="nav-main-heading">Menu Utama</li>
            <li class="nav-main-item">
                <a class="nav-main-link {{ $data['menuActive'] == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="nav-main-link-icon fa fa-gauge"></i>
                    <span class="nav-main-link-name">Dashboard</span>
                    {{-- <span class="nav-main-link-badge badge rounded-pill bg-primary">12</span> --}}
                </a>
            </li>
            <li class="nav-main-heading">Pengaturan Halaman</li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ $data['menuActive'] == 'registrasi' ? 'active' : '' }}" href="{{ route('main-registrasi') }}">
                        <i class="nav-main-link-icon fa fa-people-group"></i>
                        <span class="nav-main-link-name">Data Register</span>
                    </a>
                </li>
                {{-- @if (Auth::user()->level_user == '1' || Auth::user()->level_user == '10')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ $data['menuActive'] == 'keperluan' ? 'active' : '' }}" href="{{ route('main-keperluan') }}">
                            <i class="nav-main-link-icon fa fa-folder-tree"></i>
                            <span class="nav-main-link-name">Data Keperluan</span>
                        </a>
                    </li>
                @endif --}}
                {{-- @if (Auth::user()->level_user == '11' || Auth::user()->level_user == '3' || Auth::user()->level_user == '4' || Auth::user()->level_user == '2') || Auth::user()->level_user == '4' --}}
                @if (Auth::user()->level_user == '1')
                <li class="nav-main-item">
                    <a
                    class="nav-main-link
                    {{ $data['menuActive'] == 'registrasi-dikerjakan' ? 'active' : '' }}
                    "
                    href="{{ route('main-registrasi-dikerjakan') }}"
                    >
                        <i class="nav-main-link-icon fa fa-people-group"></i>
                        <span class="nav-main-link-name">Register Diproses</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->level_user == '1' || Auth::user()->level_user == '10' || Auth::user()->level_user == '11') {{-- || Auth::user()->level_user == '11' --}}
                    <li class="nav-main-item">
                        <a
                        class="nav-main-link
                        {{ $data['menuActive'] == 'registrasi-ditolak' ? 'active' : '' }}
                        "
                        href="{{ route('main-registrasi-ditolak') }}"
                        >
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Register Ditolak</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->level_user == '2')
                    <li class="nav-main-item">
                        <a
                        class="nav-main-link
                        {{ $data['menuActive'] == 'validasi-bidang' ? 'active' : '' }}
                        "
                        href="{{ route('main-validasi-bidang') }}"
                        >
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Validasi Bidang</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->level_user == '11' || Auth::user()->level_user == '3' || Auth::user()->level_user == '2') {{--  || Auth::user()->level_user == '4' --}}
                <li class="nav-main-item">
                    <a
                    class="nav-main-link
                    {{ $data['menuActive'] == 'registrasi-dikembalikan' ? 'active' : '' }}
                    "
                    href="{{ route('main-registrasi-dikembalikan') }}"
                    >
                        <i class="nav-main-link-icon fa fa-people-group"></i>
                        <span class="nav-main-link-name">Berkas Kembali</span>
                    </a>
                </li>
                @endif
                {{-- @if (Auth::user()->level_user == '1' || Auth::user()->level_user == '10')
                    <li class="nav-main-item">
                        <a
                        class="nav-main-link
                        {{ $data['menuActive'] == 'operator-bpn' ? 'active' : '' }}
                        "
                        href="{{ route('main-operator-bpn') }}"
                        >
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Operator BPN</span>
                        </a>
                    </li>
                @endif --}}
                @if (Auth::user()->level_user == '1' || Auth::user()->level_user == '10' || Auth::user()->level_user == '11')
                    <li class="nav-main-item">
                        <a
                        class="nav-main-link
                        {{ $data['menuActive'] == 'petugas-verifikator' ? 'active' : '' }}
                        "
                        href="{{ route('main-petugas-verifikator') }}"
                        >
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Petugas Verifikator</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->level_user == '1' || Auth::user()->level_user == '10' || Auth::user()->level_user == '3')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ $data['menuActive'] == 'pemetaan' ? 'active' : '' }}" href="{{ route('main-petugas-pemetaan') }}">
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Petugas Pemetaan</span>
                        </a>
                    </li>
                @endif
                @if ( Auth::user()->level_user == '1' || Auth::user()->level_user == '10' || Auth::user()->level_user == '2') {{-- Auth::user()->level_user == '2' || --}}
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ $data['menuActive'] == 'petugas-lapang' ? 'active' : '' }}" href="{{ route('main-petugas-lapangan') }}">
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Petugas Lapang</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->level_user == '1' || Auth::user()->level_user == '10' || Auth::user()->level_user == '4' )
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ $data['menuActive'] == 'petugas-su-el' ? 'active' : '' }}" href="{{ route('main-petugas-su-el') }}">
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Petugas SU EL</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->level_user == '10' || Auth::user()->level_user == '5' || Auth::user()->level_user == '12')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ $data['menuActive'] == 'registrasi-selesai' ? 'active' : '' }}" href="{{ route('main-registrasi-selesai') }}">
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Sudah Dikerjakan</span>
                        </a>
                    </li>
                @endif
                @if (#Auth::user()->level_user == '5' ||
                Auth::user()->level_user == '1' || Auth::user()->level_user == '10' || Auth::user()->level_user == '5')
                    {{-- <li class="nav-main-item">
                        <a class="nav-main-link {{ $data['menuActive'] == 'petugas-bt-el' ? 'active' : '' }}" href="{{ route('main-petugas-bt-el') }}">
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Petugas BT EL</span>
                        </a>
                    </li> --}}
                @endif
                @if (#Auth::user()->level_user == '5' ||
                Auth::user()->level_user == '1' || Auth::user()->level_user == '10' || Auth::user()->level_user == '12')
                    {{-- <li class="nav-main-item">
                        <a class="nav-main-link {{ $data['menuActive'] == 'petugas-bt' ? 'active' : '' }}" href="{{ route('main-petugas-bt') }}">
                            <i class="nav-main-link-icon fa fa-people-group"></i>
                            <span class="nav-main-link-name">Petugas BT</span>
                        </a>
                    </li> --}}
                @endif
                {{-- @if (#Auth::user()->level_user == '6' ||
                Auth::user()->level_user == '1' || Auth::user()->level_user == '10')
                @if (Auth::user()->level_user == '6') {{-- || Auth::user()->level_user == '1' || Auth::user()->level_user == '10' --}}
                    {{-- <li class="nav-main-item">
                        <a class="nav-main-link {{ $data['menuActive'] == 'petugas-registrasi' ? 'active' : '' }}" href="{{ route('main-petugas-registrasi') }}">
                            <i class="nav-main-link-icon fa fa-person"></i>
                            <span class="nav-main-link-name">Petugas Registrasi</span>
                        </a>
                    </li> --}}
                {{-- @endif --}}
                <li class="nav-main-item">
                    <a class="nav-main-link {{ $data['menuActive'] == 'pengaturan' ? 'active' : '' }}" href="{{ route('main-pengaturan') }}">
                        <i class="nav-main-link-icon fa fa-gears"></i>
                        <span class="nav-main-link-name">Pengaturan</span>
                    </a>
                </li>
        </ul>
    </div>
    <!-- END Side Navigation -->
</div>
<!-- END Sidebar Scrolling -->
</nav>
