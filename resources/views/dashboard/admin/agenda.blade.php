@extends('layouts.admin')

@section('title', 'Kelola Agenda')
@section('header_title', 'Agenda Sekolah')
@section('header_subtitle', 'Jadwal kegiatan dan rencana pembelajaran.')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 900; color: var(--primary);">Jadwal Agenda</h2>
            <p style="color: #94a3b8; font-weight: 600; font-size: 0.9rem;">Daftar kegiatan mendatang.</p>
        </div>
        <button class="btn btn-primary" onclick="showAddAgendaModal()">
            <i class="fas fa-calendar-plus"></i> Tambah Agenda
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Lokasi</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agendas as $a)
                <tr>
                    <td style="font-weight: 800; color: var(--primary);">{{ $a->title }}</td>
                    <td style="font-weight: 700; color: #64748b;">{{ \Carbon\Carbon::parse($a->start_date)->format('d M Y') }}</td>
                    <td style="font-weight: 700; color: var(--secondary);">{{ \Carbon\Carbon::parse($a->start_date)->format('H:i') }} WIB</td>
                    <td style="font-weight: 600; color: #64748b;">{{ $a->location ?? 'Gedung Sekolah' }}</td>
                    <td style="text-align: right;">
                        <button onclick="deleteAgenda({{ $a->id }})" class="btn" style="background: #fff5f5; color: #dc2626; padding: 10px; border-radius: 12px;"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($agendas->isEmpty())
    <div style="text-align: center; padding: 5rem 0; color: #94a3b8;">
        <div style="background: #f8fafc; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <i class="fas fa-calendar-alt fa-3x" style="opacity: 0.2;"></i>
        </div>
        <h3 style="font-weight: 800; color: #64748b;">Belum Ada Agenda</h3>
        <p style="font-weight: 600;">Jadwalkan kegiatan sekolah pertama Anda di sini.</p>
    </div>
    @endif
</div>

<script>
    function showAddAgendaModal() {
        Swal.fire({
            title: 'Tambah Agenda Baru',
            html: `
                <div style="text-align: left; font-family: 'Quicksand', sans-serif;">
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-bottom: 8px; display: block;">NAMA KEGIATAN</label>
                    <input id="swal-title" class="swal2-input" placeholder="e.g. Rapat Komite Sekolah">
                    
                    <div style="display: flex; gap: 20px; margin-top: 20px;">
                        <div style="flex: 1;">
                            <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-bottom: 8px; display: block;">TANGGAL</label>
                            <input id="swal-date" type="date" class="swal2-input">
                        </div>
                        <div style="flex: 1;">
                            <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-bottom: 8px; display: block;">WAKTU</label>
                            <input id="swal-time" type="time" class="swal2-input">
                        </div>
                    </div>
                    
                    <label style="font-weight: 800; color: var(--primary); font-size: 0.85rem; margin-top: 20px; display: block;">LOKASI KEGIATAN</label>
                    <input id="swal-location" class="swal2-input" placeholder="e.g. Ruang Serbaguna Utama">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Jadwalkan Sekarang',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            confirmButtonColor: '#034F20',
            preConfirm: () => {
                const title = document.getElementById('swal-title').value;
                const date = document.getElementById('swal-date').value;
                if (!title || !date) {
                    Swal.showValidationMessage('Nama kegiatan dan tanggal wajib diisi!');
                    return false;
                }
                return {
                    title: title,
                    date: date,
                    time: document.getElementById('swal-time').value,
                    location: document.getElementById('swal-location').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                saveAgenda(result.value);
            }
        });
    }

    async function saveAgenda(data) {
        Swal.fire({ title: 'Menyimpan Jadwal...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        try {
            const res = await fetch('/cms/agenda/create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });
            const j = await res.json();
            if (j.success) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Agenda sekolah telah dijadwalkan.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
            } else {
                Swal.fire('Gagal', j.message || 'Terjadi kesalahan saat menyimpan.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
        }
    }

    async function deleteAgenda(id) {
        const result = await Swal.fire({
            title: 'Hapus Agenda?',
            text: "Kegiatan akan dihapus dari jadwal.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Ya, Hapus'
        });

        if (result.isConfirmed) {
            const res = await fetch(`/cms/agenda/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (res.ok) Swal.fire({ icon: 'success', title: 'Terhapus!', text: 'Agenda berhasil dihapus.', showConfirmButton: false, timer: 1500 }).then(() => location.reload());
        }
    }
</script>
@endsection
