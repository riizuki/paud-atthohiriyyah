# Gallery Upload System - Complete Guide

## Overview
The gallery upload system has been refactored to be **gallery-focused** instead of information-focused. When users upload photos via `post.html`, the images appear in `galeri.html` with real-time polling updates.

## Form Fields (post.html)
The upload form now has exactly 4 fields:

| Field | HTML ID | Input Type | Required | Notes |
|-------|---------|-----------|----------|-------|
| Nama / Judul | `nama` | text | Yes | Photo title/name (e.g., "Kegiatan Bermain Anak") |
| Deskripsi | `deskripsi` | textarea | Yes | Photo description; explains the activity/moment |
| Tanggal | `tanggal` | date | Yes | Date field; **auto-filled to today's date** |
| Foto / Gambar | `foto` | file | Yes | Image file; accepts JPG, PNG, GIF (max 5MB) |

## Technical Implementation

### 1. Backend API Endpoint
**Endpoint**: `POST /admin/gallery/upload`

**Request Format** (FormData):
```
form-data:
  title:       "Kegiatan Bermain Anak"
  description: "Anak-anak bermain dengan mainan edukatif"
  file:        <binary image data>
```

**Server Behavior**:
- Requires admin/guru role (checked via `/auth/me`)
- Generates image filename with unique prefix
- Saves to `WEB/Public/uploads/gallery/` directory
- Returns response:
  ```json
  { "success": true, "id": "gallery-item-uuid", "message": "..." }
  ```

### 2. Frontend Upload Flow (post.html)

#### Step 1: Permission Check
- On page load: `updateLoginStatus()` fetches `/auth/me`
- If not logged in OR role not admin/guru:
  - Disables save button
  - Shows promo bar ("Log in to the paud...")
  - Displays error message

#### Step 2: Form Fill
- User fills in 4 fields
- Date field auto-populated to today
- File input accepts images only

#### Step 3: Form Submission
- Click "Upload ke Galeri" button
- JavaScript validates permission again via `/auth/me`
- FormData collected:
  ```javascript
  fd.append('title', document.getElementById('nama').value);
  fd.append('description', document.getElementById('deskripsi').value);
  fd.append('file', document.getElementById('foto').files[0]);
  ```
- POST to `/admin/gallery/upload` with `credentials: 'include'`
- Shows status: "Mengirim..."

#### Step 4: Success Flow
- Server returns `{ success: true }`
- Status shows: "Berhasil! Redirect ke galeri..."
- After 1.5 seconds: redirects to `galeri.html`

#### Step 5: Error Handling
- Network errors: "Gagal: [error message]"
- Permission errors: "Akses ditolak: Anda harus login sebagai admin/guru."
- Server errors: Shows response message
- Save button re-enabled on error for retry

### 3. Gallery Display (galeri.html)

#### Real-time Updates
- **Polling Interval**: Every 3 seconds via `setInterval(loadPosts, 3000)`
- **Cache Pattern**: Compares JSON stringified data; skips re-render if unchanged
  ```javascript
  const newJson = JSON.stringify(posts);
  if (newJson === _lastGalleryJson) return; // skip if no changes
  ```
- **Fallback**: If API fails, keeps last known data (doesn't clear grid)

#### Display Logic
- Fetches from `/admin/gallery/data`
- Renders as card grid with:
  - Image (from `uploads/gallery/` directory)
  - Title
  - Description preview (first 100 chars)
  - Meta info: uploaded_by username + timestamp
  - Admin controls: Detail & Delete buttons (if user is admin/guru)

#### Admin Controls
- **Detail Button**: Opens `post.html?galleryId=<ID>` for future edit functionality
- **Delete Button**: Deletes item via `DELETE /admin/gallery/<ID>`

### 4. Session Management

#### Login Status Check
- Runs `updateLoginStatus()` on page load
- Fetches `/auth/me` with `credentials: 'include'`
- Updates UI based on logged-in status:
  - **Logged In**: Hides promo bar, shows logout button
  - **Not Logged In**: Shows promo bar, shows login button

#### Promo Bar Behavior
```css
.promo-bar {
  display: none; /* default hidden */
}

.promo-bar.show {
  display: block; /* shown when not logged in */
}
```

#### Logout Handler
- Calls `POST /auth/logout`
- Clears localStorage
- Updates UI via `updateLoginStatus()`

## File Structure

```
WEB/Public/
├── post.html              ← Gallery upload form
├── galeri.html            ← Gallery display with polling
├── css/
│   ├── post.css           ← Form styling
│   └── galeri.css         ← Gallery grid styling
├── uploads/
│   └── gallery/           ← Saved gallery images
└── data/
    └── gallery.json       ← Gallery data (auto-managed by server)
```

## Testing Checklist

### 1. Server Running
- [ ] Verify `http://127.0.0.1:3000/admin/_ping` returns status 200
- [ ] Verify `/admin/gallery/data` returns `{ "list": [] }` (or existing items)

### 2. Login Flow
- [ ] Navigate to `post.html` without login
- [ ] Verify promo bar is visible
- [ ] Verify "Upload ke Galeri" button is disabled
- [ ] Verify message says "Silakan login untuk upload galeri."
- [ ] Click login, authenticate as admin/guru
- [ ] Return to `post.html`
- [ ] Verify promo bar is hidden
- [ ] Verify "Upload ke Galeri" button is enabled

### 3. Upload Form
- [ ] Verify all 4 fields are present: nama, deskripsi, tanggal, foto
- [ ] Verify tanggal field has today's date pre-filled
- [ ] Verify foto input only accepts images
- [ ] Try submitting empty form → should show required field errors
- [ ] Fill all fields with valid data (e.g., test.jpg)

### 4. Upload & Display
- [ ] Click "Upload ke Galeri"
- [ ] Verify status shows "Mengirim..."
- [ ] Wait for success message "Berhasil! Redirect ke galeri..."
- [ ] Verify auto-redirect to `galeri.html` (1.5 seconds)
- [ ] In `galeri.html`, verify new photo appears within 3 seconds (polling)
- [ ] Verify photo shows: image, title, description preview, meta info
- [ ] Verify admin user sees "Detail" and "Hapus" buttons

### 5. Real-time Polling
- [ ] Open `galeri.html` in one browser tab
- [ ] Open `post.html` in another tab
- [ ] Upload a photo via `post.html`
- [ ] Switch to `galeri.html` tab
- [ ] Verify new photo appears within 3 seconds (don't refresh page)

### 6. Admin Controls
- [ ] As admin, click "Detail" button on a gallery item
- [ ] Verify navigation to `post.html?galleryId=<ID>` (future edit page)
- [ ] Return to `galeri.html`
- [ ] Click "Hapus" on a gallery item
- [ ] Confirm deletion dialog
- [ ] Verify item removed from gallery within 3 seconds

### 7. Error Handling
- [ ] Try uploading without selecting a file → should show required field error
- [ ] Try uploading with incomplete form → should show required field errors
- [ ] Try logout and then navigate to `post.html` → should be disabled
- [ ] Try uploading as non-admin user → should show permission error

## Debugging Tips

### Issue: Photo not appearing in galeri.html
**Solution**: 
- Check browser console for JavaScript errors
- Verify server is running: `http://127.0.0.1:3000/admin/_ping`
- Check network tab: POST to `/admin/gallery/upload` should return status 200
- Check server logs for upload errors
- Wait up to 3 seconds for polling update

### Issue: "Akses ditolak" error
**Solution**:
- Verify user is logged in: Check `/auth/me` endpoint response
- Verify user role is admin/guru (check response `role` field)
- Ensure session cookie is being sent: Check network request has `Cookie` header

### Issue: Form fields not matching expected names
**Solution**:
- HTML IDs: `nama`, `deskripsi`, `tanggal`, `foto` (for display)
- FormData names sent to server: `title`, `description`, `file` (backend expects these)
- Verify FormData.append() calls match backend expectations

### Issue: Image not loading in gallery
**Solution**:
- Check image path in gallery item: should be `uploads/gallery/...`
- Verify image file exists in server filesystem
- Check onerror handler: image shows placeholder if path is wrong
- Try direct URL: `http://127.0.0.1:3000/uploads/gallery/filename.jpg`

## API Reference

### POST /admin/gallery/upload
Uploads a new gallery photo.

**Request**:
```
Headers:
  Content-Type: multipart/form-data
  Cookie: [session cookie]

Body (FormData):
  title: string (required)
  description: string (required)
  file: File object (required, images only)
```

**Response**:
```json
{
  "success": true,
  "id": "uuid-or-timestamp",
  "message": "Photo uploaded successfully"
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Error description"
}
```

### GET /admin/gallery/data
Retrieves list of all gallery photos.

**Request**:
```
Headers:
  Cache-Control: no-store
```

**Response**:
```json
{
  "success": true,
  "list": [
    {
      "id": "item-id",
      "title": "Photo Title",
      "description": "Photo description",
      "file": "uploads/gallery/filename.jpg",
      "time": "2024-01-15T10:30:00Z",
      "uploaded_by": "admin_username"
    }
  ]
}
```

### DELETE /admin/gallery/{id}
Deletes a gallery photo (admin/guru only).

**Request**:
```
Headers:
  Cookie: [session cookie]
```

**Response**:
```json
{
  "success": true,
  "message": "Photo deleted"
}
```

### GET /auth/me
Retrieves current logged-in user info.

**Response** (if logged in):
```json
{
  "success": true,
  "user": {
    "id": "user-id",
    "name": "Admin Name",
    "email": "admin@example.com",
    "role": "admin"
  }
}
```

**Response** (if not logged in):
```json
{
  "success": false,
  "message": "Not authenticated"
}
```

## Configuration

### API Base URL
Located in both `post.html` and `galeri.html`:
```javascript
const API_BASE = 'http://127.0.0.1:3000';
```

### Polling Interval
In `galeri.html`:
```javascript
setInterval(loadPosts, 3000); // 3 seconds
```

### Date Field Auto-fill
In `post.html`:
```javascript
const tanggalInput = document.getElementById('tanggal');
tanggalInput.valueAsDate = new Date(); // Today's date
```

### Redirect Delay After Upload
In `post.html`:
```javascript
setTimeout(() => { window.location.href = 'galeri.html'; }, 1500); // 1.5 seconds
```

## Summary

The gallery system provides:
- ✅ Simple 4-field upload form (nama, deskripsi, tanggal, foto)
- ✅ Real-time updates via 3-second polling with JSON caching
- ✅ Session-aware permission checks (admin/guru only)
- ✅ Responsive gallery grid display
- ✅ Admin controls (detail, delete)
- ✅ Error handling with user feedback
- ✅ Graceful fallback for network failures

For support or issues, check the debugging tips section or the server logs.
