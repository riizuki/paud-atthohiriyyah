<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sambutan Kepala Sekolah - PAUD ATTHOHIRIYYAH</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .page-header {
            background: linear-gradient(rgba(3, 79, 32, 0.9), rgba(3, 79, 32, 0.9)), url('{{ asset('img/Banner01.jpeg') }}');
            background-size: cover;
            background-position: center;
            padding: 8rem 0 12rem;
            color: white;
            text-align: center;
        }
        .page-header h1 {
            color: white !important;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        .page-header p {
            color: rgba(255,255,255,0.9);
            font-size: clamp(1rem, 2vw, 1.3rem);
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .content-card {
            background: white;
            border-radius: 50px;
            padding: clamp(2rem, 5vw, 5rem);
            margin-top: -100px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
            margin-bottom: 5rem;
            position: relative;
            z-index: 5;
        }
        .principal-bio {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 50px;
            margin-bottom: 4rem;
            align-items: start;
        }
        .principal-img-wrapper {
            position: relative;
        }
        .principal-img-wrapper::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: -20px;
            bottom: -20px;
            border: 3px solid var(--secondary);
            border-radius: 30px;
            z-index: -1;
        }
        .principal-bio img {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            object-position: top;
            border-radius: 30px;
            box-shadow: var(--shadow);
        }
        .principal-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        }
        @media (max-width: 992px) {
            .principal-bio {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .principal-img-wrapper {
                max-width: 400px;
                margin: 0 auto 3rem;
            }
        }
    </style>
</head>
<body>

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
                <li><a href="/ppdb" style="color: var(--secondary); font-weight: 800;">PMB 2026/2027</a></li>
                <li style="margin-left: 0.5rem; color: #eee;">|</li>
                <li class="nav-item">
                    <a href="/login" class="btn-login">Log In <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown" style="left: auto; right: 0; transform: translateY(10px);">
                        <li><a href="/login?role=admin"><i class="fas fa-user-shield"></i> <span>Login Admin</span></a></li>
                        <li><a href="/login?role=guru"><i class="fas fa-chalkboard-teacher"></i> <span>Login Guru</span></a></li>
                        <li><a href="/login?role=siswa"><i class="fas fa-user-graduate"></i> <span>Login Siswa</span></a></li>
                        <li><a href="/login?role=orang-tua"><i class="fas fa-users"></i> <span>Login Orang Tua</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <header class="page-header">
        <div class="container">
            <h1>Sambutan Kepala Sekolah</h1>
            <p>PAUD ATTHOHIRIYYAH CIANJUR</p>
        </div>
    </header>

    <main class="container">
        <div class="content-card">
            <div class="principal-bio">
                <div class="principal-img-wrapper">
                    <img src="{{ asset('img/Kepala-Sekolah.jpeg') }}" alt="Leni Mulyani, S.Pd.">
                </div>
                <div class="principal-text">
                    <h2 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--primary);">Leni Mulyani, S.Pd.</h2>
                    <p style="color: var(--secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.5rem;">Kepala Sekolah PAUD ATTHOHIRIYYAH</p>
                    <div style="background: var(--background); padding: 2rem; border-left: 5px solid var(--secondary); border-radius: 0 20px 20px 0;">
                        <p style="font-size: 1.1rem; color: var(--text-dark); line-height: 1.8; font-style: italic; margin: 0;">
                            "Bermain adalah cara tertinggi dari sebuah penelitian. Mari kita biarkan anak-anak tumbuh dengan kebahagiaan dan rasa ingin tahu yang besar."
                        </p>
                    </div>
                </div>
            </div>

            <div style="font-size: 1.15rem; line-height: 2; color: var(--text-dark);">
                <p style="margin-bottom: 1.5rem;">Assalamu'alaikum warahmatullahi wabarakatuh,</p>
                
                <p style="margin-bottom: 1.5rem;">
                    Segala puji bagi Allah subhanahu wa ta'ala, shalawat serta salam tercurah kepada Nabi Muhammad salallahu alaihi wasalam beserta keluarganya. Selamat datang di portal resmi PAUD ATTHOHIRIYYAH.
                </p>

                <p style="margin-bottom: 1.5rem;">
                    Kami percaya bahwa setiap anak adalah unik dan memiliki potensi yang luar biasa. Di PAUD ATTHOHIRIYYAH, kami berkomitmen untuk menyediakan lingkungan belajar yang merangsang kreativitas, rasa ingin tahu, dan karakter yang kuat. Melalui pendekatan Montessori yang kami padukan dengan Kurikulum Merdeka, kami membantu anak-anak untuk mandiri dan mencintai proses belajar.
                </p>

                <p style="margin-bottom: 1.5rem;">
                    Pendidikan di masa usia dini merupakan pondasi terpenting bagi masa depan anak. Oleh karena itu, kami tidak hanya fokus pada kecerdasan intelektual, tetapi juga pada kecerdasan emosional dan spiritual. Kami mengajak Bapak/Ibu sekalian untuk bersama-sama bersinergi dalam membimbing tunas-tunas bangsa ini.
                </p>

                <p style="margin-bottom: 1.5rem;">
                    Semoga website ini dapat menjadi jembatan informasi yang bermanfaat bagi kita semua. Terima kasih atas kepercayaan yang Bapak/Ibu berikan kepada kami.
                </p>

                <p style="margin-bottom: 3rem;">Wassalamu'alaikum warahmatullahi wabarakatuh.</p>

                <div style="text-align: right;">
                    <p>Hormat kami,</p>
                    <br><br>
                    <p><strong>Leni Mulyani, S.Pd.</strong></p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom" style="margin-top: 0; padding-top: 0; border-top: none;">
                <p>&copy; 2026 PAUD ATTHOHIRIYYAH. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

</body>
</html>
