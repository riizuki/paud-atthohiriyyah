# Gallery Upload System - Quick Reference

## 🎯 System Overview
**Purpose**: Users upload photos (nama, deskripsi, tanggal, foto) → Photos appear in gallery automatically (polling every 3 seconds)

---

## 📋 Form Fields (post.html)

```
┌─────────────────────────────────────────┐
│ UPLOAD GALERI PAUD ATTHOHIRIYYAH        │
├─────────────────────────────────────────┤
│ Nama / Judul *                          │
│ [input type="text" id="nama"]           │
│                                         │
│ Deskripsi *                             │
│ [textarea id="deskripsi" rows=5]        │
│                                         │
│ Tanggal *                               │
│ [input type="date" id="tanggal"]        │ ← Auto-filled to today
│                                         │
│ Foto / Gambar *                         │
│ [input type="file" id="foto" accept] │  ← Images only
│                                         │
│ [Upload ke Galeri] [Status]             │
└─────────────────────────────────────────┘
```

---

## 🔄 Complete Flow

### 1. User Opens post.html
```
Page Load
  ↓
updateLoginStatus() called
  ↓
Fetch /auth/me
  ↓
├─ If admin/guru logged in:
│  ├─ Hide promo bar
│  ├─ Enable upload button
│  └─ Show logout option
│
└─ If not logged in:
   ├─ Show promo bar ("Log in...")
   ├─ Disable upload button
   └─ Show login option
```

### 2. User Fills Form
```
Nama:      "Kegiatan Bermain Anak"
Deskripsi: "Anak-anak bermain dengan mainan..."
Tanggal:   "2024-01-15" (auto-filled)
Foto:      [select image file]
```

### 3. User Clicks "Upload ke Galeri"
```
Form Submit
  ↓
Check /auth/me (permission)
  ↓
├─ If not admin/guru:
│  └─ Show error, return
│
└─ If authorized:
   ├─ Create FormData
   ├─ Send to /admin/gallery/upload
   ├─ Show "Mengirim..."
   └─ Disable button
```

### 4. Server Processes Upload
```
POST /admin/gallery/upload (FormData)
  ↓
├─ Validate permission
├─ Save image to uploads/gallery/
├─ Store metadata in server
└─ Return { success: true, id: "...", message: "..." }
```

### 5. Frontend Success
```
Check response
  ↓
├─ If success:
│  ├─ Show "Berhasil! Redirect ke galeri..."
│  ├─ Wait 1.5 seconds
│  └─ Redirect to galeri.html
│
└─ If error:
   ├─ Show error message
   ├─ Re-enable button
   └─ Wait for retry
```

### 6. Gallery Auto-Updates
```
galeri.html polling (every 3 seconds)
  ↓
loadPosts() called
  ↓
Fetch /admin/gallery/data
  ↓
Compare JSON with cache
  ↓
├─ If same: Skip render (optimization)
└─ If different: Re-render gallery grid
  ↓
New photo appears!
```

---

## 🔐 Permission Validation

### Check Points
1. **Page Load**: `updateLoginStatus()` → `/auth/me` check
2. **Form Submit**: Permission validation before POST
3. **Gallery Controls**: Admin-only Detail/Delete buttons

### Role Requirements
- **Admin**: Full access (upload, delete, edit)
- **Guru**: Full access (upload, delete, edit)
- **Student/Other**: Read-only (see gallery, no upload)

---

## 📡 API Endpoints

| Method | Endpoint | Purpose | Auth Required |
|--------|----------|---------|----------------|
| POST | /admin/gallery/upload | Upload photo | admin/guru |
| GET | /admin/gallery/data | Fetch gallery list | — |
| DELETE | /admin/gallery/{id} | Delete photo | admin/guru |
| GET | /auth/me | Check session & role | — |
| POST | /auth/logout | Logout user | — |

---

## 🎨 CSS Classes

### Promo Bar Visibility
```css
.promo-bar {
  display: none; /* hidden by default */
}

.promo-bar.show {
  display: block; /* shown when not logged in */
}
```

### Colors (from profil-sekolah.css)
- **Header/Footer**: `#082b59` (dark blue)
- **Accent Border**: `#FFDE00` (yellow)
- **Font**: Trebuchet MS

---

## ⏱️ Timing

| Action | Duration | Purpose |
|--------|----------|---------|
| Polling interval | 3 seconds | How often gallery checks for new photos |
| Auto-redirect | 1.5 seconds | Time after upload success before redirect |
| Date default | On load | Sets date field to today |

---

## ❌ Error Handling

### User-Facing Errors
```
"Akses ditolak: Anda harus login sebagai admin/guru."
  ↓ Cause: Not admin/guru role

"Gagal memeriksa izin."
  ↓ Cause: Server connection error during permission check

"Gagal: [error message]"
  ↓ Cause: Upload failed; shows server error

"Silakan login untuk upload galeri."
  ↓ Cause: Not logged in; button disabled
```

### Recovery
- All errors show message to user
- Button re-enabled after error (for retry)
- Keep last known data in gallery (doesn't clear on fetch error)

---

## 🧪 Quick Test

### Test 1: Anonymous User
```
1. Open post.html (no login)
2. Verify: Promo bar visible
3. Verify: Upload button disabled
4. Verify: Message "Silakan login..."
```

### Test 2: Authorized User
```
1. Login as admin/guru
2. Open post.html
3. Verify: Promo bar hidden
4. Verify: Upload button enabled
5. Fill form (auto-fill tanggal to today)
6. Upload image
7. Redirected to galeri.html
8. New photo appears within 3 seconds
```

### Test 3: Unauthorized User
```
1. Login as student/other role
2. Open post.html
3. Verify: Upload button disabled
4. Verify: Message "Anda tidak memiliki izin..."
```

---

## 📂 File Locations

```
WEB/
├── Public/
│   ├── post.html           ← Upload form
│   ├── galeri.html         ← Display gallery
│   ├── css/
│   │   ├── post.css        ← Form styling
│   │   └── galeri.css      ← Gallery styling
│   └── uploads/
│       └── gallery/        ← Uploaded images stored here
│
└── (server files)
    ├── Server.js
    ├── routes/admin.js     ← Gallery upload endpoint
    ├── db.json             ← Gallery metadata
    └── ...

Root/
├── CHANGES_SUMMARY.md      ← Summary of all changes
├── GALLERY_UPLOAD_GUIDE.md ← Complete documentation
└── ...
```

---

## 🚀 Deployment Checklist

- [ ] Server running: `http://127.0.0.1:3000/admin/_ping` returns 200
- [ ] post.html has all 4 form fields: nama, deskripsi, tanggal, foto
- [ ] post.html POSTs to `/admin/gallery/upload` endpoint
- [ ] galeri.html has polling: `setInterval(loadPosts, 3000)`
- [ ] galeri.html has JSON caching: `_lastGalleryJson`
- [ ] Date field auto-fills to today on post.html load
- [ ] Promo bar uses `.show` class (not `.style.display`)
- [ ] Login/logout updates UI correctly
- [ ] Upload shows "Mengirim..." status
- [ ] Successful upload redirects to galeri.html after 1.5 seconds
- [ ] New photos appear in gallery within 3 seconds (polling)

---

## 💡 Key Features

✅ **4-Field Form**: Nama, Deskripsi, Tanggal (auto), Foto
✅ **Real-time Updates**: 3-second polling with JSON caching
✅ **Permission-Based**: Admin/guru only
✅ **Session-Aware**: Login status synchronized across page loads
✅ **Error Handling**: User-friendly messages with recovery
✅ **Optimized**: Skip re-render if data unchanged
✅ **Responsive**: Mobile-friendly gallery grid
✅ **Admin Controls**: Detail & Delete buttons for authorized users
✅ **Graceful Degradation**: Keep last known data if API fails

---

## 📝 Notes

- **Form field names matter**: HTML IDs (nama, deskripsi, tanggal, foto) for display, FormData keys (title, description, file) for API
- **Credentials required**: All requests use `credentials: 'include'` to send session cookie
- **No refresh needed**: Gallery updates automatically via polling
- **Fallback not needed**: galeri.html doesn't fallback to static JSON (API always available)
- **Image paths normalized**: Server removes "Public/" prefix from paths automatically

---

## 🆘 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Photo not appearing in gallery | Wait 3 seconds (polling interval); check server logs |
| "Akses ditolak" on upload | Verify admin/guru role; check `/auth/me` response |
| Form fields not saving | Verify HTML IDs match (nama, deskripsi, tanggal, foto) |
| Redirect not happening | Check browser console for JavaScript errors |
| Promo bar always showing | Verify `.show` class is being added/removed (not `.style.display`) |
| Date field not auto-filled | Check if JavaScript runs before form renders |

---

Generated: 2024
System: Gallery Upload System v1.0
Status: Production Ready ✅
