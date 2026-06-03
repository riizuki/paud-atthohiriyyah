@extends('layouts.admin')

@section('title', 'Kelola Berita & Artikel')
@section('header_title', 'Berita & Artikel')
@section('header_subtitle', 'Manajemen konten edukasi dan informasi sekolah.')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 900; color: var(--primary);">Daftar Artikel</h2>
            <p style="color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Total: {{ count($articles) }} Artikel</p>
        </div>
        <button class="btn btn-primary" onclick="showAddModal()">
            <i class="fas fa-plus-circle"></i> Tulis Artikel Baru
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Tanggal</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $a)
                <tr>
                    <td style="font-weight: 800; color: var(--primary); font-size: 1rem; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $a->title }}</td>
                    <td><span class="badge" style="background: #f1f5f9; color: #475569;">{{ $a->category }}</span></td>
                    <td style="font-weight: 600; color: #64748b;">{{ $a->author }}</td>
                    <td style="font-weight: 600; color: #64748b;">{{ $a->created_at->format('d M Y') }}</td>
                    <td style="text-align: right;">
                        <button onclick='editArticle(@json($a))' class="btn" style="background: #f8fafc; color: var(--primary); padding: 8px 12px; margin-right: 5px;"><i class="fas fa-edit"></i></button>
                        <button onclick="deleteArticle({{ $a->id }})" class="btn" style="background: #fff5f5; color: #dc2626; padding: 8px 12px;"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($articles->isEmpty())
    <div style="text-align: center; padding: 5rem 0; color: #94a3b8;">
        <div style="background: #f8fafc; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <i class="fas fa-file-signature fa-3x" style="opacity: 0.2;"></i>
        </div>
        <h3 style="font-weight: 800; color: #64748b;">Belum Ada Artikel</h3>
        <p style="font-weight: 600;">Klik tombol di atas untuk membuat konten pertama Anda.</p>
    </div>
    @endif
</div>

<script>
    function showAddModal() {
        Swal.fire({
            title: 'Tulis Artikel Baru',
            html: `
                <div style="text-align: left; font-family: 'Quicksand', sans-serif;">
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-bottom: 8px; display: block;">JUDUL ARTIKEL</label>
                    <input id="swal-title" class="swal2-input" placeholder="Masukkan judul yang inspiratif...">
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">KATEGORI</label>
                    <input id="swal-category" class="swal2-input" placeholder="e.g. Edukasi, Parenting, Tips">
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">KONTEN ARTIKEL</label>
                    <textarea id="swal-content" class="swal2-textarea" style="height: 250px;" placeholder="Tulis isi artikel di sini..."></textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Terbitkan Sekarang',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            preConfirm: () => {
                const title = document.getElementById('swal-title').value;
                const content = document.getElementById('swal-content').value;
                if (!title || !content) {
                    Swal.showValidationMessage('Judul dan isi konten tidak boleh kosong!');
                    return false;
                }
                return {
                    title: title,
                    category: document.getElementById('swal-category').value,
                    content: content
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                saveArticle(result.value);
            }
        });
    }

    async function saveArticle(data) {
        Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        try {
            const res = await fetch('/cms/articles', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });
            const j = await res.json();
            if (j.success) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Artikel telah berhasil diterbitkan.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
            } else {
                Swal.fire('Gagal', j.message || 'Terjadi kesalahan saat menyimpan.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
        }
    }

    function editArticle(article) {
        Swal.fire({
            title: 'Edit Artikel',
            html: `
                <div style="text-align: left; font-family: 'Quicksand', sans-serif;">
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-bottom: 8px; display: block;">JUDUL ARTIKEL</label>
                    <input id="swal-title" class="swal2-input" placeholder="Judul Artikel" value="${article.title}">
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">KATEGORI</label>
                    <input id="swal-category" class="swal2-input" placeholder="Kategori" value="${article.category || ''}">
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">KONTEN ARTIKEL</label>
                    <textarea id="swal-content" class="swal2-textarea" style="height: 250px;" placeholder="Konten Artikel">${article.content}</textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan Perubahan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            confirmButtonColor: '#D4AF37',
            preConfirm: () => {
                const title = document.getElementById('swal-title').value;
                const content = document.getElementById('swal-content').value;
                if (!title || !content) {
                    Swal.showValidationMessage('Judul dan isi konten tidak boleh kosong!');
                    return false;
                }
                return {
                    title: title,
                    category: document.getElementById('swal-category').value,
                    content: content
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateArticle(article.id, result.value);
            }
        });
    }

    async function updateArticle(id, data) {
        Swal.fire({ title: 'Memperbarui...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        try {
            const res = await fetch(`/cms/articles/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });
            const j = await res.json();
            if (j.success) {
                Swal.fire({ icon: 'success', title: 'Terupdate!', text: 'Artikel berhasil diperbarui.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
            } else {
                Swal.fire('Gagal', j.message || 'Terjadi kesalahan', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Sistem error', 'error');
        }
    }

    async function deleteArticle(id) {
        const result = await Swal.fire({
            title: 'Hapus Artikel?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            const res = await fetch(`/cms/articles/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (res.ok) Swal.fire({ icon: 'success', title: 'Terhapus!', text: 'Artikel telah dihapus dari sistem.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
        }
    }
</script>
@endsection
