require('dotenv').config();
const express = require("express");
const cors = require("cors");
const fs = require("fs");
const session = require("express-session");
const { OAuth2Client } = require("google-auth-library");
const authRoutes = require("./routes/auth");
const adminRoutes = require("./routes/admin");
const multer = require("multer");
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

// ==== VIEW ENGINE SETUP ====
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// ==== MIDDLEWARE ====
app.use(cors({
  origin: true, 
  methods: ["GET", "HEAD", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
  allowedHeaders: ["Content-Type", "Authorization", "X-Requested-With"],
  credentials: true
}));

// Request logger for debugging
app.use((req, res, next) => {
  console.log(new Date().toISOString(), req.method, req.url, 'from', req.ip);
  next();
});

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// ==== SESSION ====
app.use(session({
  secret: process.env.SESSION_SECRET || 'paud-secret-key',
  resave: false,
  saveUninitialized: true,
  cookie: { secure: false, maxAge: 24 * 60 * 60 * 1000 }
}));

// ==== STATIC FILES ====
app.use(express.static(path.join(__dirname, 'public')));

// Ensure uploads directory exists
const UPLOADS_DIR = path.join(__dirname, 'public', 'uploads');
if (!fs.existsSync(UPLOADS_DIR)) {
  console.log('Uploads dir not found. Creating:', UPLOADS_DIR);
  fs.mkdirSync(UPLOADS_DIR, { recursive: true });
}

// ==== MULTER CONFIG ====
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, UPLOADS_DIR);
  },
  filename: (req, file, cb) => {
    cb(null, Date.now() + '-' + file.originalname);
  }
});
const upload = multer({ storage: storage });

// ==== ROUTES ====
app.use('/auth', authRoutes);
app.use('/admin', adminRoutes);

// Helper to read JSON data
const readData = (file) => {
  const filePath = path.join(__dirname, '../data', file);
  if (!fs.existsSync(filePath)) return [];
  try {
    const isi = fs.readFileSync(filePath, 'utf-8');
    return isi.trim() ? JSON.parse(isi) : [];
  } catch (e) {
    console.error(`Error reading ${file}:`, e);
    return [];
  }
};

// ==== ROOT ROUTE ====
app.get('/', (req, res) => {
  const gallery = readData('gallery.json');
  const information = readData('information.json');
  res.render('pages/index', { gallery, information });
});

// ==== GOOGLE CLIENT ====
const CLIENT_ID = "120811507877-41ht064k625b5ra7h5rhgl6vugmghc3n.apps.googleusercontent.com";
const client = new OAuth2Client(CLIENT_ID);

// ==== GOOGLE LOGIN ====
app.post("/auth/google", async (req, res) => {
  try {
    const { id_token } = req.body;
    const ticket = await client.verifyIdToken({
      idToken: id_token,
      audience: CLIENT_ID,
    });
    const payload = ticket.getPayload();
    res.json({
      success: true,
      user: {
        email: payload.email,
        name: payload.name,
        picture: payload.picture
      }
    });
  } catch (err) {
    console.error("Google Login Error:", err);
    res.status(400).json({ success: false, message: "Token invalid!" });
  }
});

// ==== DATA API ENDPOINTS ====
app.post("/submit", (req, res) => {
  const { nama, hobi } = req.body;
  if (!nama || !hobi) return res.status(400).json({ message: "Nama dan hobi wajib diisi!" });
  const DATA_JSON = path.join(__dirname, '../data/data.json');
  let dataLama = readData('data.json');
  const dataBaru = { nama, hobi, waktu: new Date().toISOString() };
  dataLama.push(dataBaru);
  fs.writeFileSync(DATA_JSON, JSON.stringify(dataLama, null, 2));
  res.json({ message: `Halo ${nama}, data berhasil disimpan!` });
});

app.get("/data", (req, res) => {
  res.json(readData('data.json'));
});

app.post("/ppdb/submit", upload.fields([
  { name: 'pas_foto', maxCount: 1 },
  { name: 'kartu_keluarga', maxCount: 1 },
  { name: 'akta_lahir', maxCount: 1 },
  { name: 'kip', maxCount: 1 },
  { name: 'bukti_pembayaran', maxCount: 1 }
]), (req, res) => {
  try {
    const ppdbData = req.body;
    const files = req.files;
    if (!ppdbData.nama_lengkap || !ppdbData.nik) return res.status(400).json({ message: "Nama dan NIK wajib diisi!" });
    const dataWithFiles = {
      ...ppdbData,
      pas_foto: files?.pas_foto?.[0]?.filename || null,
      kartu_keluarga: files?.kartu_keluarga?.[0]?.filename || null,
      akta_lahir: files?.akta_lahir?.[0]?.filename || null,
      kip: files?.kip?.[0]?.filename || null,
      bukti_pembayaran: files?.bukti_pembayaran?.[0]?.filename || null,
      waktu: new Date().toISOString()
    };
    const PPDB_JSON = path.join(__dirname, '../data/ppdb.json');
    let dataLama = readData('ppdb.json');
    dataLama.push(dataWithFiles);
    fs.writeFileSync(PPDB_JSON, JSON.stringify(dataLama, null, 2));
    res.json({ message: "Data PPDB berhasil disimpan!" });
  } catch (err) {
    console.error('Error in /ppdb/submit:', err);
    res.status(500).json({ success: false, message: err.message });
  }
});

app.get("/ppdb/data", (req, res) => {
  res.json(readData('ppdb.json'));
});

app.put('/ppdb/update', (req, res) => {
  try {
    const { nik, updates } = req.body;
    if (!nik) return res.status(400).json({ success: false, message: 'NIK required' });
    const PPDB_JSON = path.join(__dirname, '../data/ppdb.json');
    let list = readData('ppdb.json');
    const idx = list.findIndex(e => String(e.nik) === String(nik));
    if (idx === -1) return res.status(404).json({ success: false, message: 'Entry not found' });
    list[idx] = { ...list[idx], ...updates, waktu: new Date().toISOString() };
    fs.writeFileSync(PPDB_JSON, JSON.stringify(list, null, 2));
    res.json({ success: true, entry: list[idx] });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

app.get('/ping', (req, res) => {
  res.json({ ok: true, time: new Date().toISOString() });
});

// Legacy HTML Routes Fallback
app.get('/:page.html', (req, res) => {
  const filePath = path.join(__dirname, 'public', `${req.params.page}.html`);
  if (fs.existsSync(filePath)) res.sendFile(filePath);
  else res.status(404).send('Page not found');
});

// ==== SERVER START ====
app.listen(PORT, () => {
  console.log(`✅ Server berjalan di http://127.0.0.1:${PORT}`);
});
