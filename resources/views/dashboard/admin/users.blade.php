@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('header_title', 'Kelola Pengguna')
@section('header_subtitle', 'Manajemen akses dan peran pengguna sistem.')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 900; color: var(--primary);">Daftar Pengguna</h2>
            <p style="color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Kelola siapa saja yang bisa mengakses sistem.</p>
        </div>
        <button class="btn btn-primary" onclick="showAddUserModal()">
            <i class="fas fa-user-plus"></i> Tambah Pengguna
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Peran (Role)</th>
                    <th>Terdaftar</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td style="font-weight: 800; color: var(--primary);">{{ $u->name }}</td>
                    <td style="font-weight: 600; color: #64748b;">{{ $u->email }}</td>
                    <td><span class="badge badge-{{ str_replace(' ', '-', strtolower($u->role)) }}">{{ str_replace('-', ' ', $u->role) }}</span></td>
                    <td style="font-weight: 600; color: #64748b;">{{ $u->created_at->format('d M Y') }}</td>
                    <td style="text-align: right;">
                        <button onclick='editUser(@json($u))' class="btn" style="background: #f8fafc; color: var(--primary); padding: 8px 12px; margin-right: 5px;"><i class="fas fa-user-edit"></i></button>
                        @if($u->id != Auth::id())
                        <button onclick="deleteUser({{ $u->id }})" class="btn" style="background: #fff5f5; color: #dc2626; padding: 8px 12px;"><i class="fas fa-trash-alt"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function showAddUserModal() {
        Swal.fire({
            title: 'Tambah Pengguna Baru',
            html: `
                <div style="text-align: left; font-family: 'Quicksand', sans-serif;">
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-bottom: 8px; display: block;">NAMA LENGKAP</label>
                    <input id="swal-name" class="swal2-input" placeholder="Masukkan nama lengkap...">
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">ALAMAT EMAIL</label>
                    <input id="swal-email" type="email" class="swal2-input" placeholder="contoh@paud.local">
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">PERAN (ROLE)</label>
                    <select id="swal-role" class="swal2-select">
                        <option value="guru">Guru</option>
                        <option value="orang-tua">Orang Tua Siswa</option>
                        <option value="admin">Administrator</option>
                    </select>
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">KATA SANDI</label>
                    <input id="swal-password" type="password" class="swal2-input" placeholder="Minimal 6 karakter">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan Pengguna',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            preConfirm: () => {
                const name = document.getElementById('swal-name').value;
                const email = document.getElementById('swal-email').value;
                const password = document.getElementById('swal-password').value;
                if (!name || !email || !password) {
                    Swal.showValidationMessage('Semua kolom wajib diisi!');
                    return false;
                }
                return {
                    name: name,
                    email: email,
                    role: document.getElementById('swal-role').value,
                    password: password,
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                saveUser(result.value);
            }
        });
    }

    async function saveUser(data) {
        Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        try {
            const res = await fetch('/admin/users', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });
            const j = await res.json();
            if (j.success) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Pengguna baru telah ditambahkan.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
            } else {
                Swal.fire('Gagal', j.message || 'Terjadi kesalahan.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Sistem error.', 'error');
        }
    }

    function editUser(user) {
        Swal.fire({
            title: 'Edit Data Pengguna',
            html: `
                <div style="text-align: left; font-family: 'Quicksand', sans-serif;">
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-bottom: 8px; display: block;">NAMA LENGKAP</label>
                    <input id="swal-name" class="swal2-input" placeholder="Nama Lengkap" value="${user.name}">
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">ALAMAT EMAIL</label>
                    <input id="swal-email" type="email" class="swal2-input" placeholder="Email" value="${user.email}">
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">PERAN (ROLE)</label>
                    <select id="swal-role" class="swal2-select">
                        <option value="guru" ${user.role == 'guru' ? 'selected' : ''}>Guru</option>
                        <option value="orang-tua" ${user.role == 'orang-tua' ? 'selected' : ''}>Orang Tua Siswa</option>
                        <option value="admin" ${user.role == 'admin' ? 'selected' : ''}>Administrator</option>
                    </select>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan Perubahan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            confirmButtonColor: '#D4AF37',
            preConfirm: () => {
                return {
                    name: document.getElementById('swal-name').value,
                    email: document.getElementById('swal-email').value,
                    role: document.getElementById('swal-role').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateUser(user.id, result.value);
            }
        });
    }

    async function updateUser(id, data) {
        Swal.fire({ title: 'Memperbarui...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        try {
            const res = await fetch(`/admin/users/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });
            const j = await res.json();
            if (j.success) {
                Swal.fire({ icon: 'success', title: 'Terupdate!', text: 'Data pengguna berhasil diperbarui.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
            } else {
                Swal.fire('Gagal', j.message || 'Terjadi kesalahan', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Sistem error', 'error');
        }
    }

    async function deleteUser(id) {
        const result = await Swal.fire({
            title: 'Hapus Pengguna?',
            text: "Akses pengguna ini akan dicabut permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Ya, Hapus'
        });

        if (result.isConfirmed) {
            const res = await fetch(`/admin/users/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (res.ok) Swal.fire({ icon: 'success', title: 'Dihapus!', text: 'Pengguna telah dihapus.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
        }
    }
</script>
@endsection
