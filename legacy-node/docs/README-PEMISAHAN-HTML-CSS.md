# Dokumentasi Pemisahan HTML dan CSS
## PAUD ATTHOHIRIYYAH TESTING 1

---

## Daftar File yang Telah Dibuat

### File CSS (Style Terpisah)
Berikut adalah file CSS yang telah dipisahkan dari HTML:

| File CSS | Keterangan |
|----------|-----------|
| `index.css` | Style untuk halaman utama (Index.html) |
| `login.css` | Style untuk halaman login (login.html) |
| `form.css` | Style untuk form data input (form.html) |
| `dashboard.css` | Style untuk halaman dashboard (Dashboard.html) |
| `data.css` | Style untuk halaman data (data.html) |
| `galeri.css` | Style untuk halaman galeri (galeri.html) |
| `post.css` | Style untuk halaman post artikel (post.html) |
| `ppdb.css` | Style untuk halaman PPDB (ppdb.html) |
| `identitas-paud.css` | Style untuk halaman identitas PAUD (identitas-paud.html) |
| `terms.css` | Style untuk halaman Terms of Use (Terms.html) |
| `signup.css` | Style untuk halaman pendaftaran (Sign up.html) |

### File HTML Bersih
Berikut adalah file HTML yang telah dibersihkan (contoh):

| File HTML | CSS yang Direferensikan |
|-----------|------------------------|
| `index-clean.html` | `index.css` |
| `login-clean.html` | `login.css` |

---

## Cara Menggunakan

### Step 1: Backup File Lama (Opsional)
```
Simpan file HTML asli dengan nama berbeda atau di folder backup
```

### Step 2: Update Link CSS di HTML
Tambahkan baris ini di dalam tag `<head>` setiap file HTML:

```html
<link rel="stylesheet" href="[nama-file].css">
```

**Contoh untuk Index.html:**
```html
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Header dan Footer paud</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="index.css">  <!-- TAMBAHKAN INI -->
</head>
```

### Step 3: Hapus Tag `<style>` dari HTML
Cari dan hapus semua tag `<style>...</style>` dari dalam `<head>`

---

## Struktur File di Public Folder

```
Public/
├── index.html (asli) / index-clean.html (baru)
├── index.css (BARU)
│
├── login.html (asli) / login-clean.html (baru)
├── login.css (BARU)
│
├── form.html (asli)
├── form.css (BARU)
│
├── Dashboard.html (asli)
├── dashboard.css (BARU)
│
├── data.html (asli)
├── data.css (BARU)
│
├── galeri.html (asli)
├── galeri.css (BARU)
│
├── post.html (asli)
├── post.css (BARU)
│
├── ppdb.html (asli)
├── ppdb.css (BARU)
│
├── identitas-paud.html (asli)
├── identitas-paud.css (BARU)
│
├── Terms.html (asli)
├── terms.css (BARU)
│
├── Sign up.html (asli)
├── signup.css (BARU)
│
├── profil-sekolah.html
└── img/
    └── [semua image files]
```

---

## Keuntungan Struktur Ini

✅ **Rapi dan Terorganisir**: HTML terpisah dari CSS
✅ **Mudah Dimodifikasi**: Edit CSS tanpa menyentuh HTML
✅ **Reusable**: CSS dapat digunakan bersama untuk multiple halaman
✅ **Performa Lebih Baik**: CSS di-cache oleh browser
✅ **Kolaborasi Lebih Mudah**: Desainer dan developer bisa bekerja terpisah
✅ **Maintainability**: Lebih mudah di-maintain dan di-debug

---

## Naming Convention yang Digunakan

| Jenis | Contoh | Penjelasan |
|-------|--------|-----------|
| HTML | `index.html` | Nama file HTML sesuai halaman |
| CSS | `index.css` | Nama CSS sesuai dengan nama HTML |
| Folder CSS | Semua di `Public/` | Satu folder dengan HTML |

---

## Tips Mengedit CSS

1. **Buka file CSS yang sesuai** berdasarkan halaman yang ingin diubah
2. **Hati-hati dengan media queries** - pastikan responsive design tetap berfungsi
3. **Test di berbagai ukuran layar** setelah mengubah CSS
4. **Gunakan developer tools** (F12) untuk debug styling

---

## Contoh Lengkap

### Sebelum (HTML + CSS di satu file):
```html
<!DOCTYPE html>
<html>
<head>
  <style>
    body { margin: 0; padding: 0; }
    /* 1000 baris CSS lainnya ... */
  </style>
</head>
<body>
  <!-- HTML content -->
</body>
</html>
```

### Sesudah (HTML dan CSS terpisah):

**index.html:**
```html
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="index.css">
</head>
<body>
  <!-- HTML content -->
</body>
</html>
```

**index.css:**
```css
body { margin: 0; padding: 0; }
/* Semua CSS di sini */
```

---

## Kontak & Support

Jika ada pertanyaan tentang struktur file ini, silahkan tanyakan lebih lanjut!

---

**Dibuat:** 28 Januari 2026
**Status:** Semua file CSS dan contoh HTML bersih telah siap
