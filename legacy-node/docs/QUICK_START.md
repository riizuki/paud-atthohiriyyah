# Quick Start Guide - Information Management System

## Starting the Server

Open PowerShell and run:

```powershell
cd "C:\Users\ppa63\Music\PAUD ATTHOHIRIYYAH TESTING 1\WEB"
node Server.js
```

The server will start at: **http://127.0.0.1:3000**

## Accessing the Page

1. Open browser and go to: **http://127.0.0.1:3000/information.html**
2. You should see the Information page with sample articles

## Login as Admin

1. Click the **user icon** in the top-right corner
2. Click **Login**
3. Enter credentials:
   - **Email**: `admin@paud.id`
   - **Password**: `adminpaud`
4. After login, the "+ Tambah Informasi" button appears

## Create New Article

1. Click **+ Tambah Informasi** button
2. Fill in the form:
   - **Judul**: Enter article title
   - **Deskripsi**: Enter article content
   - **Kategori**: Choose from dropdown (Artikel, Kegiatan Anak, Kegiatan Orang Tua)
   - **Foto**: (Optional) Select an image
3. Click **Simpan**
4. Article appears in the grid

## Edit Article

1. Find the article in the grid
2. Click the yellow **Edit** button
3. Modify the details
4. Click **Simpan**

## Delete Article

1. Find the article in the grid
2. Click the red **Hapus** button
3. Confirm deletion

## Filter Articles

1. Use the **Kategori Artikel** sidebar
2. Click a category to filter
3. Click **Semua** to show all articles

## View Recent Articles

- Check **Artikel Terbaru** sidebar for latest 5 articles
- Click any article title to filter by that category

---

## File Locations

- **Main Page**: `WEB/Public/information.html`
- **Styling**: `WEB/Public/css/information.css`
- **API Routes**: `WEB/routes/admin.js`
- **Data Storage**: `WEB/Public/information.json`
- **Uploaded Images**: `WEB/Public/uploads/information/`

## API Endpoints

```
GET    /admin/information         # Get all articles
POST   /admin/information         # Create article (admin)
PUT    /admin/information/:id     # Update article (admin)
DELETE /admin/information/:id     # Delete article (admin)
GET    /auth/me                   # Check login status
POST   /auth/login                # Login
POST   /auth/logout               # Logout
```

## Troubleshooting

### Server Won't Start
- Check Node.js is installed: `node --version`
- Check port 3000 is free
- Check you're in the correct directory

### Articles Not Showing
- Refresh the page (F5)
- Check browser console for errors
- Verify server is running

### Can't Login
- Check spelling of credentials
- Clear browser cookies and try again
- Make sure you're using admin account

### Images Not Uploading
- Check image format (JPG, PNG, GIF)
- Check image size (max 5MB)
- Check `/uploads/information/` folder exists

---

**Status**: Ready to Use ✅

**Server Running**: http://127.0.0.1:3000

**Access**: http://127.0.0.1:3000/information.html
