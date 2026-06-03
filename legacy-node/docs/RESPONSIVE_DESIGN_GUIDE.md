# Panduan Responsive Design untuk Mobile & Tablet

## ✅ Apa yang Sudah Diupdate di index.html

### 1. **CSS Responsif** (css/index.css)
Sudah ditambahkan media queries lengkap:
- **768px dan bawah** (Tablet & Tablet Kecil)
- **480px dan bawah** (Mobile Phone)
- **360px dan bawah** (Mobile Sangat Kecil)

### 2. **HTML Elements** (index.html)
- ✅ Hamburger menu button untuk mobile
- ✅ JavaScript untuk toggle menu
- ✅ Dropdown menu responsif

### 3. **Fitur Responsive yang Ditambahkan:**
- ✅ Header menyesuaikan ukuran font dan padding
- ✅ Navigasi berubah jadi hamburger menu di mobile
- ✅ Logo teks tersembunyi di tablet/mobile
- ✅ Swiper slider height berkurang di mobile
- ✅ Card grid menjadi 2 kolom/1 kolom di mobile
- ✅ Welcome section stacking vertikal
- ✅ Footer berubah ke layout kolom tunggal
- ✅ Semua ukuran font dan spacing disesuaikan

---

## 🔄 Untuk Menerapkan ke Halaman Lain

Ikuti langkah berikut untuk setiap halaman HTML:

### **Langkah 1: Update CSS File**

1. Buka file CSS untuk halaman tersebut (misal: `css/galeri.css`)
2. Salin dan tempel struktur media queries dari akhir `css/index.css` ke file CSS tersebut
3. Sesuaikan selector class/id sesuai dengan struktur HTML halaman tersebut

**Contoh struktur media queries minimal:**

```css
/* Responsive Tablet (768px) */
@media (max-width: 768px) {
  .paud-header {
    padding: 10px 15px;
  }
  
  .paud-nav {
    display: none;
    position: absolute;
    /* ... */
  }
  
  .paud-nav.active {
    display: flex;
  }
  
  /* Sesuaikan selector lain sesuai halaman */
}

/* Responsive Mobile (480px) */
@media (max-width: 480px) {
  /* Ukuran lebih kecil */
}
```

### **Langkah 2: Update HTML File**

Untuk setiap halaman HTML:

1. **Tambahkan hamburger menu** di `<header>`:
```html
<!-- Hamburger Menu for Mobile -->
<div class="hamburger-menu" id="hamburgerMenu">
  <span></span>
  <span></span>
  <span></span>
</div>
```

2. **Tambahkan CSS untuk hamburger** ke file CSS halaman:
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

3. **Tambahkan JavaScript** di akhir halaman (sebelum `</body>`):
```javascript
// Hamburger Menu Functionality for Mobile
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

## 📱 Ukuran Breakpoint yang Digunakan

| Breakpoint | Perangkat | Ukuran |
|-----------|-----------|--------|
| 900px | Desktop/Tablet Besar | > 900px |
| 768px | Tablet/Tablet Kecil | 600px - 768px |
| 480px | Mobile Phone | 320px - 480px |
| 360px | Mobile Sangat Kecil | < 360px |

---

## 🎯 Daftar Halaman yang Perlu Diupdate

- [ ] galeri.html + css/galeri.css
- [ ] information.html + css/information.css
- [ ] agenda.html + css/agenda.css
- [ ] profil-sekolah.html + css/profil-sekolah.css
- [ ] kontak.html + css/kontak.css
- [ ] ppdb.html + css/ppdb.css
- [ ] login.html + css/login.css
- [ ] login-siswa.html + css/login.css
- [ ] post.html + css/post.css
- [ ] post-agenda.html + css/post-agenda.css
- [ ] Dashboard.html + css/Dashboard.css
- [ ] Dashboard-Siswa.html + css/Dashboard-Siswa.css
- [ ] dan halaman lainnya...

---

## 🧪 Testing Responsif

### **Cara mengecek di browser:**
1. Buka halaman di browser
2. Tekan `F12` atau `Ctrl+Shift+I` untuk membuka Developer Tools
3. Klik ikon "toggle device toolbar" atau tekan `Ctrl+Shift+M`
4. Pilih device (iPhone, Samsung, dll) atau ukuran custom
5.Periksa apakah layout terlihat dengan baik di berbagai ukuran

### **Ukuran untuk ditest:**
- iPhone SE: 375px
- iPhone 12/13: 390px
- iPhone 14 Pro Max: 430px
- Samsung S21: 360px
- iPad Mini: 768px
- iPad Pro: 1024px

---

## 💡 Tips & Best Practices

### ✅ Untuk Setiap Halaman:
1. **Header/Navigation** - Gunakan hamburger menu untuk mobile
2. **Gambar** - Gunakan `object-fit: cover` dan set max-width
3. **Grid/Flex** - Ubah `grid-template-columns` ke lebih sedikit kolom
4. **Font Size** - Kurangi ukuran font di mobile
5. **Padding/Margin** - Kurangi spacing untuk mobile

### ❌ Hindari:
- ❌ Menggunakan `width: 100vw` (gunakan `width: 100%` atau `max-width`)
- ❌ Font size terlalu kecil (minimum 14px untuk body, 12px untuk small text)
- ❌ Padding terlalu besar pada mobile
- ❌ Element yang terlalu besar (logo, image)

### 📐 Ukuran Minimum untuk Mobile:
- Hamburger menu: 25px × 25px
- Touch target (button, link): minimal 44px × 44px
- Font body: 14-16px
- Line height: 1.5 atau lebih

---

## 🚀 Cara Cepat Mengaplikasikan

### **Menggunakan CSS yang Sama untuk Beberapa Halaman:**

Jika beberapa halaman menggunakan selector yang sama, cukup:
1. Buat file CSS terpisah untuk responsive: `css/responsive.css`
2. Masukkan semua media queries di sana
3. Include file tersebut di semua halaman HTML:
   ```html
   <link rel="stylesheet" href="css/responsive.css">
   ```

---

## 📊 Checklist Implementasi

Untuk setiap halaman, pastikan Anda sudah:

- [ ] Menambahkan hamburger menu HTML
- [ ] Menambahkan hamburger menu CSS
- [ ] Menambahkan hamburger menu JavaScript
- [ ] Menambahkan media query @768px
- [ ] Menambahkan media query @480px
- [ ] Menambahkan media query @360px (opsional)
- [ ] Testing di berbagai ukuran device
- [ ] Mengecek alignment text/image di mobile
- [ ] Mengecek spacing/padding di mobile
- [ ] Mengecek dropdown menus

---

## 📝 Contoh Real: Menerapkan ke galeri.html

Jika file `css/galeri.css` ada, Anda perlu:

1. **Buka galeri.html** - pastikan ada hamburger menu di header
2. **Buka css/galeri.css** - tambahkan di akhir file:
   ```css
   @media (max-width: 768px) {
     /* Sesuaikan gallery grid, card size, dll */
     .gallery-grid {
       grid-template-columns: repeat(2, 1fr);
     }
   }
   
   @media (max-width: 480px) {
     .gallery-grid {
       grid-template-columns: 1fr;
     }
   }
   ```

---

Jika ada pertanyaan atau butuh bantuan menerapkan ke halaman lain, silakan beri tahu! 🎉
