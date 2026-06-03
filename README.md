# PAUD ATTHOHIRIYYAH

Portal Aplikasi Pendidikan PAUD ATTHOHIRIYYAH.

## Prasyarat

Pastikan komputer Anda telah terinstal:
- PHP (versi 8.2 atau lebih baru)
- Composer
- SQLite (untuk database default) atau MySQL/MariaDB

## Instalasi

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/riizuki/paud-atthohiriyyah.git
   cd paud-atthohiriyyah
   ```

2. **Instal Dependensi PHP:**
   ```bash
   composer install
   ```

3. **Setup Environment:**
   Salin file contoh konfigurasi dan atur *environment* Anda:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan konfigurasi database (DB_CONNECTION, DB_HOST, dll) sesuai dengan database yang Anda gunakan.

4. **Generate App Key:**
   ```bash
   php artisan key:generate
   ```

## Setup Database

1. **Migrasi Database:**
   Jalankan perintah berikut untuk membuat tabel:
   ```bash
   php artisan migrate
   ```

2. **Seeder Data (Opsional):**
   Untuk mengisi data awal, jalankan:
   ```bash
   php artisan db:seed
   ```

## Menjalankan Aplikasi

1. **Jalankan Development Server:**
   ```bash
   php artisan serve
   ```

2. **Akses Aplikasi:**
   Buka browser dan akses `http://localhost:8000`.
