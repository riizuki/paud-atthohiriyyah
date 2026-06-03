<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Kegiatan - PAUD ATTHOHIRIYYAH</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Koleksi foto kegiatan dan momen berharga anak-anak di PAUD ATTHOHIRIYYAH.">
    
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
        
        .gallery-item {
            background: #f0f0f0;
            border-radius: 20px;
            overflow: hidden;
            height: 350px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .gallery-item:hover img {
            transform: scale(1.15);
        }
    </style>
</head>
<body>
    @include('pages.partials.navbar')

    <header class="page-header">
        <div class="container">
            <h1>Galeri Kegiatan</h1>
            <p>Momen berharga pertumbuhan anak-anak di PAUD ATTHOHIRIYYAH</p>
        </div>
    </header>

    <main class="container" style="margin-bottom: 5rem;">
        <div class="gallery-grid">
            @foreach($gallery as $item)
            <div class="gallery-item">
                <img src="{{ asset($item->file) }}" alt="{{ $item->title }}">
                <div style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 1.5rem; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white;">
                    <h4 style="color: white; font-size: 1rem;">{{ $item->title }}</h4>
                </div>
            </div>
            @endforeach
        </div>
        <div style="margin-top: 3rem;">
            {{ $gallery->links() }}
        </div>
    </main>

    @include('pages.partials.footer')
</body>
</html>
