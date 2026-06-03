    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="/" class="logo">
                <img src="{{ asset('img/Logopaud.png') }}" alt="Logo">
                PAUD ATTHOHIRIYYAH
            </a>
            <ul class="nav-links">
                <li><a href="/">BERANDA</a></li>
                <li class="nav-item">
                    <a href="{{ route('profil') }}">PROFIL <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 5px;"></i></a>
                    <ul class="dropdown">
                        <li><a href="/sambutan">Sambutan Kepala Sekolah</a></li>
                        <li><a href="{{ route('profil') }}#program">Program Pembelajaran</a></li>
                        <li><a href="{{ route('profil') }}#fasilitas">Fasilitas Sekolah</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('galeri') }}">GALERI</a></li>
                <li><a href="{{ route('media') }}">MEDIA</a></li>
                <li><a href="{{ route('informasi') }}">INFORMASI</a></li>
                <li><a href="{{ route('kontak') }}">KONTAK</a></li>
                @php
                    $pmbYear = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'pmb_year')->value('value') ?? '2026/2027';
                @endphp
                <li><a href="/ppdb" style="color: var(--secondary); font-weight: 800;">PMB {{ $pmbYear }}</a></li>
                <li style="margin-left: 0.5rem; color: #eee;">|</li>
                @auth
                <li class="nav-item">
                    <a href="/dashboard" class="btn-login">Dashboard <i class="fas fa-user-circle"></i></a>
                    <ul class="dropdown" style="left: auto; right: 0; transform: translateY(10px);">
                        <li><a href="/dashboard"><i class="fas fa-tachometer-alt"></i> Panel Utama</a></li>
                        <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
                    </ul>
                    <form id="logout-form" action="/auth/logout" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                @else
                <li class="nav-item">
                    <a href="/login" class="btn-login">Log In <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown" style="left: auto; right: 0; transform: translateY(10px);">
                        <li><a href="/login?role=admin"><i class="fas fa-user-shield"></i> <span>Login Admin</span></a></li>
                        <li><a href="/login?role=guru"><i class="fas fa-chalkboard-teacher"></i> <span>Login Guru</span></a></li>
                        <li><a href="/login?role=orang-tua"><i class="fas fa-users"></i> <span>Login Orang Tua</span></a></li>
                    </ul>
                </li>
                @endauth
            </ul>
        </div>
    </nav>
