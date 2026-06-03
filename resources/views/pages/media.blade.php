<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media & Artikel - PAUD ATTHOHIRIYYAH</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Kumpulan artikel, tips parenting, dan informasi edukatif dari PAUD ATTHOHIRIYYAH.">
    <meta name="keywords" content="Artikel PAUD, Parenting, Tips Pendidikan Anak, Media ATTHOHIRIYYAH">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/Logopaud.png') }}">
    
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .page-header {
            background: linear-gradient(rgba(3, 79, 32, 0.9), rgba(3, 79, 32, 0.9)), url('{{ asset('img/Banner0.jpeg') }}');
            background-size: cover;
            background-position: center;
            padding: 6rem 0;
            color: white;
            text-align: center;
            margin-bottom: 4rem;
        }
        .page-header h1 { color: white !important; font-size: 3rem; font-weight: 800; }
    </style>
</head>
<body>
    @include('pages.partials.navbar')

    <header class="page-header">
        <div class="container">
            <h1>Media & Artikel</h1>
            <p>Eksplorasi artikel dan karya edukatif kami</p>
        </div>
    </header>

    <main class="container" style="margin-bottom: 5rem;">
        <div class="info-grid">
            @foreach($articles as $article)
            <div class="news-card">
                <div class="news-content">
                    <span class="news-category">{{ $article->category ?? 'Artikel' }}</span>
                    <h3 class="news-title">{{ $article->title }}</h3>
                    <p style="color: var(--text-muted); margin-bottom: 1.5rem;">{{ Str::limit($article->content, 150) }}</p>
                    <a href="/articles/{{ $article->slug }}" class="news-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>
        <div style="margin-top: 3rem;">
            {{ $articles->links() }}
        </div>
    </main>

    @include('pages.partials.footer')
</body>
</html>
