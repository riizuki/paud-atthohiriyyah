const sqlite3 = require('sqlite3').verbose();
const fs = require('fs');
const path = require('path');

const DB_FILE = path.join(__dirname, '../data/db.sqlite');
const JSON_FILE = path.join(__dirname, '../data/db.json');

const db = new sqlite3.Database(DB_FILE);

// Initialize tables
db.serialize(() => {
  db.run(`CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE,
    password TEXT,
    role TEXT DEFAULT 'user',
    avatar TEXT DEFAULT ''
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    content TEXT,
    user_id INTEGER,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
  )`);
});

// === MIGRATION: ensure extra columns exist in users ===
db.all("PRAGMA table_info(users)", [], (err, cols) => {
  if (err) return console.error('Error reading users table info', err);
  const names = cols.map(c => c.name);
  if (!names.includes('account_id')) {
    console.log('Adding column account_id to users table');
    db.run("ALTER TABLE users ADD COLUMN account_id TEXT", () => {
      // try to create a unique index (allows multiple NULLs in SQLite)
      db.run("CREATE UNIQUE INDEX IF NOT EXISTS idx_users_account_id ON users(account_id)", err => {
        if (err) console.warn('Could not create unique index on account_id:', err && err.message ? err.message : err);
      });
    });
  }
  if (!names.includes('name')) {
    console.log('Adding column name to users table');
    db.run("ALTER TABLE users ADD COLUMN name TEXT");
  }
});

// Sync to JSON for backup
function syncToJSON() {
  db.all("SELECT * FROM users", [], (err, users) => {
    if (err) return;
    db.all("SELECT * FROM messages", [], (err, messages) => {
      if (err) return;
      const data = { users, messages };
      fs.writeFileSync(JSON_FILE, JSON.stringify(data, null, 2));
    });
  });
}

module.exports = { db, syncToJSON };