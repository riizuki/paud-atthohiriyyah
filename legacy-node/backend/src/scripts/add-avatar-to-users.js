const fs = require('fs');
const path = require('path');
const DB_FILE = path.join(__dirname, '../../data/db.json');

function readDB(){
  if(!fs.existsSync(DB_FILE)) return { users: [], messages: [] };
  const raw = fs.readFileSync(DB_FILE,'utf8');
  try{ return JSON.parse(raw); }catch(e){ return { users: [], messages: [] }; }
}

function writeDB(db){ fs.writeFileSync(DB_FILE, JSON.stringify(db,null,2)); }

const db = readDB();
let changed = 0;
(db.users||[]).forEach(u=>{
  if(u.avatar===undefined){ u.avatar = ''; changed++; }
});
if(changed>0){ writeDB(db); console.log(`Updated ${changed} users with default avatar field.`); }
else { console.log('No users needed updating.'); }
