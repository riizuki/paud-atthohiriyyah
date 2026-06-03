# ALUR LENGKAP: Post → Information

## Flow yang sudah diimplementasikan:

### 1. **post.html** (Upload Form)
- Form input: **Judul**, **Deskripsi**, **Kategori** (select), **Gambar** (optional)
- Setelah submit: FormData POST ke `http://127.0.0.1:3000/admin/information` dengan `credentials: 'include'`
- Response: Success → Redirect ke `information.html`

### 2. **information.html** (List & Grid View)
- **Polling logic** (seperti hasil-ppdb.html):
  - Load data setiap **3 detik** dari `/admin/information` endpoint
  - Cache response JSON untuk skip re-render jika data sama
  - Fallback ke static `information.json` jika API gagal
- **Render langsung tanpa modal**: Card items di grid dengan click handler
- **Click card** → Navigate ke `information-detail.html?id=<ID>`

### 3. **information-detail.html** (Full Page Detail)
- Load data dari `/admin/information` atau fallback `information.json`
- Menampilkan: Title, Category badge, Date, Image, Full description
- Tombol "← Kembali ke Informasi" untuk back to list

### 4. **Session & Login**
- `/auth/me` endpoint untuk check session
- Promo bar hanya tampil saat **belum login**
- Admin bisa edit/delete item di information.html

---

## Teknologi & Pattern:

### Polling (update real-time):
```javascript
// Load data setiap 3 detik
setInterval(loadInformation, 3000);

// Cache untuk skip re-render jika data sama
let _lastInformationJson = '';
const newJson = JSON.stringify(list);
if (newJson === _lastInformationJson) return; // skip
_lastInformationJson = newJson;
```

### FormData Upload:
```javascript
const fd = new FormData(uploadForm);
const res = await fetch('/admin/information', {
  method: 'POST',
  body: fd,
  credentials: 'include'  // important: send session cookie
});
```

### Error Handling:
- Try fetch dengan credentials
- Fallback fetch tanpa credentials
- Fallback load static JSON
- Keep last known data (tidak clear grid saat error)

---

## Testing Checklist:

### Test 1: Server & API
- [ ] Server running: `http://127.0.0.1:3000/admin/_ping` → `{"ok":true}`
- [ ] Get list: `http://127.0.0.1:3000/admin/information` → JSON dengan items

### Test 2: Upload Flow
1. **Login**:
   - Buka: `http://127.0.0.1:3000/login.html`
   - Email: `admin@paud.id`
   - Password: `adminpaud`
   - Klik Login

2. **Upload**:
   - Buka: `http://127.0.0.1:3000/post.html`
   - Isi form:
     - Judul: `Test Upload`
     - Deskripsi: `Ini test dari form`
     - Kategori: `Artikel`
     - Gambar: (optional)
   - Klik **Upload Informasi**
   - Tunggu status: `Berhasil menambahkan informasi!`
   - Auto-redirect ke `information.html`

3. **Lihat di Grid**:
   - Buka: `http://127.0.0.1:3000/information.html`
   - Tunggu **max 3 detik** (polling interval)
   - Item `Test Upload` **harus muncul** di grid

4. **Klik Detail**:
   - Klik card item `Test Upload`
   - Halaman: `information-detail.html?id=<ID>`
   - Tampilkan: Judul, kategori, tanggal, deskripsi

### Test 3: Promo Bar Session
- [ ] Sebelum login: Promo bar "Log in..." **TAMPIL**
- [ ] Setelah login: Promo bar **HILANG**
- [ ] Logout: Promo bar **MUNCUL KEMBALI**

---

## Debugging Tips:

### Data tidak muncul di information.html:
1. Buka DevTools (F12) → Console
2. Cek log:
   - `[INFO] Loaded X items from API`
   - `[INFO] Data tidak berubah, skip render` (normal, cache)
3. Network tab:
   - Cek `GET /admin/information` request
   - Status 200 = data ada
   - Status 401/403 = session problem
4. Cek `_lastInformationJson` value di console

### Upload tidak bekerja:
1. Console → cek error message
2. Network tab → cek POST `/admin/information`:
   - Status 200 = success
   - Status 401 = not authenticated
   - Status 400 = validation error (missing required fields)
3. Verifikasi form field names: `title`, `description`, `category`, `image`

### Detail page blank:
1. Check URL: `?id=<ID>` harus ada
2. Network → cek GET `/admin/information` response
3. Console → cek error message
4. Verifikasi ID ada di list

---

## File Changes Summary:

| File | Changes |
|------|---------|
| `post.html` | Simplified untuk hanya post ke `/admin/information`, hapus gallery logic |
| `information.html` | Tambah polling (setInterval 3s), cache logic, simplify render |
| `information-detail.html` | NEW: Full page detail view (mirip sambutan-kepala-sekolah.html) |
| `css/information.css` | Header/footer match profil-sekolah.css, promo bar class `.show` |
| `css/post.css` | Header/footer match profil-sekolah.css |
| `routes/admin.js` | GET/POST/PUT/DELETE `/admin/information` endpoints (already existed) |

---

## Expected Result:

✅ Admin upload article via post.html
✅ Article langsung muncul di information.html dalam max 3 detik (polling)
✅ Click card → detail page dengan full content
✅ Promo bar otomatis hide saat login, show saat logout
✅ Error handling graceful (fallback static JSON)

Selamat testing! 🚀
