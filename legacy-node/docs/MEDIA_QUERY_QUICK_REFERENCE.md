# 🎯 Quick Reference: Media Query Breakpoints

Copy-paste struktur ini untuk setiap halaman HTML baru!

---

## 📐 Breakpoint Definitions

```css
/* === BREAKPOINT 1: TABLET (768px & below) === */
@media (max-width: 768px) {
  /* Semua perubahan untuk tablet mulai di sini */
}

/* === BREAKPOINT 2: MOBILE (480px & below) === */
@media (max-width: 480px) {
  /* Perubahan lebih ekstrim untuk mobile phone */
}

/* === BREAKPOINT 3: VERY SMALL (360px & below) === */
@media (max-width: 360px) {
  /* Perubahan untuk phone sangat kecil */
}
```

---

## 🔧 Komponen HTML Standard untuk Setiap Halaman

### Header
```html
<header class="paud-header">
  <a href="index.html" class="paud-logo" style="text-decoration: none; color: rgb(255, 255, 255);">
    <img src="img/Logopaud.png" alt="paud Logo">
    <span>PAUD ATTHOHIRIYYAH</span>
  </a>

  <nav class="paud-nav">
    <!-- Menu items here -->
  </nav>

  <div class="paud-icons">
    <!-- Icons here -->
  </div>

  <!-- HAMBURGER MENU - HARUS ADA -->
  <div class="hamburger-menu" id="hamburgerMenu">
    <span></span>
    <span></span>
    <span></span>
  </div>
</header>
```

---

## 🎨 CSS Standard untuk Header Mobile

### Hamburger Menu CSS
```css
/* Hamburger Menu Button */
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
```

### Header pada 768px
```css
@media (max-width: 768px) {
  .paud-header {
    padding: 10px 15px;
    flex-wrap: wrap;
  }

  .paud-logo span {
    display: none;  /* Hide PAUD ATTHOHIRIYYAH text */
  }

  .paud-logo img {
    height: 28px;
    margin-right: 10px;
  }

  .paud-nav {
    display: none;  /* Hidden by default */
    position: absolute;
    top: 50px;
    left: 0;
    right: 0;
    flex-direction: column;
    background-color: #051f40;
    padding: 20px;
    gap: 15px;
    z-index: 1000;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
  }

  .paud-nav.active {
    display: flex;  /* Show when active */
  }

  .hamburger-menu {
    display: flex;  /* Show hamburger menu */
  }
}
```

### Header pada 480px
```css
@media (max-width: 480px) {
  .paud-header {
    padding: 8px 10px;
    gap: 10px;
  }

  .paud-logo img {
    height: 24px;
  }

  .paud-icons {
    gap: 8px;
  }

  .header-icon {
    width: 18px;
    height: 18px;
  }
}
```

---

## 🎬 JavaScript Standard untuk Mobile Menu

```javascript
// Hamburger Menu Functionality for Mobile
const hamburgerMenu = document.getElementById('hamburgerMenu');
const paudNav = document.querySelector('.paud-nav');

// Toggle hamburger menu on click
hamburgerMenu.addEventListener('click', () => {
  hamburgerMenu.classList.toggle('active');
  paudNav.classList.toggle('active');
});

// Close menu when a link is clicked
const navLinks = paudNav.querySelectorAll('a');
navLinks.forEach(link => {
  link.addEventListener('click', () => {
    hamburgerMenu.classList.remove('active');
    paudNav.classList.remove('active');
  });
});

// Close menu when clicking outside
document.addEventListener('click', (e) => {
  if (!e.target.closest('.paud-header')) {
    hamburgerMenu.classList.remove('active');
    paudNav.classList.remove('active');
  }
});
```

---

## 📱 Common Components Responsive Pattern

### Grid Items (Cards)
```css
/* Desktop */
.card-grid {
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

@media (max-width: 768px) {
  .card-grid {
    grid-template-columns: repeat(2, 1fr);  /* 2 columns */
    gap: 12px;
  }
}

@media (max-width: 480px) {
  .card-grid {
    grid-template-columns: 1fr;  /* 1 column */
    gap: 10px;
  }
}
```

### Flex Layout (Side-by-side)
```css
/* Desktop */
.container {
  display: flex;
  gap: 40px;
}

@media (max-width: 768px) {
  .container {
    flex-direction: column;  /* Stack vertically */
    gap: 20px;
  }
}

@media (max-width: 480px) {
  .container {
    flex-direction: column;
    gap: 15px;
  }
}
```

### Font Size Pattern
```css
/* Desktop */
.heading {
  font-size: 32px;
}

@media (max-width: 768px) {
  .heading {
    font-size: 24px;  /* 75% dari desktop */
  }
}

@media (max-width: 480px) {
  .heading {
    font-size: 20px;  /* 62.5% dari desktop */
  }
}
```

### Padding/Margin Pattern
```css
/* Desktop */
.section {
  padding: 60px 40px;
}

@media (max-width: 768px) {
  .section {
    padding: 40px 20px;  /* 67% dari desktop */
  }
}

@media (max-width: 480px) {
  .section {
    padding: 20px 10px;  /* 33% dari desktop */
  }
}
```

---

## 📋 Komponen yang Perlu Responsive pada Setiap Halaman

✅ **Wajib di-update:**
1. Header & Navigation
2. Main Content Grid/Flex
3. Cards/Items Layout
4. Footer
5. Forms (jika ada)

⚠️ **Sering terlupakan:**
1. Modal/Popup (sesuaikan width)
2. Table (jadi horizontal scroll atau vertical)
3. Image size
4. Button/Link size (minimum 44px)

---

## 🎯 Pixel Measurements Reference

| Element | Desktop | Tablet | Mobile |
|---------|---------|--------|--------|
| Header Height | auto | auto | auto |
| Header Padding | 10px 20px | 10px 15px | 8px 10px |
| Nav Font | 15px | 14px | 12px |
| H1 Title | 36px | 28px | 22px |
| H2 Title | 28px | 24px | 20px |
| Body Text | 16px | 14px | 12px |
| Small Text | 14px | 13px | 11px |
| Card Gap | 20px | 15px | 10px |
| Card Height | 200px | 150px | 130px |
| Button Size | 50px×16px | 40px×14px | 44px×12px |
| Section Padding | 60px 40px | 40px 20px | 20px 10px |

---

## ⚡ Performance Tips

### ✅ DO:
- ✅ Gunakan `max-width` bukan `width: 100vw`
- ✅ Gunakan relative units (em, rem) untuk font
- ✅ Gunakan `box-sizing: border-box`
- ✅ Min-height untuk touch targets: 44px
- ✅ Comfortable font size: 14px-16px minimum

### ❌ DON'T:
- ❌ Font size < 12px di mobile
- ❌ Padding < 8px di mobile
- ❌ Touch target < 44×44px
- ❌ Button/link padding terlalu kecil
- ❌ Horizontal scrolling (kecuali carousel)

---

## 🖼️ Image Responsive Pattern

```css
/* Desktop */
.image-container img {
  width: 100%;
  height: auto;
  max-width: 600px;
}

/* Tablet & Mobile (auto adjust) */
@media (max-width: 768px) {
  .image-container img {
    max-width: 100%;
  }
}
```

---

## 📬 Form Responsive Pattern

```css
/* Desktop */
.form-group {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

@media (max-width: 768px) {
  .form-group {
    grid-template-columns: 1fr;  /* Stack fields */
    gap: 15px;
  }
}

@media (max-width: 480px) {
  .form-group input,
  .form-group textarea {
    font-size: 16px;  /* Prevent zoom on iOS */
    padding: 12px;
    min-height: 44px;  /* Touch friendly */
  }
}
```

---

## 📞 Testing at Different Breakpoints

### Browser Developer Tools:
1. Press `F12` or `Ctrl+Shift+I`
2. Press `Ctrl+Shift+M` to toggle device mode
3. Select device or custom size

### Test Sizes:
- 320px (iPhone SE)
- 360px (Samsung S21)
- 375px (iPhone 12)
- 480px (Small mobile)
- 600px (Large tablet)
- 768px (iPad)
- 1024px (iPad Pro)
- 1200px (Desktop)

---

## 💾 Checklist per Halaman

Sebelum push ke production, pastikan:
- [ ] Header responsif
- [ ] Navigation mobile-friendly
- [ ] Content grid menyesuaikan
- [ ] Form fields stacked di mobile
- [ ] Images scaled properly
- [ ] Touch targets ≥ 44px
- [ ] Font readable (≥ 12px)
- [ ] No horizontal scroll
- [ ] Tested di 3+ device sizes
- [ ] Tested di Safari (iOS)
- [ ] Tested di Chrome (Android)

---

**Happy Coding! 🚀**
