# Test End-to-End Flow untuk Upload Informasi

## Langkah-Langkah Testing:

### 1. Login Admin
- Buka: http://127.0.0.1:3000/login.html
- Email: `admin@paud.id`
- Password: `adminpaud`
- Klik Login

**Expected Result:**
- Redirect ke dashboard atau login page dengan success message
- Session cookie disimpan browser

---

### 2. Buka Post Page
- Buka: http://127.0.0.1:3000/post.html
- Verifikasi: Promo bar "Log in to the paud..." **TIDAK TAMPIL** (karena sudah login)
- Verifikasi: Form upload tersedia

**Expected Result:**
- Form "Post Artikel PAUD ATTHOHIRIYYAH" tampil
- Field input: Judul, Deskripsi, Kategori, Gambar

---

### 3. Isi Form dan Upload
Form Fields:
- **Judul**: Contoh: "Test Upload Informasi"
- **Deskripsi**: Contoh: "Ini adalah test post dari form upload"
- **Kategori**: Pilih "Artikel"
- **Gambar** (opsional): Pilih gambar atau kosongkan

Lalu klik **Upload**

**Expected Result:**
- Status text: "Berhasil menambahkan informasi"
- Redirect ke: http://127.0.0.1:3000/information.html

---

### 4. Verifikasi di Information Page
- Halaman information.html terbuka
- Lihat bagian "Informasi" (artikel-grid)

**Expected Result:**
- Item baru "Test Upload Informasi" **TAMPIL SEBAGAI CARD** di grid
- Card berada di posisi paling atas (newest first)
- Card menampilkan: Gambar (atau placeholder), Tanggal, Judul
- **Promo bar TIDAK TAMPIL** (karena admin sudah login)

---

### 5. Klik Card untuk Lihat Detail
- Klik card item yang baru dibuat

**Expected Result:**
- Halaman: http://127.0.0.1:3000/information-detail.html?id=<ID>
- Menampilkan:
  - Tombol "← Kembali ke Informasi" (link ke information.html)
  - Judul: "Test Upload Informasi"
  - Kategori badge: "Artikel"
  - Tanggal: Hari ini
  - Gambar (atau placeholder)
  - Deskripsi penuh
- Layout mirip dengan `sambutan-kepala-sekolah.html`

---

### 6. Logout & Verifikasi Promo Bar
- Klik icon user (bell-icon) di header
- Klik "Logout"
- Verifikasi redirect dan logout

**Expected Result:**
- Promo bar "Log in to the paud..." **MUNCUL KEMBALI**
- Login section di dropdown menu **TAMPIL**
- Logout section **TERSEMBUNYI**

---

## Troubleshooting:

### Card tidak tampil di information.html
1. Buka DevTools → Console
2. Cek error message
3. Buka tab Network → cek request `GET /admin/information`
   - Jika gagal (401/403): session tidak terbaca
   - Jika 200: data ada di response
4. Cek request headers: pastikan `Cookie` terkirim

### Detail page blank
1. Buka DevTools → Console
2. Cek error di JavaScript
3. Buka tab Network → cek request `GET /admin/information`
4. Cek URL parameter: `?id=<ID>` harus sesuai

### Promo bar masih tampil setelah login
1. Hard refresh: Ctrl+Shift+R
2. Check `/auth/me` endpoint via Network tab
3. Response harus include `user` object

---

## API Endpoints Reference:

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| POST | `/auth/login` | No | Login user |
| GET | `/auth/me` | Session | Check current user |
| POST | `/auth/logout` | Session | Logout |
| GET | `/admin/information` | No | Get all information |
| POST | `/admin/information` | Admin | Create information |
| PUT | `/admin/information/:id` | Admin | Update information |
| DELETE | `/admin/information/:id` | Admin | Delete information |

---

## Files Involved:

- **Backend:**
  - `WEB/Server.js` - Main server
  - `WEB/routes/admin.js` - Information endpoints
  - `WEB/routes/auth.js` - Authentication

- **Frontend:**
  - `WEB/Public/post.html` - Upload form
  - `WEB/Public/information.html` - List & grid view
  - `WEB/Public/information-detail.html` - Detail page (NEW)
  - `WEB/Public/css/post.css` - Post form styling
  - `WEB/Public/css/information.css` - List page styling
  - `WEB/Public/css/sambutan-kepala-sekolah.css` - Detail page styling (reused)

- **Data:**
  - `WEB/Public/information.json` - Static fallback data

---

## Summary:

✅ Implementasi alur **upload → tampil di list → klik → detail** sudah SELESAI.
✅ Header/footer styling match dengan profil-sekolah.css.
✅ Promo bar session-aware (tersembunyi saat login).
✅ Semua endpoint backend ready & tested.

Silakan test melalui browser sesuai langkah-langkah di atas!
