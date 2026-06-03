<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUD ATTHOHIRIYYAH - Pendidikan Usia Dini</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="logo">
                <img src="{{ asset('img/Logopaud.png') }}" alt="Logo PAUD">
                <span>PAUD ATTHOHIRIYYAH</span>
            </a>
            <ul class="nav-links">
                <li><a href="/">Beranda</a></li>
                <li><a href="#about">Tentang Kami</a></li>
                <li><a href="#gallery">Galeri</a></li>
                <li><a href="#info">Informasi</a></li>
                @auth
                    <li><a href="/dashboard" class="btn-login">Dashboard</a></li>
                @else
                    <li><a href="/login" class="btn-login">Login</a></li>
                @endauth
            </ul>
        </div>
    </nav>
