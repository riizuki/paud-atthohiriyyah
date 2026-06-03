# Gallery Upload System - Reference Card

## 📋 Form Fields

```
NAMA / JUDUL:       Text input (id="nama")
DESKRIPSI:         Textarea (id="deskripsi", 5 rows)
TANGGAL:           Date input (id="tanggal") [AUTO-FILLED: TODAY]
FOTO / GAMBAR:     File input (id="foto", accept="image/*")
```

**Submit Button**: "Upload ke Galeri" (id="saveBtn")
**Status Display**: Span with id="postStatus"

---

## 🔄 Complete Flow

```
1. USER OPENS post.html
   └─ JavaScript: updateLoginStatus() → /auth/me
      ├─ If admin/guru: Enable form
      └─ If not logged in: Disable form + show promo bar

2. USER FILLS FORM
   ├─ Nama: Required
   ├─ Deskripsi: Required
   ├─ Tanggal: Auto-filled to today
   └─ Foto: Required (images only)

3. USER CLICKS "Upload ke Galeri"
   └─ JavaScript: form submit handler
      ├─ Check /auth/me (permission)
      ├─ Create FormData
      ├─ POST to /admin/gallery/upload
      ├─ Show "Mengirim..." status
      └─ Wait for response

4. SERVER SAVES PHOTO
   └─ /admin/gallery/upload endpoint
      ├─ Validate admin/guru role
      ├─ Save image to uploads/gallery/
      ├─ Save metadata to database
      └─ Return { success: true }

5. FORM SUCCESS HANDLER
   └─ Check response
      ├─ If success:
      │  ├─ Show "Berhasil! Redirect ke galeri..."
      │  ├─ Wait 1.5 seconds
      │  └─ Redirect to galeri.html
      └─ If error:
         ├─ Show error message
         └─ Re-enable button (for retry)

6. GALLERY AUTO-UPDATES
   └─ galeri.html polling (every 3 seconds)
      ├─ Fetch /admin/gallery/data
      ├─ Compare JSON with cache
      ├─ If different → Re-render grid
      ├─ If same → Skip (optimization)
      └─ New photo appears!
```

---

## 🔐 Permission Model

```
ROLE              CAN UPLOAD?    CAN DELETE?    CAN EDIT?
─────────────────────────────────────────────────────
Admin             YES ✅         YES ✅         YES ✅
Guru              YES ✅         YES ✅         YES ✅
Student           NO  ✗          NO  ✗          NO  ✗
Anonymous         NO  ✗          NO  ✗          NO  ✗
```

**How Validated**:
1. On page load: Check `/auth/me` endpoint
2. On form submit: Re-validate permission
3. In gallery: Check role before showing admin controls

---

## 📡 API Endpoints Quick Reference

| Method | Endpoint | Purpose | Auth |
|--------|----------|---------|------|
| POST | /admin/gallery/upload | Upload photo | ✅ admin/guru |
| GET | /admin/gallery/data | Get all photos | — |
| DELETE | /admin/gallery/{id} | Delete photo | ✅ admin/guru |
| GET | /auth/me | Check session | — |
| POST | /auth/logout | Logout user | — |

---

## 📝 FormData Structure (What Server Receives)

```javascript
FormData fields sent to POST /admin/gallery/upload:

title: "Kegiatan Bermain Anak"           (from id="nama")
description: "Anak-anak bermain dengan..." (from id="deskripsi")
file: <binary image data>               (from id="foto")

Note: Tanggal field is NOT sent to server
      (Server auto-generates timestamp)
```

---

## ⏰ Timing Reference

| Action | Duration | Purpose |
|--------|----------|---------|
| Date auto-fill | Instant | On page load |
| Upload process | ~1-2 sec | Server saves file |
| Polling check | Every 3 sec | Auto-refresh gallery |
| Redirect delay | 1.5 sec | Show success message |
| Photo appearance | 0-3 sec | Due to polling |

---

## 🎨 CSS Classes & Styling

### Promo Bar
```css
.promo-bar {
  display: none;           /* default: hidden */
}

.promo-bar.show {
  display: block;          /* shown when not logged in */
  background: #082b59;     /* dark blue */
  border-bottom: 2px solid #FFDE00;  /* yellow */
}
```

### Login/Logout Sections
```html
<div id="login-section">         <!-- shown when NOT logged in -->
<div id="logout-section">        <!-- shown when logged in -->
```

### Form Status
```html
<span id="postStatus" class="muted"></span>  <!-- for messages -->
```

---

## 🐛 Common Issues & Quick Fixes

| Issue | Check | Fix |
|-------|-------|-----|
| Form disabled | Check login | Login as admin/guru |
| Promo bar always shows | Check session | Clear cookies, login again |
| Photo not appearing | Check polling | Wait 3 seconds, no refresh needed |
| Permission denied | Check role | Verify user is admin/guru |
| Upload fails silently | Check console | Look for error messages in browser console |
| Date not auto-filled | Check JS | Verify `tanggalInput.valueAsDate = new Date()` runs |

---

## 📊 Expected Behavior

### NOT Logged In
- [x] Promo bar visible
- [x] Upload button disabled
- [x] Message: "Silakan login untuk upload galeri."
- [x] Login section shown
- [x] Logout section hidden

### Logged In as Admin/Guru
- [x] Promo bar hidden
- [x] Upload button enabled
- [x] Date auto-filled to today
- [x] Login section hidden
- [x] Logout section shown

### After Successful Upload
- [x] Status message: "Berhasil! Redirect ke galeri..."
- [x] Page redirects after 1.5 seconds
- [x] Photo appears in gallery within 3 seconds

### On Error
- [x] Error message displayed
- [x] Button re-enabled
- [x] User can retry
- [x] Previous photos still visible

---

## 🧪 Quick Test Checklist

```
Test 1: Anonymous User (5 min)
  ☐ Open post.html without login
  ☐ Verify promo bar visible
  ☐ Verify upload button disabled
  ☐ Verify correct message displays

Test 2: Logged In User (10 min)
  ☐ Login as admin/guru
  ☐ Open post.html
  ☐ Verify date auto-filled to today
  ☐ Fill form with test data
  ☐ Click "Upload ke Galeri"
  ☐ See "Mengirim..." status
  ☐ Get redirected to galeri.html

Test 3: Real-Time Updates (5 min)
  ☐ Open galeri.html in separate tab
  ☐ Don't refresh
  ☐ Upload photo from post.html
  ☐ Watch galeri.html auto-update
  ☐ Verify photo appears within 3 sec
```

---

## 💾 File Locations

```
WEB/Public/
├── post.html              ← Upload form (MODIFIED)
├── galeri.html            ← Gallery display (VERIFIED)
├── css/
│   ├── post.css           ← Form styling (UPDATED)
│   └── galeri.css         ← Gallery styling
├── uploads/
│   └── gallery/           ← Photos saved here
└── ...

Server:
├── routes/admin.js        ← Upload endpoint
├── db.json               ← Gallery data storage
└── ...
```

---

## 🚀 Deployment Steps

1. ✅ Verify server running: `http://127.0.0.1:3000/admin/_ping`
2. ✅ Test upload flow: Run all 3 tests above
3. ✅ Check photos appear: In gallery within 3 seconds
4. ✅ Verify admin controls: Detail & Delete buttons visible
5. ✅ Clear errors: No console errors or red flags
6. ✅ Deploy: Push to production

---

## 📞 Getting Help

**Quick Questions?**
→ See "Common Issues & Quick Fixes" above

**Understanding Code?**
→ Check QUICK_REFERENCE.md or GALLERY_UPLOAD_GUIDE.md

**Testing Help?**
→ Follow IMPLEMENTATION_CHECKLIST.md

**Technical Details?**
→ See GALLERY_UPLOAD_GUIDE.md → API Reference

---

## 🎯 Key Points to Remember

✅ **4 form fields**: nama, deskripsi, tanggal, foto
✅ **Date auto-fills**: to today automatically
✅ **Real-time updates**: gallery refreshes every 3 seconds
✅ **No manual refresh**: photos appear without user action
✅ **Permission checks**: both client-side and server-side
✅ **Error handling**: graceful with retry capability
✅ **Session aware**: UI changes based on login state

---

## 📈 Success Criteria

All these should pass:
- [x] Form submits to `/admin/gallery/upload` (not `/admin/information`)
- [x] Photos save to `uploads/gallery/` directory
- [x] Gallery displays photos in grid layout
- [x] New photos appear within 3 seconds (polling)
- [x] Admin users see Detail & Delete buttons
- [x] Non-admin users can't upload (permission denied)
- [x] Logout clears session and updates UI
- [x] Date field auto-fills to today
- [x] Error messages show friendly text
- [x] No console errors or warnings

---

**System**: Gallery Upload v1.0
**Status**: ✅ Ready to Test
**Server**: http://127.0.0.1:3000
**Documentation**: QUICK_REFERENCE.md (start here!)
