<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru Dashboard - PAUD ATTHOHIRIYYAH</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #f4f7f6; display: flex; min-height: 100vh; font-family: 'Quicksand', sans-serif; }
        .sidebar { width: 280px; background: #2d3748; color: white; padding: 2rem; font-family: 'Quicksand', sans-serif; }
        .sidebar h2 { color: var(--secondary); margin-bottom: 2rem; font-size: 1.5rem; font-weight: 800; }
        .nav-menu { list-style: none; }
        .nav-menu li { margin-bottom: 0.5rem; }
        .nav-menu a { color: white; text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 12px; transition: all 0.3s; font-weight: 600; }
        .nav-menu a:hover, .nav-menu a.active { background: rgba(255,255,255,0.1); color: var(--secondary); }
        .main-content { flex: 1; padding: 3rem; overflow-y: auto; font-family: 'Quicksand', sans-serif; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; }
        .action-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; }
        .card { background: white; padding: 2rem; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-logout { background: #e53e3e; color: white; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; font-weight: 700; font-family: 'Quicksand', sans-serif; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>PORTAL GURU</h2>
        <ul class="nav-menu">
            <li><a href="#" class="active"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="#"><i class="fas fa-book"></i> Materi Ajar</a></li>
            <li><a href="#"><i class="fas fa-pen-nib"></i> Input Nilai</a></li>
            <li><a href="#"><i class="fas fa-tasks"></i> Agenda Kelas</a></li>
            <li><a href="#"><i class="fas fa-comment-dots"></i> Laporan Perkembangan</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="header">
            <div>
                <h1 style="font-size: 2rem;">Selamat Mengajar, {{ $user->name }}!</h1>
                <p style="color: #718096;">Mari bentuk generasi cerdas dan berakhlak.</p>
            </div>
            <button class="btn" onclick="handleLogout()" style="background: #fee2e2; color: #dc2626; border: 2px solid #fee2e2; padding: 10px 20px; border-radius: 12px; font-weight: 800; transition: all 0.3s; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </header>

        <div class="action-grid">
            <div class="card">
                <h3><i class="fas fa-plus-circle" style="color: var(--primary);"></i> Posting Materi Baru</h3>
                <p style="color: #718096; margin-top: 10px;">Bagikan materi pembelajaran kepada orang tua siswa.</p>
            </div>
            <div class="card">
                <h3><i class="fas fa-star" style="color: var(--secondary);"></i> Update Nilai Siswa</h3>
                <p style="color: #718096; margin-top: 10px;">Input perkembangan harian siswa di kelas.</p>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function handleLogout() {
            const result = await Swal.fire({
                title: 'Yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#034F20',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Keluar',
                cancelButtonText: 'Batal'
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
