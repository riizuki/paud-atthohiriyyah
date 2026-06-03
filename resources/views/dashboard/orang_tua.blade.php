<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Orang Tua - PAUD ATTHOHIRIYYAH</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #fdfdfd; min-height: 100vh; font-family: 'Quicksand', sans-serif; }
        .top-nav { background: var(--primary); color: white; padding: 1rem 0; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: 'Quicksand', sans-serif; }
        .container { max-width: 1000px; margin: 0 auto; padding: 0 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin: 3rem 0; font-family: 'Quicksand', sans-serif; }
        .welcome-card { background: white; border-radius: 30px; padding: 3rem; box-shadow: 0 20px 40px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; margin-bottom: 2rem; text-align: center; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .menu-item { background: white; padding: 2rem; border-radius: 20px; text-align: center; text-decoration: none; color: var(--text-dark); transition: all 0.3s; border: 1px solid #eee; font-family: 'Quicksand', sans-serif; }
        .menu-item:hover { transform: translateY(-5px); border-color: var(--secondary); box-shadow: var(--shadow); }
        .menu-item i { font-size: 2rem; color: var(--primary); margin-bottom: 1rem; display: block; }
        .btn-logout { background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 8px 16px; border-radius: 50px; cursor: pointer; font-family: 'Quicksand', sans-serif; font-weight: 700; }
    </style>
</head>
<body>
    <nav class="top-nav">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 800; font-size: 1.2rem;">PAUD ATTHOHIRIYYAH</div>
            <button class="btn" onclick="handleLogout()" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 10px 20px; border-radius: 12px; cursor: pointer; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                Logout <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <div>
                <h1>Halo, Ayah/Bunda {{ $user->name }}!</h1>
                <p style="color: #718096;">Pantau perkembangan buah hati Anda di sini.</p>
            </div>
        </div>

        <div class="welcome-card">
            <h2 style="color: var(--primary); margin-bottom: 1rem;">Selamat Datang di Portal Orang Tua</h2>
            <p style="color: #718096; max-width: 600px; margin: 0 auto;">Di sini Anda dapat melihat laporan perkembangan, materi ajar, dan informasi terbaru seputar kegiatan sekolah.</p>
        </div>

        <div class="menu-grid">
            <a href="#" class="menu-item">
                <i class="fas fa-chart-line"></i>
                <strong>Perkembangan Siswa</strong>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-book-open"></i>
                <strong>Materi Belajar</strong>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-calendar-check"></i>
                <strong>Absensi</strong>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-bullhorn"></i>
                <strong>Pengumuman</strong>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function handleLogout() {
            const result = await Swal.fire({
                title: 'Yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#034F20',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar'
            });

            if (result.isConfirmed) {
                const res = await fetch('/auth/logout', { 
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (res.ok) window.location.href = '/login';
            }
        }
    </script>
</body>
</html>
