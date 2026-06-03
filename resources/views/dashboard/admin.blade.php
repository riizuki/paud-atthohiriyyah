@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Halo, ' . $user->name . '!')
@section('header_subtitle', 'Selamat datang kembali di panel kendali utama.')

@section('content')
<div class="stat-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #e0e7ff; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-users" style="color: #4338ca; font-size: 1.5rem;"></i>
        </div>
        <div>
            <h3 style="color: #718096; font-size: 0.9rem; text-transform: uppercase;">Total Pengguna</h3>
            <p style="font-size: 2rem; font-weight: 800; color: var(--primary);">{{ $stats['total_users'] }}</p>
        </div>
    </div>
    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #fef3c7; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-file-alt" style="color: #92400e; font-size: 1.5rem;"></i>
        </div>
        <div>
            <h3 style="color: #718096; font-size: 0.9rem; text-transform: uppercase;">Berita & Artikel</h3>
            <p style="font-size: 2rem; font-weight: 800; color: var(--primary);">{{ $stats['total_articles'] }}</p>
        </div>
    </div>
    <div class="card" style="display: flex; align-items: center; gap: 20px;">
        <div style="background: #dcfce7; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-user-plus" style="color: #166534; font-size: 1.5rem;"></i>
        </div>
        <div>
            <h3 style="color: #718096; font-size: 0.9rem; text-transform: uppercase;">Pendaftar PPDB</h3>
            <p style="font-size: 2rem; font-weight: 800; color: var(--secondary);">{{ $stats['total_ppdb'] }}</p>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.2rem; font-weight: 800;">Aktivitas Terbaru</h2>
        <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 0.9rem;">Lihat Semua</a>
    </div>
    <div style="color: #718096; font-weight: 600;">
        <div style="padding: 1rem 0; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between;">
            <span>Pendaftaran PPDB Baru: Ahmad Rizky</span>
            <span style="font-size: 0.8rem; opacity: 0.7;">2 Jam yang lalu</span>
        </div>
        <div style="padding: 1rem 0; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between;">
            <span>Artikel Baru Diterbitkan: Tips Mendidik Anak</span>
            <span style="font-size: 0.8rem; opacity: 0.7;">5 Jam yang lalu</span>
        </div>
        <div style="padding: 1rem 0; display: flex; justify-content: space-between;">
            <span>User Baru Terdaftar: Bunda Siti</span>
            <span style="font-size: 0.8rem; opacity: 0.7;">1 Hari yang lalu</span>
        </div>
    </div>
</div>
@endsection
