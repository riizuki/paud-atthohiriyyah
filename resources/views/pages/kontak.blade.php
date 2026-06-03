<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak - PAUD ATTHOHIRIYYAH</title>
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
            margin-bottom: 4rem;
        }
        .page-header h1 { color: white !important; font-size: 3rem; font-weight: 800; }
        .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 5rem; }
        .contact-info-card { background: white; padding: 3rem; border-radius: 30px; box-shadow: var(--shadow); }
        .contact-info-card h3 { color: var(--primary); margin-bottom: 1.5rem; }
        .contact-form { background: white; padding: 3rem; border-radius: 30px; box-shadow: var(--shadow); }
        .contact-form .form-group { margin-bottom: 1.5rem; }
        .contact-form input, .contact-form textarea { width: 100%; padding: 1rem; border: 2px solid #eee; border-radius: 12px; }
        @media (max-width: 768px) { .contact-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    @include('pages.partials.navbar')

    <header class="page-header">
        <div class="container">
            <h1>Hubungi Kami</h1>
            <p>Kami siap melayani pertanyaan dan aspirasi Bapak/Ibu</p>
        </div>
    </header>

    <main class="container">
        <div class="contact-grid">
            <div class="contact-info-card">
                <h3>Informasi Kontak</h3>
                <p style="margin-bottom: 1rem;"><i class="fas fa-map-marker-alt" style="color: var(--secondary);"></i> Jl. Raya Siliwangi No. 123, Cianjur</p>
                <p style="margin-bottom: 1rem;"><i class="fas fa-phone" style="color: var(--secondary);"></i> +62 812-3456-7890</p>
                <p style="margin-bottom: 2rem;"><i class="fas fa-envelope" style="color: var(--secondary);"></i> info@paud-atthohiriyyah.sch.id</p>
                
                <div style="border-radius: 20px; overflow: hidden; height: 300px; background: #eee;">
                    <!-- Placeholder for Map -->
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #999;">
                        Google Maps Placeholder
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <h3>Kirim Pesan</h3>
                <div class="form-group">
                    <input type="text" placeholder="Nama Lengkap">
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <textarea rows="5" placeholder="Pesan Anda"></textarea>
                </div>
                <button class="btn btn-primary" style="width: 100%; border: none;">Kirim Sekarang</button>
            </div>
        </div>
    </main>

    @include('pages.partials.footer')
</body>
</html>
