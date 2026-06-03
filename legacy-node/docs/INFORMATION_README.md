# Information Management System - Complete Guide

## Overview

The Information Management System is a comprehensive content management solution for the PAUD ATTHOHIRIYYAH website. It allows administrators to create, edit, and delete informational articles with support for categories, images, and dynamic filtering.

## Features

### Public Features
- 📰 **Browse Articles** - View all published information
- 🏷️ **Category Filtering** - Filter articles by category (Artikel, Kegiatan Anak, Kegiatan Orang Tua)
- 📋 **Recent Articles Widget** - Quick access to latest 5 articles
- 📱 **Responsive Design** - Works perfectly on desktop, tablet, and mobile
- 🔍 **Category Counts** - See how many articles per category

### Admin Features
- ✍️ **Create Articles** - Add new information with title, description, category, and optional image
- ✏️ **Edit Articles** - Modify existing articles inline
- 🗑️ **Delete Articles** - Remove articles with confirmation
- 🖼️ **Image Upload** - Support for JPG, PNG, GIF images
- 🔐 **Access Control** - Admin-only features protected by role-based access

### Technical Features
- 🔗 **RESTful API** - Clean, well-documented endpoints
- 💾 **JSON Storage** - Persistent data storage with JSON files
- 🔐 **Session Security** - Secure session-based authentication
- 🎨 **Professional UI** - Clean, modern design matching reference sites
- 📞 **Error Handling** - Comprehensive error messages and validation

## File Structure

```
WEB/
├── Public/
│   ├── information.html          # Main page
│   ├── css/
│   │   └── information.css       # Styling
│   ├── information.json          # Data storage
│   └── uploads/
│       └── information/          # Article images
├── routes/
│   └── admin.js                  # Backend endpoints
└── Server.js                     # Express app
```

## API Endpoints

### Get Information (Public)
```
GET /admin/information
```
Returns all articles with ownership flags for authorization.

**Response:**
```json
{
  "success": true,
  "list": [
    {
      "id": 1707628800000,
      "title": "Article Title",
      "description": "Article content",
      "category": "Artikel",
      "image": "Public/uploads/information/1707628800000-image.jpg",
      "author": "Admin Name",
      "userId": 1,
      "time": "2025-02-09T08:00:00.000Z",
      "isOwner": true
    }
  ]
}
```

### Create Information (Admin Only)
```
POST /admin/information
Content-Type: multipart/form-data

Fields:
- title (required): Article title
- description (required): Article content
- category (required): Article category
- image (optional): Image file
```

**Response:**
```json
{
  "success": true,
  "item": { /* article object */ }
}
```

### Update Information (Admin Only)
```
PUT /admin/information/:id
Content-Type: multipart/form-data

Fields: Same as POST
```

### Delete Information (Admin Only)
```
DELETE /admin/information/:id
```

**Response:**
```json
{
  "success": true
}
```

## Usage Guide

### For End Users

1. **Visit the Information Page**
   - Open `http://127.0.0.1:3000/information.html`

2. **Browse Articles**
   - Articles are displayed in a 2-column grid
   - Each article shows a preview image, date, and title

3. **Filter by Category**
   - Use the "Kategori Artikel" sidebar to filter
   - Click a category to see only articles in that category
   - Click "Semua" to see all articles

4. **View Recent Articles**
   - Check the "Artikel Terbaru" sidebar for the 5 newest articles
   - Click on any article title to filter by that category

### For Administrators

1. **Login**
   - Click the user icon in the header
   - Enter credentials:
     - Email: `admin@paud.id`
     - Password: `adminpaud`

2. **Create New Article**
   - Click "+ Tambah Informasi" button
   - Fill in the form:
     - **Judul**: Article title
     - **Deskripsi**: Article content/description
     - **Kategori**: Select category (Artikel, Kegiatan Anak, Kegiatan Orang Tua)
     - **Foto**: (Optional) Upload an image
   - Click "Simpan" to create

3. **Edit Article**
   - Find the article in the grid
   - Click the yellow "Edit" button
   - Modify the details
   - Click "Simpan" to update

4. **Delete Article**
   - Find the article in the grid
   - Click the red "Hapus" button
   - Confirm the deletion

## Data Structure

### Article Object
```javascript
{
  id: 1707628800000,              // Timestamp ID
  title: "Article Title",          // Article title
  description: "Content...",       // Full article content
  category: "Artikel",             // Category name
  image: "Public/uploads/...",     // Image file path (null if no image)
  author: "Admin Name",            // Author name
  userId: 1,                       // User ID (null for pre-populated)
  time: "2025-02-09T08:00:00Z",   // Creation/Update timestamp
  isOwner: true                    // Only in API response (ownership flag)
}
```

### Categories
- **Artikel** - General articles/announcements
- **Kegiatan Anak** - Activities for children
- **Kegiatan Orang Tua** - Activities for parents

## Styling & Design

### Color Palette
- **Primary Blue**: `#1e5a96` (headers, links)
- **Dark Blue**: `#0d3b66` (gradients, hover states)
- **Light Gray**: `#f8f9fa` (sidebar backgrounds)
- **Accent Yellow**: `#ffc107` (edit button)
- **Danger Red**: `#dc3545` (delete button)
- **Success Green**: `#28a745` (add button)

### Layout
- **Desktop (1024px+)**: 2-column grid (articles + 300px sidebar)
- **Tablet (768px)**: 1-column layout with sidebar below
- **Mobile (480px)**: Single column, full width

### Typography
- **Headers**: System fonts, bold weight
- **Body**: 14px, regular weight
- **Links**: 14px, color: #1e5a96

## Form Validation

All form inputs are validated on submission:
- **Title**: Required, trimmed
- **Description**: Required, trimmed
- **Category**: Required, must select from dropdown
- **Image**: Optional, supported formats: JPG, PNG, GIF (Max 5MB)

## Security & Access Control

### Authentication
- Session-based authentication
- Credentials checked on every request
- Session timeout after inactivity

### Authorization
- **Admin-Only Endpoints**: POST, PUT, DELETE on `/admin/information`
- **Public Endpoints**: GET `/admin/information` (shows `isOwner` flag)
- **Role Checking**: `requireRole('admin')` middleware validates permissions

### File Security
- Images uploaded to isolated directory: `/uploads/information/`
- File names sanitized (spaces replaced with underscores)
- Old images deleted when articles are updated
- Path traversal prevented by using `path.relative()`

## Image Upload

### Supported Formats
- JPG/JPEG
- PNG
- GIF

### File Handling
- Original filename preserved in file name
- Timestamp prefix added for uniqueness
- Files stored in `/Public/uploads/information/`
- Images served via static file route

### Image Paths in API
- Paths stored relative to project root
- Automatically normalized for cross-platform compatibility
- Slashes converted for Windows/Linux compatibility

## Error Handling

### Common Errors
```
400 Bad Request: Missing required fields
403 Forbidden: Not authenticated as admin
404 Not Found: Article ID doesn't exist
500 Internal Server Error: Server error (check logs)
```

### Client-Side Messages
- Form validation errors shown in modal
- API errors displayed in status message area
- User confirmation dialogs for destructive actions

## Performance Optimization

### Client-Side
- Article grid uses CSS Grid for efficient layout
- Cards have smooth hover transitions
- Modal form reuses single DOM element
- Event delegation for dynamic elements

### Server-Side
- Synchronous JSON file operations (suitable for small datasets)
- Efficient array operations for filtering/sorting
- Best-effort image cleanup (doesn't block response)

## Browser Compatibility

| Browser | Version | Support |
|---------|---------|---------|
| Chrome | Latest | ✅ Full |
| Firefox | Latest | ✅ Full |
| Safari | Latest | ✅ Full |
| Edge | Latest | ✅ Full |
| Mobile Chrome | Latest | ✅ Full |
| Mobile Safari | Latest | ✅ Full |

## Troubleshooting

### Articles Not Loading
1. Check server is running: `node Server.js` in WEB directory
2. Verify API endpoint: `http://127.0.0.1:3000/admin/information`
3. Check browser console for fetch errors

### Images Not Displaying
1. Verify image uploaded successfully (check `/uploads/information/`)
2. Clear browser cache (Ctrl+F5)
3. Check image file permissions

### Admin Controls Not Showing
1. Login with admin credentials
2. Check session is stored (browser cookies)
3. Verify `/auth/me` endpoint returns correct role

### Form Submission Fails
1. Fill all required fields (title, description, category)
2. Check image file size (max 5MB)
3. Verify image format (JPG, PNG, GIF)

## Future Enhancements

### Planned Features
- [ ] Search functionality for articles
- [ ] Article preview page
- [ ] Rich text editor for descriptions
- [ ] Multiple image upload
- [ ] Article scheduling/publishing
- [ ] User comments/feedback
- [ ] Article tags/keywords
- [ ] Admin dashboard statistics

### Possible Improvements
- Database migration (SQLite/PostgreSQL instead of JSON)
- Image optimization/resizing on upload
- Pagination for large article lists
- User roles (Editor, Moderator, Admin)
- Draft/Published status
- Revision history

## Support & Maintenance

### Regular Tasks
- Monitor server logs
- Backup `information.json` file regularly
- Clean up old/unused images
- Test image uploads periodically

### Security Updates
- Keep Node.js updated
- Update npm dependencies: `npm update`
- Review file permissions regularly
- Monitor for suspicious file uploads

---

**Status**: ✅ Production Ready

**Last Updated**: 2025-02-11

**Version**: 1.0.0

**Server**: http://127.0.0.1:3000
