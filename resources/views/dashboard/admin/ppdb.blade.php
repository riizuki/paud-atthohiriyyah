@extends('layouts.admin')

@section('title', 'Kelola PPDB')
@section('header_title', 'Penerimaan Peserta Didik Baru')
@section('header_subtitle', 'Manajemen pendaftaran calon siswa baru.')

@section('content')
<div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 1.2rem; font-weight: 800; color: var(--primary);">Pengaturan Pendaftaran</h2>
            <p style="color: #64748b; font-size: 0.85rem;">Atur status dan tahun ajaran pendaftaran.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <input type="text" id="pmb_year" value="{{ $settings['pmb_year'] ?? '2026/2027' }}" class="btn" style="border: 2px solid #e2e8f0; width: 200px; padding: 12px;">
            <button onclick="updateSettings()" class="btn btn-primary">Simpan Pengaturan</button>
        </div>
    </div>
    <div style="margin-top: 1rem;">
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 700; color: var(--primary);">
            <input type="checkbox" id="pmb_status" {{ ($settings['pmb_status'] ?? 0) == 1 ? 'checked' : '' }} style="width: 20px; height: 20px;">
            Status Pendaftaran: <span id="status_text">{{ ($settings['pmb_status'] ?? 0) == 1 ? 'DIBUKA' : 'DITUTUP' }}</span>
        </label>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 900; color: var(--primary);">Daftar Pendaftar</h2>
            <p style="color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Total: {{ count($ppdb) }} Calon Siswa</p>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email / NIK</th>
                    <th>Jenis Kelamin</th>
                    <th>Status</th>
                    <th>Tanggal Daftar</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ppdb as $p)
                <tr>
                    <td style="font-weight: 800; color: var(--primary);">{{ $p->nama_lengkap }}</td>
                    <td style="font-weight: 600; color: #64748b;">
                        {{ $p->email ?? 'N/A' }}<br>
                        <small style="opacity: 0.7;">NIK: {{ $p->nik }}</small>
                    </td>
                    <td style="font-weight: 600; color: #64748b;">{{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>
                        <span class="badge" style="background: {{ $p->status == 'verified' ? '#f0fdf4' : ($p->status == 'rejected' ? '#fff1f2' : '#fefce8') }}; color: {{ $p->status == 'verified' ? '#15803d' : ($p->status == 'rejected' ? '#be123c' : '#a16207') }};">
                            {{ strtoupper($p->status) }}
                        </span>
                    </td>
                    <td style="font-weight: 600; color: #64748b;">{{ $p->created_at->format('d M Y') }}</td>
                    <td style="text-align: right;">
                        <button onclick='viewDetails(@json($p))' class="btn" style="background: #f8fafc; color: var(--primary); padding: 8px 12px; margin-right: 5px;" title="Lihat Detail"><i class="fas fa-eye"></i></button>
                        @if($p->status == 'pending')
                        <button onclick="updateStatus({{ $p->id }}, 'verified')" class="btn" style="background: #f0fdf4; color: #15803d; padding: 8px 12px; margin-right: 5px;" title="Verifikasi"><i class="fas fa-check-circle"></i></button>
                        @endif
                        <button onclick="deletePPDB({{ $p->id }})" class="btn" style="background: #fff5f5; color: #dc2626; padding: 8px 12px;" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($ppdb->isEmpty())
    <div style="text-align: center; padding: 5rem 0; color: #94a3b8;">
        <div style="background: #f8fafc; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <i class="fas fa-user-graduate fa-3x" style="opacity: 0.2;"></i>
        </div>
        <h3 style="font-weight: 800; color: #64748b;">Belum Ada Pendaftar</h3>
        <p style="font-weight: 600;">Data pendaftaran siswa baru akan muncul di sini.</p>
    </div>
    @endif
</div>

<script>
    document.getElementById('pmb_status').addEventListener('change', function() {
        document.getElementById('status_text').textContent = this.checked ? 'DIBUKA' : 'DITUTUP';
    });

    async function updateSettings() {
        const status = document.getElementById('pmb_status').checked ? 1 : 0;
        const year = document.getElementById('pmb_year').value;
        
        try {
            await fetch('/admin/settings/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ key: 'pmb_status', value: status }) });
            await fetch('/admin/settings/update', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ key: 'pmb_year', value: year }) });
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Pengaturan telah disimpan.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
        } catch (e) {
            Swal.fire('Error', 'Gagal menyimpan pengaturan.', 'error');
        }
    }

    function viewDetails(data) {
        Swal.fire({
            title: 'Detail Calon Siswa',
            html: `
                <div style="text-align: left; font-family: 'Quicksand', sans-serif;">
                    <div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
                        <img src="${data.pas_foto ? '/' + data.pas_foto : 'https://ui-avatars.com/api/?name=' + data.nama_lengkap}" style="width: 100px; height: 100px; border-radius: 20px; object-fit: cover; border: 4px solid #f1f5f9;">
                        <div>
                            <h4 style="margin: 0; color: var(--primary); font-size: 1.2rem;">${data.nama_lengkap}</h4>
                            <p style="margin: 5px 0; color: #64748b; font-weight: 700;">NIK: ${data.nik}</p>
                            <span class="badge" style="background: #f1f5f9; color: #475569;">${data.jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'}</span>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="font-weight: 800; color: #94a3b8; font-size: 0.7rem; text-transform: uppercase;">Tempat, Tgl Lahir</label>
                            <p style="margin: 4px 0; font-weight: 700; color: var(--primary);">${data.tempat_lahir}, ${data.tanggal_lahir}</p>
                        </div>
                        <div>
                            <label style="font-weight: 800; color: #94a3b8; font-size: 0.7rem; text-transform: uppercase;">Agama</label>
                            <p style="margin: 4px 0; font-weight: 700; color: var(--primary);">${data.agama}</p>
                        </div>
                        <div style="grid-column: span 2;">
                            <label style="font-weight: 800; color: #94a3b8; font-size: 0.7rem; text-transform: uppercase;">Alamat</label>
                            <p style="margin: 4px 0; font-weight: 700; color: var(--primary);">${data.alamat}</p>
                        </div>
                        <div style="margin-top: 10px;">
                            <label style="font-weight: 800; color: #94a3b8; font-size: 0.7rem; text-transform: uppercase;">Nama Ayah</label>
                            <p style="margin: 4px 0; font-weight: 700; color: var(--primary);">${data.nama_ayah}</p>
                        </div>
                        <div style="margin-top: 10px;">
                            <label style="font-weight: 800; color: #94a3b8; font-size: 0.7rem; text-transform: uppercase;">Nama Ibu</label>
                            <p style="margin: 4px 0; font-weight: 700; color: var(--primary);">${data.nama_ibu}</p>
                        </div>
                    </div>
                </div>
            `,
            width: '600px',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#034F20'
        });
    }

    async function updateStatus(id, status) {
        const result = await Swal.fire({
            title: status == 'verified' ? 'Verifikasi Pendaftar?' : 'Tolak Pendaftar?',
            text: "Status pendaftar akan diperbarui.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status == 'verified' ? '#034F20' : '#dc2626',
            confirmButtonText: 'Ya, Lanjutkan'
        });

        if (result.isConfirmed) {
            try {
                const res = await fetch('/ppdb/update', {
                    method: 'PUT',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id, status })
                });
                if (res.ok) {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Status telah diperbarui.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'Gagal memperbarui status.', 'error');
            }
        }
    }

    async function deletePPDB(id) {
        const result = await Swal.fire({
            title: 'Hapus Data?',
            text: "Data pendaftaran akan dihapus permanen dari sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Ya, Hapus'
        });

        if (result.isConfirmed) {
            try {
                const res = await fetch(`/admin/ppdb/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (res.ok) {
                    Swal.fire({ icon: 'success', title: 'Dihapus!', text: 'Data pendaftar telah dihapus.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
                }
            } catch (e) {
                Swal.fire('Error', 'Gagal menghapus data.', 'error');
            }
        }
    }
</script>
@endsection
