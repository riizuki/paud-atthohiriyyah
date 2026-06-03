const express = require('express');
const bcrypt = require('bcryptjs');
const multer = require('multer');
const path = require('path');
const fs = require('fs');
const { db, syncToJSON } = require('../db');

const router = express.Router();

// Role check helper
function requireRole(...roles) {
  return (req, res, next) => {
    const u = req.session && req.session.user;
    if (!u || !roles.includes(u.role)) return res.status(403).json({ success: false, message: 'Forbidden' });
    next();
  };
}

// Ensure avatar upload directory exists
const AVATAR_DIR = path.join(__dirname, '../../../frontend/public', 'uploads', 'avatars');
if (!fs.existsSync(AVATAR_DIR)) fs.mkdirSync(AVATAR_DIR, { recursive: true });

const storage = multer.diskStorage({
  destination: (_req, _file, cb) => cb(null, AVATAR_DIR),
  filename: (_req, file, cb) => cb(null, Date.now() + '-' + file.originalname.replace(/\s+/g, '_'))
});
const upload = multer({ storage });

// Gallery upload storage
const GALLERY_DIR = path.join(__dirname, '../../../frontend/public', 'uploads', 'gallery');
if (!fs.existsSync(GALLERY_DIR)) fs.mkdirSync(GALLERY_DIR, { recursive: true });
const galleryStorage = multer.diskStorage({
  destination: (_req, _file, cb) => cb(null, GALLERY_DIR),
  filename: (_req, file, cb) => cb(null, Date.now() + '-' + file.originalname.replace(/\s+/g, '_'))
});
const galleryUpload = multer({ storage: galleryStorage });

// Helper: generate random password
function genPassword() {
  return Math.random().toString(36).slice(2, 10); // 8 chars
}

// Helper: generate account id for guru (G + timestamp)
function genGuruAccountId() {
  return 'G' + Date.now();
}

// Add Guru (admin only)
router.post('/guru', requireRole('admin'), upload.single('avatar'), async (req, res) => {
  try {
    const { name, email } = req.body;
    let { password } = req.body;

    if (!name) return res.status(400).json({ success: false, message: 'Nama wajib diisi' });

    // generate account_id and ensure uniqueness
    let account_id = genGuruAccountId();
    let exists = true;
    while (exists) {
      const row = await new Promise((resolve) => db.get('SELECT id FROM users WHERE account_id=?', [account_id], (_e, r) => resolve(r)));
      if (row) account_id = genGuruAccountId(); else exists = false;
    }

    const rawPass = password && password.trim().length ? password : genPassword();
    const hash = await bcrypt.hash(rawPass, 10);
    const avatarPath = req.file ? path.relative(path.join(__dirname, '..', '..'), req.file.path).replace(/\\/g, '/') : null;
    const userEmail = email && email.trim() ? email.trim() : `${account_id}@paud.local`;

    db.run(`INSERT INTO users(account_id, email, password, role, name, avatar) VALUES(?,?,?,?,?,?)`,
      [account_id, userEmail, hash, 'guru', name.trim(), avatarPath], function(err){
        if (err) {
          console.error('Insert guru error', err);
          return res.status(500).json({ success: false, message: err.message });
        }
        // Sync DB->JSON and also append a record to data.json for legacy UI
        syncToJSON();
        try {
          const DATA_JSON = path.join(__dirname, '../../data/data.json');
          const list = fs.existsSync(DATA_JSON) ? JSON.parse(fs.readFileSync(DATA_JSON, 'utf-8')) : [];
          list.push({ nama: name.trim(), email: userEmail, role: 'guru', account_id, waktu: new Date().toISOString() });
          fs.writeFileSync(DATA_JSON, JSON.stringify(list, null, 2), 'utf-8');
        } catch (e) {
          console.warn('Failed to append guru to data.json', e && e.message ? e.message : e);
        }

        res.json({ success: true, account_id, password: rawPass });
      }
    );

  } catch (err) {
    console.error('Error /admin/guru', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Update role (admin only)
router.put('/users/:id/role', requireRole('admin'), (req, res) => {
  const { role } = req.body;
  const id = req.params.id;
  console.log('PUT /admin/users/:id/role called', { id, role, sessionUser: req.session && req.session.user });
  if (!role) return res.status(400).json({ success: false, message: 'Role required' });

  // fetch user first to obtain email/account_id for data.json sync
  db.get('SELECT * FROM users WHERE id=?', [id], (err, user) => {
    if (err) { console.error('DB error fetching user for role update', err); return res.status(500).json({ success: false, message: 'DB error' }); }
    if (!user) return res.status(404).json({ success: false, message: 'User not found' });

    db.run('UPDATE users SET role=? WHERE id=?', [role, id], function(err2) {
      if (err2) {
        console.error('Update role error', err2);
        return res.status(500).json({ success: false, message: err2.message });
      }
      // sync sqlite -> db.json
      syncToJSON();

      // also update data.json entries that match this user (by account_id or email)
      try {
        const DATA_JSON = path.join(__dirname, '../../data/data.json');
        const list = fs.existsSync(DATA_JSON) ? JSON.parse(fs.readFileSync(DATA_JSON, 'utf-8')) : [];
        let changed = false;
        const updated = list.map(entry => {
          if ((user.account_id && entry.account_id === user.account_id) || (entry.email && entry.email === user.email)) {
            changed = true;
            return Object.assign({}, entry, { role, waktu: new Date().toISOString() });
          }
          return entry;
        });
        if (changed) fs.writeFileSync(DATA_JSON, JSON.stringify(updated, null, 2), 'utf-8');
      } catch (e) {
        console.warn('Failed to update data.json for role change', e && e.message ? e.message : e);
      }

      res.json({ success: true });
    });
  });
});

// Update user details (name, email) (admin only)
router.put('/users/:id', requireRole('admin'), (req, res) => {
  const id = req.params.id;
  const { name, email } = req.body;
  if (!name && !email) return res.status(400).json({ success: false, message: 'No fields to update' });

  db.get('SELECT * FROM users WHERE id=?', [id], (err, user) => {
    if (err) { console.error('DB error fetching user for update', err); return res.status(500).json({ success: false, message: 'DB error' }); }
    if (!user) return res.status(404).json({ success: false, message: 'User not found' });

    const updates = [];
    const params = [];
    if (name) { updates.push('name=?'); params.push(name.trim()); }
    if (email) { updates.push('email=?'); params.push(email.trim()); }
    params.push(id);

    db.run(`UPDATE users SET ${updates.join(', ')} WHERE id=?`, params, function(err2) {
      if (err2) {
        console.error('Update user error', err2);
        return res.status(500).json({ success: false, message: err2.message });
      }

      // sync to db.json
      syncToJSON();

      // update data.json entries as well
      try {
        const DATA_JSON = path.join(__dirname, '../../data/data.json');
        if (fs.existsSync(DATA_JSON)) {
          const list = JSON.parse(fs.readFileSync(DATA_JSON, 'utf-8')) || [];
          const updated = list.map(entry => {
            if ((user.account_id && entry.account_id === user.account_id) || (entry.email && entry.email === user.email)) {
              return Object.assign({}, entry, {
                nama: name ? name.trim() : entry.nama,
                email: email ? email.trim() : entry.email,
                waktu: new Date().toISOString()
              });
            }
            return entry;
          });
          fs.writeFileSync(DATA_JSON, JSON.stringify(updated, null, 2), 'utf-8');
        }
      } catch (e) {
        console.warn('Failed to update data.json for user update', e && e.message ? e.message : e);
      }

      res.json({ success: true });
    });
  });
});

// Create siswa from ppdb data (by NIK) (admin only)
router.post('/siswa-from-ppdb', requireRole('admin'), async (req, res) => {
  try {
    const { nik } = req.body;
    if (!nik) return res.status(400).json({ success: false, message: 'NIK required' });

    const PPDB_JSON = path.join(__dirname, '../../data/ppdb.json');
    if (!fs.existsSync(PPDB_JSON)) return res.status(400).json({ success: false, message: 'No PPDB data' });
    const isi = fs.readFileSync(PPDB_JSON, 'utf-8');
    const list = isi.trim() ? JSON.parse(isi) : [];
    const entry = list.find(e => e.nik === nik);
    if (!entry) return res.status(404).json({ success: false, message: 'Entry not found' });

    const account_id = nik; // as requested
    // ensure not exists
    const exists = await new Promise((resolve) => db.get('SELECT id FROM users WHERE account_id=?', [account_id], (_e, r) => resolve(r)));
    if (exists) return res.status(400).json({ success: false, message: 'Akun siswa sudah ada' });

    const rawPass = genPassword();
    const hash = await bcrypt.hash(rawPass, 10);
    const userEmail = `${account_id}@paud.local`;
    const name = entry.nama_lengkap || entry.nama || ('Siswa ' + account_id);

    db.run(`INSERT INTO users(account_id, email, password, role, name) VALUES(?,?,?,?,?)`, [account_id, userEmail, hash, 'siswa', name], function(err){
      if (err) {
        console.error('Insert siswa error', err);
        return res.status(500).json({ success: false, message: err.message });
      }
      // Sync DB->JSON and append to data.json
      syncToJSON();
      try {
        const DATA_JSON = path.join(__dirname, '../../data/data.json');
        const dlist = fs.existsSync(DATA_JSON) ? JSON.parse(fs.readFileSync(DATA_JSON, 'utf-8')) : [];
        dlist.push({ nama: name, email: userEmail, role: 'siswa', account_id, waktu: new Date().toISOString() });
        fs.writeFileSync(DATA_JSON, JSON.stringify(dlist, null, 2), 'utf-8');
      } catch (e) {
        console.warn('Failed to append siswa to data.json', e && e.message ? e.message : e);
      }

      res.json({ success: true, account_id, password: rawPass });
    });
  } catch (err) {
    console.error('Error /admin/siswa-from-ppdb', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Upload gallery item (guru or admin)
router.post('/gallery/upload', requireRole('guru', 'admin'), galleryUpload.single('file'), (req, res) => {
  try {
    const { description, title } = req.body;
    if (!req.file) return res.status(400).json({ success: false, message: 'File required' });
    const rel = path.relative(path.join(__dirname, '..', '..'), req.file.path).replace(/\\/g, '/');
    const item = { id: Date.now(), file: rel, title: title || '', description: description || '', uploaded_by: (req.session.user && req.session.user.name) || req.session.user.email, time: new Date().toISOString() };

    const GALLERY_JSON = path.join(__dirname, '../../data/gallery.json');
    const list = fs.existsSync(GALLERY_JSON) ? JSON.parse(fs.readFileSync(GALLERY_JSON, 'utf-8')) : [];
    list.push(item);
    fs.writeFileSync(GALLERY_JSON, JSON.stringify(list, null, 2), 'utf-8');

    res.json({ success: true, item });
  } catch (err) {
    console.error('Error upload gallery', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Get gallery data (public)
router.get('/gallery/data', (req, res) => {
  console.log('GET /admin/gallery/data requested from', req.ip);
  const GALLERY_JSON = path.join(__dirname, '../../data/gallery.json');
  const list = fs.existsSync(GALLERY_JSON) ? JSON.parse(fs.readFileSync(GALLERY_JSON, 'utf-8')) : [];
  // ensure items have stable ids
  let changed = false;
  const normalized = list.map((item, idx) => {
    if (!item.id) { item.id = Date.now() + idx; changed = true; }
    return item;
  });
  if (changed) fs.writeFileSync(GALLERY_JSON, JSON.stringify(normalized, null, 2), 'utf-8');
  res.json({ success: true, list: normalized });
});

// Update gallery item (title/description) (admin/guru)
router.put('/gallery/:id', requireRole('guru','admin'), (req, res) => {
  try {
    const id = Number(req.params.id);
    const { title, description } = req.body;
    const GALLERY_JSON = path.join(__dirname, '../../data/gallery.json');
    const list = fs.existsSync(GALLERY_JSON) ? JSON.parse(fs.readFileSync(GALLERY_JSON, 'utf-8')) : [];
    const idx = list.findIndex(it => Number(it.id) === id);
    if (idx === -1) return res.status(404).json({ success: false, message: 'Item not found' });
    if (title !== undefined) list[idx].title = title;
    if (description !== undefined) list[idx].description = description;
    fs.writeFileSync(GALLERY_JSON, JSON.stringify(list, null, 2), 'utf-8');
    res.json({ success: true, item: list[idx] });
  } catch (err) {
    console.error('Error updating gallery item', err);
    res.status(500).json({ success: false, message: err.message || 'Internal server error' });
  }
});

// Delete gallery item (admin/guru)
router.delete('/gallery/:id', requireRole('guru','admin'), (req, res) => {
  try {
    const id = Number(req.params.id);
    const GALLERY_JSON = path.join(__dirname, '../../data/gallery.json');
    const list = fs.existsSync(GALLERY_JSON) ? JSON.parse(fs.readFileSync(GALLERY_JSON, 'utf-8')) : [];
    const idx = list.findIndex(it => Number(it.id) === id);
    if (idx === -1) return res.status(404).json({ success: false, message: 'Item not found' });
    const removed = list.splice(idx,1)[0];
    fs.writeFileSync(GALLERY_JSON, JSON.stringify(list, null, 2), 'utf-8');
    // try remove file (best-effort)
    try { if (removed.file && fs.existsSync(path.join(__dirname, '../../../frontend/public', removed.file))) fs.unlinkSync(path.join(__dirname, '../../../frontend/public', removed.file)); } catch (e) { console.warn('Failed to remove file', e); }
    res.json({ success: true });
  } catch (err) {
    console.error('Error deleting gallery item', err);
    res.status(500).json({ success: false, message: err.message || 'Internal server error' });
  }
});

// Replace/Upload file for existing gallery item (admin/guru)
router.post('/gallery/:id/file', requireRole('guru','admin'), galleryUpload.single('file'), (req, res) => {
  try {
    const id = Number(req.params.id);
    if (!req.file) return res.status(400).json({ success: false, message: 'File required' });
    const GALLERY_JSON = path.join(__dirname, '../../data/gallery.json');
    const list = fs.existsSync(GALLERY_JSON) ? JSON.parse(fs.readFileSync(GALLERY_JSON, 'utf-8')) : [];
    const idx = list.findIndex(it => Number(it.id) === id);
    if (idx === -1) return res.status(404).json({ success: false, message: 'Item not found' });

    // remove old file (best-effort)
    try { const old = list[idx].file; if (old && fs.existsSync(path.join(__dirname, '../../../frontend/public', old))) fs.unlinkSync(path.join(__dirname, '../../../frontend/public', old)); } catch (e) { console.warn('Failed to remove old file', e); }

    const rel = path.relative(path.join(__dirname, '..', '..'), req.file.path).replace(/\\/g, '/');
    list[idx].file = rel;
    list[idx].time = new Date().toISOString();
    fs.writeFileSync(GALLERY_JSON, JSON.stringify(list, null, 2), 'utf-8');

    res.json({ success: true, item: list[idx] });
  } catch (err) {
    console.error('Error uploading file for gallery item', err);
    res.status(500).json({ success: false, message: err.message || 'Internal server error' });
  }
});

// Create kegiatan (photo optional). If file present -> saved to gallery.json, otherwise saved as article -> articles.json
router.post('/kegiatan/create', requireRole('guru', 'admin'), galleryUpload.single('file'), (req, res) => {
  try {
    const title = (req.body.title || '').trim();
    const description = (req.body.description || '').trim();
    const author = (req.session && req.session.user && req.session.user.name) ? req.session.user.name : (req.session.user && req.session.user.email) || 'Unknown';

    if (req.file) {
      // save to gallery
      const rel = path.relative(path.join(__dirname, '..', '..'), req.file.path).replace(/\\/g, '/');
      const item = { file: rel, title: title || '', description: description || '', uploaded_by: author, time: new Date().toISOString() };
      const GALLERY_JSON = path.join(__dirname, '../../data/gallery.json');
      const list = fs.existsSync(GALLERY_JSON) ? JSON.parse(fs.readFileSync(GALLERY_JSON, 'utf-8')) : [];
      list.push(item);
      fs.writeFileSync(GALLERY_JSON, JSON.stringify(list, null, 2), 'utf-8');
      return res.json({ success: true, type: 'gallery', item });
    }

    // no file -> save as article
    const ARTICLES_JSON = path.join(__dirname, '../../data/articles.json');
    const list = fs.existsSync(ARTICLES_JSON) ? JSON.parse(fs.readFileSync(ARTICLES_JSON, 'utf-8')) : [];
    const item = { id: Date.now(), title: title || (description ? (description.slice(0, 50) + '...') : 'Untitled'), description: description || '', author, time: new Date().toISOString() };
    list.push(item);
    fs.writeFileSync(ARTICLES_JSON, JSON.stringify(list, null, 2), 'utf-8');
    res.json({ success: true, type: 'article', item });
  } catch (err) {
    console.error('Error /admin/kegiatan/create', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Get articles (public)
router.get('/articles', (req, res) => {
  const ARTICLES_JSON = path.join(__dirname, '../../data/articles.json');
  const list = fs.existsSync(ARTICLES_JSON) ? JSON.parse(fs.readFileSync(ARTICLES_JSON, 'utf-8')) : [];
  
  // Check if current user is owner of each article
  const currentUser = req.session && req.session.user;
  const enriched = list.map(a => ({
    ...a,
    isOwner: currentUser && (currentUser.id === a.userId || currentUser.role === 'admin')
  }));
  
  res.json({ success: true, list: enriched });
});

// Create article (admin only)
router.post('/articles', requireRole('admin'), galleryUpload.single('image'), (req, res) => {
  try {
    const { title, content, category } = req.body;
    if (!title || !content) return res.status(400).json({ success: false, message: 'Title and content required' });
    
    const author = (req.session && req.session.user && req.session.user.name) || (req.session && req.session.user && req.session.user.email) || 'Unknown';
    const userId = req.session && req.session.user && req.session.user.id;
    let imagePath = null;
    
    if (req.file) {
      imagePath = path.relative(path.join(__dirname, '..', '..'), req.file.path).replace(/\\/g, '/');
    }
    
    const ARTICLES_JSON = path.join(__dirname, '../../data/articles.json');
    const list = fs.existsSync(ARTICLES_JSON) ? JSON.parse(fs.readFileSync(ARTICLES_JSON, 'utf-8')) : [];
    
    const item = {
      id: Date.now(),
      title: title.trim(),
      content: content.trim(),
      category: (category || '').trim() || 'Umum',
      image: imagePath,
      author,
      userId,
      time: new Date().toISOString()
    };
    
    list.push(item);
    fs.writeFileSync(ARTICLES_JSON, JSON.stringify(list, null, 2), 'utf-8');
    
    res.json({ success: true, item });
  } catch (err) {
    console.error('Error creating article', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Update article (admin only, own articles)
router.put('/articles/:id', requireRole('admin'), galleryUpload.single('image'), (req, res) => {
  try {
    const id = Number(req.params.id);
    const { title, content, category } = req.body;
    if (!title || !content) return res.status(400).json({ success: false, message: 'Title and content required' });
    
    const ARTICLES_JSON = path.join(__dirname, '../../data/articles.json');
    const list = fs.existsSync(ARTICLES_JSON) ? JSON.parse(fs.readFileSync(ARTICLES_JSON, 'utf-8')) : [];
    const idx = list.findIndex(a => Number(a.id) === id);
    if (idx === -1) return res.status(404).json({ success: false, message: 'Article not found' });
    
    // Check ownership
    const currentUser = req.session && req.session.user;
    const article = list[idx];
    if (currentUser.role !== 'admin' && currentUser.id !== article.userId) {
      return res.status(403).json({ success: false, message: 'Forbidden' });
    }
    
    // Update fields
    list[idx].title = title.trim();
    list[idx].content = content.trim();
    list[idx].category = (category || '').trim() || 'Umum';
    
    // Update image if new one provided
    if (req.file) {
      // Remove old image (best-effort)
      try {
        const oldImg = list[idx].image;
        if (oldImg && fs.existsSync(path.join(__dirname, '../../../frontend/public', oldImg))) {
          fs.unlinkSync(path.join(__dirname, '../../../frontend/public', oldImg));
        }
      } catch (e) { console.warn('Failed to remove old image', e); }
      
      list[idx].image = path.relative(path.join(__dirname, '..', '..'), req.file.path).replace(/\\/g, '/');
    }
    
    list[idx].time = new Date().toISOString();
    fs.writeFileSync(ARTICLES_JSON, JSON.stringify(list, null, 2), 'utf-8');
    
    res.json({ success: true, item: list[idx] });
  } catch (err) {
    console.error('Error updating article', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Delete article (admin only, own articles)
router.delete('/articles/:id', requireRole('admin'), (req, res) => {
  try {
    const id = Number(req.params.id);
    const ARTICLES_JSON = path.join(__dirname, '../../data/articles.json');
    const list = fs.existsSync(ARTICLES_JSON) ? JSON.parse(fs.readFileSync(ARTICLES_JSON, 'utf-8')) : [];
    const idx = list.findIndex(a => Number(a.id) === id);
    if (idx === -1) return res.status(404).json({ success: false, message: 'Article not found' });
    
    // Check ownership
    const currentUser = req.session && req.session.user;
    const article = list[idx];
    if (currentUser.role !== 'admin' && currentUser.id !== article.userId) {
      return res.status(403).json({ success: false, message: 'Forbidden' });
    }
    
    const removed = list.splice(idx, 1)[0];
    fs.writeFileSync(ARTICLES_JSON, JSON.stringify(list, null, 2), 'utf-8');
    
    // Remove image file (best-effort)
    try {
      if (removed.image && fs.existsSync(path.join(__dirname, '../../../frontend/public', removed.image))) {
        fs.unlinkSync(path.join(__dirname, '../../../frontend/public', removed.image));
      }
    } catch (e) { console.warn('Failed to remove image', e); }
    
    res.json({ success: true });
  } catch (err) {
    console.error('Error deleting article', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Information upload storage
const INFORMATION_DIR = path.join(__dirname, '../../../frontend/public', 'uploads', 'information');
if (!fs.existsSync(INFORMATION_DIR)) fs.mkdirSync(INFORMATION_DIR, { recursive: true });
const informationStorage = multer.diskStorage({
  destination: (_req, _file, cb) => cb(null, INFORMATION_DIR),
  filename: (_req, file, cb) => cb(null, Date.now() + '-' + file.originalname.replace(/\s+/g, '_'))
});
const informationUpload = multer({ storage: informationStorage });

// Get information (public)
router.get('/information', (req, res) => {
  const INFORMATION_JSON = path.join(__dirname, '../../data/information.json');
  const list = fs.existsSync(INFORMATION_JSON) ? JSON.parse(fs.readFileSync(INFORMATION_JSON, 'utf-8')) : [];
  
  // Check if current user is owner
  const currentUser = req.session && req.session.user;
  const enriched = list.map(a => ({
    ...a,
    isOwner: currentUser && (currentUser.id === a.userId || currentUser.role === 'admin')
  }));
  
  res.json({ success: true, list: enriched });
});

// Create information (admin only)
router.post('/information', requireRole('admin'), informationUpload.single('image'), (req, res) => {
  try {
    const { title, description, category } = req.body;
    if (!title || !description || !category) return res.status(400).json({ success: false, message: 'Title, description, and category required' });
    
    const author = (req.session && req.session.user && req.session.user.name) || (req.session && req.session.user && req.session.user.email) || 'Unknown';
    const userId = req.session && req.session.user && req.session.user.id;
    
    let imagePath = null;
    if (req.file) {
      imagePath = path.relative(path.join(__dirname, '..', '..'), req.file.path).replace(/\\/g, '/');
    }
    
    const INFORMATION_JSON = path.join(__dirname, '../../data/information.json');
    const list = fs.existsSync(INFORMATION_JSON) ? JSON.parse(fs.readFileSync(INFORMATION_JSON, 'utf-8')) : [];
    
    const item = {
      id: Date.now(),
      title: title.trim(),
      description: description.trim(),
      category: (category || '').trim() || 'Umum',
      image: imagePath,
      author,
      userId,
      time: new Date().toISOString()
    };
    
    list.push(item);
    fs.writeFileSync(INFORMATION_JSON, JSON.stringify(list, null, 2), 'utf-8');
    
    res.json({ success: true, item });
  } catch (err) {
    console.error('Error creating information', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Update information (admin only)
router.put('/information/:id', requireRole('admin'), informationUpload.single('image'), (req, res) => {
  try {
    const id = Number(req.params.id);
    const { title, description, category } = req.body;
    if (!title || !description || !category) return res.status(400).json({ success: false, message: 'Title, description, and category required' });
    
    const INFORMATION_JSON = path.join(__dirname, '../../data/information.json');
    const list = fs.existsSync(INFORMATION_JSON) ? JSON.parse(fs.readFileSync(INFORMATION_JSON, 'utf-8')) : [];
    const idx = list.findIndex(a => Number(a.id) === id);
    if (idx === -1) return res.status(404).json({ success: false, message: 'Information not found' });
    
    // Check ownership
    const currentUser = req.session && req.session.user;
    const info = list[idx];
    if (currentUser.role !== 'admin' && currentUser.id !== info.userId) {
      return res.status(403).json({ success: false, message: 'Forbidden' });
    }
    
    // Update fields
    list[idx].title = title.trim();
    list[idx].description = description.trim();
    list[idx].category = (category || '').trim() || 'Umum';
    
    // Update image if new one provided
    if (req.file) {
      // Remove old image (best-effort)
      try {
        const oldImg = list[idx].image;
        if (oldImg && fs.existsSync(path.join(__dirname, '../../../frontend/public', oldImg))) {
          fs.unlinkSync(path.join(__dirname, '../../../frontend/public', oldImg));
        }
      } catch (e) { console.warn('Failed to remove old image', e); }
      
      list[idx].image = path.relative(path.join(__dirname, '..', '..'), req.file.path).replace(/\\/g, '/');
    }
    
    list[idx].time = new Date().toISOString();
    fs.writeFileSync(INFORMATION_JSON, JSON.stringify(list, null, 2), 'utf-8');
    
    res.json({ success: true, item: list[idx] });
  } catch (err) {
    console.error('Error updating information', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Delete information (admin only)
router.delete('/information/:id', requireRole('admin'), (req, res) => {
  try {
    const id = Number(req.params.id);
    const INFORMATION_JSON = path.join(__dirname, '../../data/information.json');
    const list = fs.existsSync(INFORMATION_JSON) ? JSON.parse(fs.readFileSync(INFORMATION_JSON, 'utf-8')) : [];
    const idx = list.findIndex(a => Number(a.id) === id);
    if (idx === -1) return res.status(404).json({ success: false, message: 'Information not found' });
    
    // Check ownership
    const currentUser = req.session && req.session.user;
    const info = list[idx];
    if (currentUser.role !== 'admin' && currentUser.id !== info.userId) {
      return res.status(403).json({ success: false, message: 'Forbidden' });
    }
    
    const removed = list.splice(idx, 1)[0];
    fs.writeFileSync(INFORMATION_JSON, JSON.stringify(list, null, 2), 'utf-8');
    
    // Remove image file (best-effort)
    try {
      if (removed.image && fs.existsSync(path.join(__dirname, '../../../frontend/public', removed.image))) {
        fs.unlinkSync(path.join(__dirname, '../../../frontend/public', removed.image));
      }
    } catch (e) { console.warn('Failed to remove image', e); }
    
    res.json({ success: true });
  } catch (err) {
    console.error('Error deleting information', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Simple ping to verify admin router is mounted
router.get('/ping', (req, res) => {
  res.json({ status: 'ok' });
});

// ========== AGENDA MANAGEMENT ==========
// Create agenda (admin/guru only)
router.post('/agenda/create', requireRole('guru', 'admin'), (req, res) => {
  try {
    const title = (req.body.title || '').trim();
    const description = (req.body.description || '').trim();
    const startDate = req.body.startDate;
    const endDate = req.body.endDate;
    const location = (req.body.location || '').trim();
    const author = (req.session && req.session.user && req.session.user.name) ? req.session.user.name : (req.session.user && req.session.user.email) || 'Unknown';
    const userId = req.session && req.session.user ? req.session.user.id : null;

    if (!title || !description || !startDate || !endDate) {
      return res.status(400).json({ success: false, message: 'Missing required fields' });
    }

    const AGENDA_JSON = path.join(__dirname, '../../data/agenda.json');
    const list = fs.existsSync(AGENDA_JSON) ? JSON.parse(fs.readFileSync(AGENDA_JSON, 'utf-8')) : [];
    
    const item = {
      id: Date.now().toString(),
      title,
      description,
      startDate,
      endDate,
      location,
      author,
      userId,
      createdAt: new Date().toISOString()
    };

    list.push(item);
    fs.writeFileSync(AGENDA_JSON, JSON.stringify(list, null, 2), 'utf-8');

    res.json({ success: true, item });
  } catch (err) {
    console.error('Error /admin/agenda/create', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Get all agendas (public)
router.get('/agenda/data', (req, res) => {
  try {
    const AGENDA_JSON = path.join(__dirname, '../../data/agenda.json');
    const list = fs.existsSync(AGENDA_JSON) ? JSON.parse(fs.readFileSync(AGENDA_JSON, 'utf-8')) : [];
    
    // Check if current user is owner of each agenda
    const currentUser = req.session && req.session.user;
    const enriched = list.map(a => ({
      ...a,
      isOwner: currentUser && (currentUser.id === a.userId || currentUser.role === 'admin')
    }));

    res.json({ success: true, list: enriched });
  } catch (err) {
    console.error('Error getting agendas', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Update agenda (admin/guru only, owner or admin)
router.put('/agenda/:id', requireRole('guru', 'admin'), (req, res) => {
  try {
    const agendaId = req.params.id;
    const title = (req.body.title || '').trim();
    const description = (req.body.description || '').trim();
    const startDate = req.body.startDate;
    const endDate = req.body.endDate;
    const location = (req.body.location || '').trim();

    const AGENDA_JSON = path.join(__dirname, '../../data/agenda.json');
    const list = fs.existsSync(AGENDA_JSON) ? JSON.parse(fs.readFileSync(AGENDA_JSON, 'utf-8')) : [];
    
    const idx = list.findIndex(a => a.id === agendaId);
    if (idx === -1) {
      return res.status(404).json({ success: false, message: 'Agenda not found' });
    }

    const currentUser = req.session && req.session.user;
    if (currentUser.id !== list[idx].userId && currentUser.role !== 'admin') {
      return res.status(403).json({ success: false, message: 'Forbidden' });
    }

    list[idx] = { ...list[idx], title, description, startDate, endDate, location };
    fs.writeFileSync(AGENDA_JSON, JSON.stringify(list, null, 2), 'utf-8');

    res.json({ success: true, item: list[idx] });
  } catch (err) {
    console.error('Error updating agenda', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Delete agenda (admin/guru only, owner or admin)
router.delete('/agenda/:id', requireRole('guru', 'admin'), (req, res) => {
  try {
    const agendaId = req.params.id;

    const AGENDA_JSON = path.join(__dirname, '../../data/agenda.json');
    const list = fs.existsSync(AGENDA_JSON) ? JSON.parse(fs.readFileSync(AGENDA_JSON, 'utf-8')) : [];
    
    const idx = list.findIndex(a => a.id === agendaId);
    if (idx === -1) {
      return res.status(404).json({ success: false, message: 'Agenda not found' });
    }

    const currentUser = req.session && req.session.user;
    if (currentUser.id !== list[idx].userId && currentUser.role !== 'admin') {
      return res.status(403).json({ success: false, message: 'Forbidden' });
    }

    list.splice(idx, 1);
    fs.writeFileSync(AGENDA_JSON, JSON.stringify(list, null, 2), 'utf-8');

    res.json({ success: true });
  } catch (err) {
    console.error('Error deleting agenda', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

// Simple ping to verify admin router is mounted
router.get('/_ping', (_req, res) => {
  res.json({ ok: true, time: new Date().toISOString() });
});

module.exports = router;
