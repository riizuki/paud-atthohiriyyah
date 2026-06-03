const express = require("express");
const bcrypt = require("bcryptjs");
const { db, syncToJSON } = require("../db");

const router = express.Router();

/* ===== LOGIN ===== */
router.post("/login", (req, res) => {
  const { email, password } = req.body;
  console.log('POST /auth/login', { email });

  // ADMIN STATIS
  if (email === "admin@paud.id" && password === "adminpaud") {
    console.log('Admin login success');
    // set session
    req.session.user = { id: 'admin', email, role: 'admin', name: 'Admin' };
    return res.json({ success: true, user: req.session.user });
  }

  // USER DB
  db.get("SELECT * FROM users WHERE email=?", [email], async (err, u) => {
    if (err) {
      console.error('DB Error on login:', err);
      return res.status(500).json({ success: false, message: 'Internal server error' });
    }

    if (!u) return res.json({ success: false });

    const ok = await bcrypt.compare(password, u.password);
    if (!ok) return res.json({ success: false });

    // set session with user role and name, including id
    req.session.user = { id: u.id, email: u.email, role: u.role || 'user', name: u.name || u.email };

    res.json({
      success: true,
      user: req.session.user
    });
  });
});

// Return current session user
router.get('/me', (req, res) => {
  if (req.session && req.session.user) return res.json({ success: true, user: req.session.user });
  res.json({ success: false, user: null });
});

// Logout
router.post('/logout', (req, res) => {
  req.session.destroy(err => {
    if (err) return res.status(500).json({ success: false });
    res.json({ success: true });
  });
});

/* ===== REGISTER SISWA ===== */
router.post("/register", async (req, res) => {
  const { email, password } = req.body;
  const hash = await bcrypt.hash(password, 10);

  db.run(
    "INSERT INTO users(email,password,role) VALUES (?,?,?)",
    [email, hash, "user"],
    err => {
      if (err) return res.json({ success: false });
      // sync DB -> db.json and append to data.json for legacy use
      syncToJSON();
      try {
        const fs = require('fs');
        const path = require('path');
        const DATA_JSON = path.join(__dirname, '../../data/data.json');
        const list = fs.existsSync(DATA_JSON) ? JSON.parse(fs.readFileSync(DATA_JSON, 'utf-8')) : [];
        list.push({ email, role: 'user', waktu: new Date().toISOString() });
        fs.writeFileSync(DATA_JSON, JSON.stringify(list, null, 2), 'utf-8');
      } catch (e) {
        console.warn('Failed to append registered user to data.json', e && e.message ? e.message : e);
      }
      res.json({ success: true });
    }
  );
});

/* ===== ADMIN: LIST USER ===== */
router.get("/users", (req, res) => {
  if (!req.session || !req.session.user || req.session.user.role !== 'admin') return res.status(403).json({ success: false, message: 'Forbidden' });
  db.all("SELECT id,email,role FROM users", [], (e, rows) => {
    res.json(rows);
  });
});

/* ===== ADMIN: DELETE USER ===== */
router.delete("/users/:id", (req, res) => {
  if (!req.session || !req.session.user || req.session.user.role !== 'admin') return res.status(403).json({ success: false, message: 'Forbidden' });
  const id = req.params.id;
  // fetch user first to identify matching entry in data.json
  db.get('SELECT * FROM users WHERE id=?', [id], (err, user) => {
    if (err) { console.error('DB error fetching user for delete', err); return res.status(500).json({ success: false }); }
    db.run("DELETE FROM users WHERE id=?", [id], function(_){
      // sync DB -> db.json
      syncToJSON();
      // remove from data.json if present
      try {
        const fs = require('fs');
        const path = require('path');
        const DATA_JSON = path.join(__dirname, '../../data/data.json');
        if (fs.existsSync(DATA_JSON)) {
          const list = JSON.parse(fs.readFileSync(DATA_JSON, 'utf-8')) || [];
          const filtered = list.filter(entry => {
            if (!user) return true; // nothing to match, keep
            if (user.account_id && entry.account_id && entry.account_id === user.account_id) return false;
            if (entry.email && user.email && entry.email === user.email) return false;
            return true;
          });
          fs.writeFileSync(DATA_JSON, JSON.stringify(filtered, null, 2), 'utf-8');
        }
      } catch (e) {
        console.warn('Failed to update data.json after user delete', e && e.message ? e.message : e);
      }

      res.json({ success: true });
    });
  });
});

module.exports = router;
