<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Sekolah - PAUD ATTHOHIRIYYAH</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Kenali visi, misi, dan program pendidikan di PAUD ATTHOHIRIYYAH. Kami berdedikasi mencetak generasi cerdas dan berakhlak.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/Logopaud.png') }}">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .page-header {
            background: linear-gradient(rgba(3, 79, 32, 0.9), rgba(3, 79, 32, 0.9)), url('{{ asset('img/Banner01.jpeg') }}');
            background-size: cover;
            background-position: center;
            padding: 6rem 0;
            color: white;
            text-align: center;
        }
        .page-header h1 { color: white !important; font-size: 3rem; font-weight: 800; }
        .profile-section { padding: 5rem 0; }
        .vimi-card { background: white; padding: 3rem; border-radius: 30px; box-shadow: var(--shadow); margin-bottom: 2rem; }
    </style>
</head>
<body>
    @include('pages.partials.navbar')

    <header class="page-header">
        <div class="container">
            <h1>Profil Sekolah</h1>
            <p>Mengenal lebih dekat PAUD ATTHOHIRIYYAH</p>
        </div>
    </header>

    <main class="container profile-section">
        <div class="vimi-card">
            <h2 style="color: var(--primary); margin-bottom: 1.5rem;">Visi & Misi</h2>
            <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-dark);">
                <strong>Visi:</strong> Mewujudkan generasi Robbani yang cerdas, kreatif, mandiri, dan berakhlakul karimah.<br><br>
                <strong>Misi:</strong>
                <ul style="margin-left: 20px;">
                    <li>Membiasakan anak beribadah dan berperilaku santun sesuai tuntunan agama.</li>
                    <li>Mengembangkan potensi anak melalui kegiatan bermain yang edukatif.</li>
                    <li>Membangun kemandirian anak dalam kehidupan sehari-hari.</li>
                    <li>Menjalin kemitraan yang harmonis dengan orang tua dan masyarakat.</li>
                </ul>
            </p>
        </div>

        <section id="program" style="padding: 4rem 0;">
             <h2 class="section-title">Program Pembelajaran</h2>
             <div class="info-grid">
                <div class="info-card">
                    <i class="fas fa-shapes fa-3x" style="color: var(--secondary);"></i>
                    <h3>Kelompok Bermain</h3>
                    <p>Fokus pada perkembangan motorik dan sosial anak usia 2-4 tahun.</p>
                </div>
                <div class="info-card">
                    <i class="fas fa-graduation-cap fa-3x" style="color: var(--primary);"></i>
                    <h3>Taman Kanak-kanak</h3>
                    <p>Persiapan menuju jenjang SD dengan metode Kurikulum Merdeka.</p>
                </div>
             </div>
        </section>

        <section id="fasilitas" style="padding: 4rem 0;">
            <h2 class="section-title">Fasilitas Sekolah</h2>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 3rem;">Kami menyediakan fasilitas yang aman dan mendukung tumbuh kembang anak.</p>
            <div class="gallery-grid">
                <div class="gallery-item"><img src="{{ asset('img/Kelompok-Bermain.jpg') }}"></div>
                <div class="gallery-item"><img src="{{ asset('img/Kurikulum.jpg') }}"></div>
            </div>
        </section>
    </main>

    @include('pages.partials.footer')
</body>
</html>
