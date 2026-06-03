// Hamburger + dropdown shared functionality

// search box – handle every instance (desktop/mobile)
const searchIcons = document.querySelectorAll('.search-icon');
const searchBox = document.getElementById('searchBox');
const closeSearch = document.getElementById('closeSearch');
if (searchIcons.length && searchBox && closeSearch) {
  searchIcons.forEach(icon => {
    icon.addEventListener('click', () => searchBox.style.display = 'block');
  });
  closeSearch.addEventListener('click', () => searchBox.style.display = 'none');
}

// dropdown community
const dropdownContainers = document.querySelectorAll('.dropdown-container');

dropdownContainers.forEach(container => {
  const dropdownSocial = container.querySelector('.dropdown-social');
  const link = container.querySelector('a');

  link.addEventListener('click', (e) => {
    e.preventDefault();
    // close other open dropdowns
    dropdownContainers.forEach(otherContainer => {
      const otherDropdown = otherContainer.querySelector('.dropdown-social');
      if (otherDropdown !== dropdownSocial) {
        otherDropdown.style.display = 'none';
      }
    });
    dropdownSocial.style.display = dropdownSocial.style.display === 'block' ? 'none' : 'block';
  });
});

// notification bell (all instances)
const bellIcons = document.querySelectorAll('.bell-icon');
const dropdown = document.getElementById('notification-dropdown');
if (bellIcons.length && dropdown) {
  bellIcons.forEach(icon => {
    icon.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
  });
  document.addEventListener('click', (e) => {
    if (!dropdown.contains(e.target) && ![...bellIcons].includes(e.target)) {
      dropdown.style.display = 'none';
    }
  });
}

// hamburger menu
const hamburgerMenu = document.getElementById('hamburgerMenu');
const paudNav = document.querySelector('.paud-nav');

// cart icon navigation (desktop + mobile)
const cartIcons = document.querySelectorAll('.cart-icon');
cartIcons.forEach(icon => {
  icon.addEventListener('click', () => {
    window.location.href = 'hasil-ppdb.html';
  });
});

if (hamburgerMenu && paudNav) {
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
  // also close when tapping icons inside the mobile menu
  const mobileMenuIcons = document.querySelectorAll('.mobile-nav-icons img');
  mobileMenuIcons.forEach(icon => {
    icon.addEventListener('click', () => {
      hamburgerMenu.classList.remove('active');
      paudNav.classList.remove('active');
    });
  });
  // also close when clicking any desktop icon (search/bell/cart)
  const desktopIcons = document.querySelectorAll('.paud-icons img');
  desktopIcons.forEach(icon => {
    icon.addEventListener('click', () => {
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
}

// also handle rog-nav for Dashboard
const rogNav = document.querySelector('.rog-nav');
if (hamburgerMenu && rogNav) {
  hamburgerMenu.addEventListener('click', () => {
    hamburgerMenu.classList.toggle('active');
    rogNav.classList.toggle('active');
  });
  const rogNavLinks = rogNav.querySelectorAll('a');
  rogNavLinks.forEach(link => {
    link.addEventListener('click', () => {
      hamburgerMenu.classList.remove('active');
      rogNav.classList.remove('active');
    });
  });
  // also close when tapping icons inside the mobile menu
  const rogMobileMenuIcons = document.querySelectorAll('.rog-nav .mobile-nav-icons i');
  rogMobileMenuIcons.forEach(icon => {
    icon.addEventListener('click', () => {
      hamburgerMenu.classList.remove('active');
      rogNav.classList.remove('active');
    });
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.rog-header')) {
      hamburgerMenu.classList.remove('active');
      rogNav.classList.remove('active');
    }
  });
}
