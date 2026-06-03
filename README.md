# PAUD ATTHOHIRIYYAH

Portal Aplikasi Pendidikan PAUD ATTHOHIRIYYAH.

## Prasyarat

Pastikan komputer Anda telah terinstal:
- PHP (versi 8.2 atau lebih baru)
- Composer
- Node.js & NPM
- SQLite (untuk database default)

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

3. **Instal Dependensi Node.js:**
   ```bash
   npm install
   ```

4. **Setup Environment:**
   Salin file contoh konfigurasi dan atur *environment* Anda:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan konfigurasi database jika diperlukan (default menggunakan SQLite).

5. **Generate App Key:**
   ```bash
   php artisan key:generate
   ```

## Setup Database

1. **Buat File Database (Jika menggunakan SQLite):**
   Pastikan file `database/database.sqlite` ada. Jika belum, Anda bisa membuatnya:
   ```bash
   touch database/database.sqlite
   ```

2. **Jalankan Migrasi & Seeder:**
   Jalankan perintah berikut untuk membuat tabel dan mengisi data awal:
   ```bash
   php artisan migrate --seed
   ```

## Menjalankan Aplikasi

1. **Jalankan Development Server:**
   ```bash
   php artisan serve
   ```

2. **Jalankan Vite (untuk aset CSS/JS):**
   Buka terminal baru, lalu jalankan:
   ```bash
   npm run dev
   ```

3. **Akses Aplikasi:**
   Buka browser dan akses `http://localhost:8000`.
