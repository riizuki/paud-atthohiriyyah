<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PAUD ATTHOHIRIYYAH</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #f4f7f6; display: flex; min-height: 100vh; font-family: 'Quicksand', sans-serif; }
        :root {
            --primary: #034F20;
            --secondary: #D4AF37;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        .sidebar { width: 280px; background: var(--primary); color: white; padding: 2rem; position: sticky; top: 0; height: 100vh; z-index: 100; }
        .sidebar h2 { color: var(--secondary); margin-bottom: 2rem; font-size: 1.5rem; font-weight: 900; letter-spacing: -0.5px; font-family: 'Quicksand', sans-serif; }
        .nav-menu { list-style: none; }
        .nav-menu li { margin-bottom: 0.5rem; }
        .nav-menu a { color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 16px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-weight: 700; font-family: 'Quicksand', sans-serif; }
        .nav-menu a:hover, .nav-menu a.active { background: rgba(255,255,255,0.1); color: var(--secondary); transform: translateX(5px); }
        .nav-menu a.active { background: white; color: var(--primary); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        
        .main-content { flex: 1; padding: 3rem; overflow-y: auto; background: #f8fafc; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3.5rem; }
        .card { background: white; padding: 2.5rem; border-radius: 32px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04); }
        
        .btn { padding: 12px 24px; border-radius: 16px; font-weight: 800; transition: all 0.3s; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px; font-size: 0.9rem; font-family: 'Quicksand', sans-serif; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #04632d; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(3,79,32,0.2); }
        .btn-logout { background: #fff5f5; color: #dc2626; border: 1px solid #fee2e2; }
        .btn-logout:hover { background: #fee2e2; transform: translateY(-2px); }

        .table { width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-top: -12px; font-family: 'Quicksand', sans-serif; }
        .table th { padding: 1.2rem; text-align: left; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 800; }
        .table td { padding: 1.2rem; background: white; transition: all 0.3s; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; font-weight: 600; }
        .table tr td:first-child { border-left: 1px solid #f1f5f9; border-radius: 16px 0 0 16px; }
        .table tr td:last-child { border-right: 1px solid #f1f5f9; border-radius: 0 16px 16px 0; }
        .table tr:hover td { background: #fcfdfd; border-color: var(--primary); }

        .badge { padding: 6px 14px; border-radius: 12px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; font-family: 'Quicksand', sans-serif; }
        .badge-admin { background: #eef2ff; color: #4338ca; }
        .badge-guru { background: #fffbeb; color: #b45309; }
        .badge-orang-tua { background: #f0fdf4; color: #15803d; }

        /* Custom SweetAlert Styling */
        .swal2-popup { border-radius: 32px !important; padding: 2.5rem !important; font-family: 'Quicksand', sans-serif !important; }
        .swal2-title { font-weight: 800 !important; color: var(--primary) !important; font-size: 1.8rem !important; margin-bottom: 1.5rem !important; }
        .swal2-input, .swal2-textarea, .swal2-select { border-radius: 16px !important; border: 2px solid #f1f5f9 !important; font-family: 'Quicksand', sans-serif !important; font-weight: 700 !important; font-size: 1rem !important; margin: 10px 0 !important; width: 100% !important; box-sizing: border-box !important; }
        .swal2-input:focus, .swal2-textarea:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 4px rgba(3, 79, 32, 0.1) !important; outline: none !important; }
        .swal2-confirm { border-radius: 16px !important; font-weight: 800 !important; padding: 14px 35px !important; font-size: 1rem !important; background-color: var(--primary) !important; }
        .swal2-cancel { border-radius: 16px !important; font-weight: 800 !important; padding: 14px 35px !important; font-size: 1rem !important; background-color: #f1f5f9 !important; color: #64748b !important; margin-right: 10px !important; }
        .swal2-actions { margin-top: 2rem !important; }
    </style>
    @yield('styles')
</head>
<body>
    <aside class="sidebar">
        <h2>PAUD ADMIN</h2>
        <ul class="nav-menu">
            <li><a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.users') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}"><i class="fas fa-users"></i> Kelola Pengguna</a></li>
            <li><a href="{{ route('admin.articles') }}" class="{{ request()->is('admin/articles*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Berita & Artikel</a></li>
            <li><a href="{{ route('admin.gallery') }}" class="{{ request()->is('admin/gallery*') ? 'active' : '' }}"><i class="fas fa-images"></i> Galeri</a></li>
            <li><a href="{{ route('admin.agenda') }}" class="{{ request()->is('admin/agenda*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> Agenda</a></li>
            <li><a href="{{ route('admin.ppdb') }}" class="{{ request()->is('admin/ppdb-manage*') ? 'active' : '' }}"><i class="fas fa-user-graduate"></i> Kelola PPDB</a></li>
            <li style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);"><a href="/"><i class="fas fa-external-link-alt"></i> Lihat Website</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="header">
            <div>
                <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--primary);">@yield('header_title')</h1>
                <p style="color: #718096; font-weight: 600;">@yield('header_subtitle')</p>
            </div>
            <button class="btn btn-logout" onclick="handleLogout()" style="background: #fee2e2; color: #dc2626; border: 2px solid #fee2e2; padding: 10px 20px; border-radius: 12px; font-weight: 800; transition: all 0.3s; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-sign-out-alt"></i> Keluar
        </button>
        </header>

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        async function handleLogout() {
            const result = await Swal.fire({
                title: 'Yakin ingin keluar?',
                text: "Sesi admin Anda akan berakhir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#034F20',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!',
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
    @yield('scripts')
</body>
</html>
