<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUD ATTHOHIRIYYAH - Mencetak Generasi Cerdas & Berakhlak</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-slider { position: relative; height: 80vh; overflow: hidden; }
        .slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease; background-size: cover; background-position: center; display: flex; align-items: center; }
        .slide.active { opacity: 1; }
        .slide::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); }
        .greeting-section { background: white; border-radius: 50px; overflow: hidden; display: flex; box-shadow: var(--shadow); margin-top: -100px; position: relative; z-index: 10; }
        .greeting-img { flex: 1; min-height: 400px; background-size: cover; background-position: center top; border-right: 8px solid var(--secondary); }
        .greeting-content { flex: 1.5; padding: 4rem; }
        @media (max-width: 992px) { .greeting-section { flex-direction: column; margin-top: 0; } }
    </style>
</head>
<body>
    @include('pages.partials.navbar')

    <!-- Hero Slider -->
    <div class="hero-slider">
        <div class="slide active" style="background-image: url('{{ asset('img/Banner01.jpeg') }}')">
            <div class="container">
                <div class="hero-content" style="color: white;">
                    <h1 style="color: white; text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">Mencetak Generasi Cerdas & Berakhlak</h1>
                    <p style="color: white; text-shadow: 1px 1px 5px rgba(0,0,0,0.5);">Lingkungan belajar yang aman, nyaman, dan menyenangkan.</p>
                    <a href="/ppdb" class="btn btn-primary">Daftar Sekarang</a>
                </div>
            </div>
        </div>
        <div class="slide" style="background-image: url('{{ asset('img/Banner0.jpeg') }}')">
            <div class="container">
                <div class="hero-content" style="color: white;">
                    <h1 style="color: white;">Fasilitas Lengkap</h1>
                    <p style="color: white;">Mendukung kreativitas dan eksplorasi anak.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Sambutan Section -->
        <div class="greeting-section">
            <div class="greeting-img" style="background-image: url('{{ asset('img/Kepala-Sekolah.jpeg') }}')"></div>
            <div class="greeting-content">
                <h4 style="color: var(--secondary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1rem;">Sambutan Kepala Sekolah</h4>
                <h2 style="margin-bottom: 1.5rem;">Leni Mulyani, S.Pd.</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">
                    Assalamu'alaikum warahmatullahi wabarakatuh. Segala puji bagi Allah subhanahu wa ta'ala, shalawat serta salam tercurah kepada Nabi Muhammad salallahu alaihi wasalam...
                </p>
                <a href="/sambutan" class="news-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Modern Statistics -->
    <section style="background: white; padding: 4rem 0;">
        <div class="container">
            <div style="background: var(--primary); border-radius: 40px; padding: 5rem 2rem; position: relative; overflow: hidden; box-shadow: 0 20px 50px rgba(3, 79, 32, 0.2);">
                <!-- Decorative background circles -->
                <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -30px; left: 10%; width: 120px; height: 120px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
                
                <div class="stats-grid" style="position: relative; z-index: 1; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px;">
                    <div class="stat-card" style="text-align: center; color: white;">
                        <div style="background: rgba(255,255,255,0.1); width: 70px; height: 70px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid rgba(255,255,255,0.2);">
                            <i class="fas fa-user-tie" style="font-size: 1.8rem; color: var(--secondary);"></i>
                        </div>
                        <h2 style="font-size: 3.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem; font-family: 'Quicksand';">{{ $stats['teachers'] }}</h2>
                        <p style="font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.8;">Guru & Staf</p>
                    </div>
                    
                    <div class="stat-card" style="text-align: center; color: white;">
                        <div style="background: rgba(255,255,255,0.1); width: 70px; height: 70px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid rgba(255,255,255,0.2);">
                            <i class="fas fa-user-graduate" style="font-size: 1.8rem; color: var(--secondary);"></i>
                        </div>
                        <h2 style="font-size: 3.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem; font-family: 'Quicksand';">{{ $stats['students'] }}</h2>
                        <p style="font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.8;">Siswa Terdaftar</p>
                    </div>

                    <div class="stat-card" style="text-align: center; color: white;">
                        <div style="background: rgba(255,255,255,0.1); width: 70px; height: 70px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; border: 1px solid rgba(255,255,255,0.2);">
                            <i class="fas fa-door-open" style="font-size: 1.8rem; color: var(--secondary);"></i>
                        </div>
                        <h2 style="font-size: 3.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem; font-family: 'Quicksand';">{{ $stats['rombel'] }}</h2>
                        <p style="font-size: 0.9rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.8;">Rombongan Belajar</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brief Information -->
    <section style="background: white; padding: 6rem 0;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3.5rem;">
                <div>
                    <h4 style="color: var(--secondary); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem;">Update Terbaru</h4>
                    <h2 style="font-size: 2.5rem; margin: 0;">Berita Terkini</h2>
                </div>
                <a href="{{ route('informasi') }}" class="btn btn-secondary" style="border-radius: 12px; padding: 0.8rem 2rem;">Lihat Semua <i class="fas fa-arrow-right" style="margin-left: 10px; font-size: 0.8rem;"></i></a>
            </div>

            <div class="info-grid">
                @foreach($information as $info)
                <div class="news-card" style="border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border-radius: 20px; transition: var(--transition);">
                    <div class="news-content" style="padding: 2.5rem;">
                        <span style="color: var(--text-muted); font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 1rem;">{{ $info->created_at->format('d M Y') }}</span>
                        <h3 class="news-title" style="font-size: 1.5rem; line-height: 1.4; margin-bottom: 1.5rem; min-height: 4.2rem;">{{ $info->title }}</h3>
                        <a href="/information/{{ $info->slug }}" style="color: var(--primary); text-decoration: none; font-weight: 800; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; transition: gap 0.3s ease;">
                            Baca Selengkapnya <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Upcoming Agenda -->
    @if(isset($agendas) && !$agendas->isEmpty())
    <section style="background: #f8fafc; padding: 6rem 0;">
        <div class="container">
            <h2 class="section-title">Agenda Sekolah</h2>
            <div class="info-grid">
                @foreach($agendas as $agenda)
                <div class="agenda-item" style="background: white; border-radius: 20px; padding: 2rem; display: flex; gap: 20px; align-items: center; border: 1px solid #f1f5f9;">
                    <div style="background: var(--primary); color: white; padding: 1rem; border-radius: 15px; text-align: center; min-width: 90px;">
                        <span style="font-size: 1.5rem; font-weight: 800; display: block;">{{ \Carbon\Carbon::parse($agenda->start_date)->format('d') }}</span>
                        <span style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">{{ \Carbon\Carbon::parse($agenda->start_date)->format('M') }}</span>
                    </div>
                    <div>
                        <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">{{ $agenda->title }}</h3>
                        <p style="color: var(--text-muted); font-size: 0.9rem;"><i class="fas fa-clock" style="margin-right: 8px;"></i> {{ \Carbon\Carbon::parse($agenda->start_date)->format('H:i') }} WIB</p>
                        <p style="color: var(--text-muted); font-size: 0.9rem;"><i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i> {{ $agenda->location ?? 'Gedung PAUD' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('pages.partials.footer')

    <script>
        const slides = document.querySelectorAll('.slide');
        let currentSlide = 0;
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5000);
    </script>
</body>
</html>
