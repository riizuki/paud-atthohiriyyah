@extends('layouts.admin')

@section('title', 'Kelola Galeri')
@section('header_title', 'Galeri Foto')
@section('header_subtitle', 'Kelola momen-momen berharga PAUD ATTHOHIRIYYAH.')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 900; color: var(--primary);">Koleksi Galeri</h2>
            <p style="color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Total: {{ count($gallery) }} Foto</p>
        </div>
        <button class="btn btn-primary" onclick="showUploadModal()">
            <i class="fas fa-cloud-upload-alt"></i> Unggah Foto Baru
        </button>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
        @foreach($gallery as $item)
        <div class="card" style="padding: 0; overflow: hidden; border-radius: 24px; position: relative; border: 1px solid #f1f5f9;">
            <div style="height: 200px; overflow: hidden;">
                <img src="{{ asset($item->file) }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            </div>
            <div style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; background: white;">
                <div style="overflow: hidden;">
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--primary); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->title }}</h4>
                    <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 4px; font-weight: 700; text-transform: uppercase;">{{ $item->created_at->format('d M Y') }}</p>
                </div>
                <button onclick="deleteGallery({{ $item->id }})" class="btn" style="background: #fff5f5; color: #dc2626; padding: 10px; border-radius: 12px;"><i class="fas fa-trash-alt"></i></button>
            </div>
        </div>
        @endforeach
    </div>

    @if($gallery->isEmpty())
    <div style="text-align: center; padding: 5rem 0; color: #94a3b8;">
        <div style="background: #f8fafc; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <i class="fas fa-images fa-3x" style="opacity: 0.2;"></i>
        </div>
        <h3 style="font-weight: 800; color: #64748b;">Galeri Masih Kosong</h3>
        <p style="font-weight: 600;">Unggah momen pertumbuhan siswa di sini.</p>
    </div>
    @endif
</div>

<script>
    async function showUploadModal() {
        const { value: file } = await Swal.fire({
            title: 'Pilih Foto Kegiatan',
            input: 'file',
            inputAttributes: { 'accept': 'image/*', 'aria-label': 'Pilih foto' },
            showCancelButton: true,
            confirmButtonText: 'Lanjut Pilih Judul',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            confirmButtonColor: '#034F20',
        });

        if (file) {
            const { value: title } = await Swal.fire({
                title: 'Beri Judul Foto',
                input: 'text',
                inputPlaceholder: 'e.g. Wisuda Angkatan 2026',
                showCancelButton: true,
                confirmButtonText: 'Mulai Unggah',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#034F20',
                inputValidator: (value) => {
                    if (!value) return 'Judul foto wajib diisi!'
                }
            });

            if (title) {
                // Pass the file object directly
                uploadImage(file, title);
            }
        }
    }

    async function uploadImage(file, title) {
        Swal.fire({ title: 'Sedang Mengunggah...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        const formData = new FormData();
        formData.append('file', file);
        formData.append('title', title);

        try {
            const res = await fetch('/cms/gallery/upload', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            const j = await res.json();
            if (j.success) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Foto telah berhasil masuk ke galeri.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
            } else {
                Swal.fire('Gagal', j.message || 'Terjadi kesalahan saat unggah.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Ukuran file mungkin terlalu besar atau terjadi sistem error.', 'error');
        }
    }

    async function deleteGallery(id) {
        const result = await Swal.fire({
            title: 'Hapus Foto?',
            text: "Foto akan dihapus dari galeri publik.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            const res = await fetch(`/cms/gallery/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (res.ok) Swal.fire({ icon: 'success', title: 'Dihapus!', text: 'Foto telah dihapus.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
        }
    }
</script>
@endsection
