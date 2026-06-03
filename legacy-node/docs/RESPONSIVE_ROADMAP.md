# ✅ RESPONSIVE DESIGN: Implementation Roadmap & Checklist

**Status:** index.html ✅ DONE  

Gunakan checklist ini untuk menerapkan responsive design ke semua halaman lain!

---

## 📋 Daftar Halaman yang Perlu Diupdate

### Priority 1: Main Pages (Paling Penting) 🔴

#### 1. galeri.html
- [ ] Add hamburger menu HTML
- [ ] Copy all CSS hamburger styles
- [ ] Add media query @768px to css/galeri.css
  - Adjust gallery grid columns
  - Responsive header
  - Responsive footer
- [ ] Add media query @480px
  - Gallery: 1-2 columns
  - Smaller thumbnails
- [ ] Add JavaScript hamburger functionality
- [ ] Test at 3+ breakpoints
- **Priority:** HIGH - Halaman galeri sangat penting untuk user

#### 2. information.html + information.json
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Add media query @768px to css/information.css
  - Information cards responsive
  - Detail section responsive
  - Search/filter responsive
- [ ] Add media query @480px
  - Cards stacked vertically
  - Smaller fonts
- [ ] Add JavaScript
- [ ] Test responsiveness
- **Priority:** HIGH - Info page sering dikunjungi

#### 3. agenda.html
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/agenda.css with media queries
  - Agenda items responsive
  - Timeline/list view responsive
  - Calendar view responsive
- [ ] Add media query @480px
  - Agenda list: full width
  - Smaller text
- [ ] Add JavaScript
- [ ] Test all views
- **Priority:** HIGH - Important untuk parent & siswa

#### 4. login.html + login-siswa.html
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/login.css
  - Form fields responsive
  - Larger input fields for mobile
- [ ] Add media query @480px
  - Full-width form
  - Larger touch targets for buttons
  - Font-size: 16px minimum (prevent iOS zoom)
- [ ] Add JavaScript
- [ ] Test form inputs
- **Priority:** HIGH - Login page critical

#### 5. ppdb.html (PMB 2026/2027)
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/ppdb.css
  - Form responsive
  - Table horizontal scroll for mobile
  - Status display responsive
- [ ] Add media query @768px
  - Form: 2 columns → 1 column
  - Table: stack or scroll
- [ ] Add media query @480px
  - Very compact form
  - Minimal table display
- [ ] Add JavaScript
- [ ] Test form & table
- **Priority:** CRITICAL - PPDB page sangat penting

---

### Priority 2: Secondary Pages (Penting) 🟠

#### 6. profil-sekolah.html
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/profil-sekolah.css
  - Content + image layout responsive
  - History/timeline responsive
- [ ] Add media queries
- [ ] Add JavaScript
- **Priority:** MEDIUM

#### 7. kontak.html
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/kontak.css
  - Contact form responsive
  - Map responsive
  - Info cards responsive
- [ ] Add media queries
  - Form: stack vertically
  - Map: full width responsive
- [ ] Add JavaScript (form validation)
- **Priority:** MEDIUM

#### 8. Dashboard.html (Admin)
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/Dashboard.css
  - Sidebar: collapsible on mobile
  - Main content: responsive grid
  - Cards: responsive layout
  - Charts: responsive sizing
- [ ] Add media query @768px
  - Sidebar hidden, hamburger shows
  - Main content full width
- [ ] Add media query @480px
  - Extreme compact mode
  - Single column layout
- [ ] Add JavaScript (sidebar toggle)
- **Priority:** MEDIUM

#### 9. Dashboard-Siswa.html (Student Dashboard)
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/Dashboard-Siswa.css
  - Similar to Dashboard but simpler
  - Card grid responsive
  - Data display responsive
- [ ] Add media queries
- [ ] Add JavaScript
- **Priority:** MEDIUM

---

### Priority 3: Content Pages (Penting tapi tidak urgent) 🟡

#### 10. post.html (Berita/Blog)
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/post.css
  - Article layout responsive
  - Image sizes responsive
  - Comment section responsive
- [ ] Add media queries
- **Priority:** LOW-MEDIUM

#### 11. post-agenda.html (Posting Agenda)
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/post-agenda.css
  - Editor form responsive
  - Preview responsive
- [ ] Add media queries
- **Priority:** LOW

#### 12. hasil-ppdb.html (PPDB Results)
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Update css/hasil-ppdb.css
  - Results display responsive
  - Table/list responsive
- [ ] Add media queries
- **Priority:** MEDIUM (Important for students)

---

### Priority 4: Other Pages 🟢

#### 13-23: biodata.html, data.html, kelompok-bermain.html, dll...
- [ ] Add hamburger menu HTML
- [ ] Add hamburger CSS
- [ ] Add media queries
- **Priority:** LOW

---

## 📝 Template Untuk Copy-Paste (Hamburger Menu)

### ✂️ CSS to Add (Copy ke akhir setiap CSS file):
```css
/* Hamburger Menu */
.hamburger-menu {
  display: none;
  flex-direction: column;
  cursor: pointer;
  gap: 5px;
  z-index: 1001;
}

.hamburger-menu span {
  width: 25px;
  height: 3px;
  background-color: white;
  border-radius: 2px;
  transition: all 0.3s ease;
}

.hamburger-menu.active span:nth-child(1) {
  transform: rotate(45deg) translate(10px, 10px);
}

.hamburger-menu.active span:nth-child(2) {
  opacity: 0;
}

.hamburger-menu.active span:nth-child(3) {
  transform: rotate(-45deg) translate(7px, -7px);
}

@media (max-width: 768px) {
  .hamburger-menu {
    display: flex;
  }

  .paud-nav {
    display: none;
    position: absolute;
    top: 50px;
    left: 0;
    right: 0;
    flex-direction: column;
    background-color: #051f40;
    padding: 20px;
    z-index: 1000;
  }

  .paud-nav.active {
    display: flex;
  }
}
```

### ✂️ HTML to Add (Di dalam header):
```html
<div class="hamburger-menu" id="hamburgerMenu">
  <span></span>
  <span></span>
  <span></span>
</div>
```

### ✂️ JavaScript to Add (Di akhir file sebelum </body>):
```javascript
const hamburgerMenu = document.getElementById('hamburgerMenu');
const paudNav = document.querySelector('.paud-nav');

hamburgerMenu.addEventListener('click', () => {
  hamburgerMenu.classList.toggle('active');
  paudNav.classList.toggle('active');
});

const navLinks = paudNav.querySelectorAll('a');
navLinks.forEach(link => {
  link.addEventListener('click', () => {
    hamburgerMenu.classList.remove('active');
    paudNav.classList.remove('active');
  });
});

document.addEventListener('click', (e) => {
  if (!e.target.closest('.paud-header')) {
    hamburgerMenu.classList.remove('active');
    paudNav.classList.remove('active');
  }
});
```

---

## 🎯 Implementation Order (Recommended)

**Week 1 - CRITICAL (5 pages):**
1. ✅ index.html (DONE)
2. ppdb.html
3. login.html
4. galeri.html
5. agenda.html

**Week 2 - HIGH (3 pages):**
6. information.html
7. Dashboard.html
8. hasil-ppdb.html

**Week 3 - MEDIUM (3 pages):**
9. profil-sekolah.html
10. kontak.html
11. Dashboard-Siswa.html

**Week 4+ - LOW (8+ pages):**
12+. post.html, post-agenda.html, biodata.html, data.html, dll...

---

## 💾 Progress Tracker

```
✅ index.html ............................ 100%

🟥 PRIORITY 1 GROUP:
  ppdb.html .............................. 0%
  login.html ............................. 0%
  galeri.html ............................ 0%
  agenda.html ............................ 0%

🟧 PRIORITY 2 GROUP:
  information.html ....................... 0%
  Dashboard.html ......................... 0%
  hasil-ppdb.html ........................ 0%

🟨 PRIORITY 3 GROUP:
  profil-sekolah.html .................... 0%
  kontak.html ............................ 0%
  Dashboard-Siswa.html ................... 0%

🟩 PRIORITY 4 GROUP:
  post.html .............................. 0%
  post-agenda.html ....................... 0%
  biodata.html ........................... 0%
  data.html .............................. 0%
  kelompok-bermain.html .................. 0%
  StrukturOrganisasi.html ................ 0%
  sambutan-kepala-sekolah.html ........... 0%
  ruangkelas.html ........................ 0%
  kelola-data.html ....................... 0%
  Lainnya ............................... 0%

TOTAL: 1/22 (4.5%) ✅
TARGET: 5/22 (22.7%) dalam 1 minggu
```

---

## 🧪 QA Checklist per Halaman

Sebelum (✅) sebagai done:
- [ ] Tested di Desktop
- [ ] Tested di Tablet (768px)
- [ ] Tested di Mobile (480px)
- [ ] Tested di Extra Small (360px)
- [ ] All links clickable
- [ ] No horizontal scroll
- [ ] Images scales properly
- [ ] Text readable (font size OK)
- [ ] Hamburger menu works
- [ ] Dropdown menus work mobile
- [ ] Form fields accessible
- [ ] Buttons reachable (44px min)

---

## 📚 Reference Documents

Baca panduan lengkap untuk detail:
1. `RESPONSIVE_DESIGN_GUIDE.md` - Detailed step-by-step
2. `MEDIA_QUERY_QUICK_REFERENCE.md` - CSS snippets & patterns
3. `VISUAL_GUIDE_RESPONSIVE.md` - Visual mockup
4. `RESPONSIVE_UPDATE_SUMMARY.md` - What was changed

---

## 🚀 Next Action

**👉 START WITH:** ppdb.html atau login.html  
**WHY:** These are critical pages that affect user experience most

Would you like me to start implementing responsive design for ppdb.html? Just say YES! 🎯

