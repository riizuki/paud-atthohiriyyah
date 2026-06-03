// Header icon interactions
document.addEventListener('DOMContentLoaded', function() {
  const searchIcon = document.querySelector('.search-icon');
  const bellIcon = document.querySelector('.bell-icon');
  const cartIcon = document.querySelector('.cart-icon');

  // Simple click handlers (dapat dikembangkan lebih lanjut)
  if (searchIcon) {
    searchIcon.addEventListener('click', function() {
      alert('Search functionality akan segera hadir!');
    });
  }

  if (bellIcon) {
    bellIcon.addEventListener('click', function() {
      alert('Notifikasi akan segera hadir!');
    });
  }

  if (cartIcon) {
    cartIcon.addEventListener('click', function() {
      alert('Keranjang akan segera hadir!');
    });
  }

  // Gallery image lightbox (opsional)
  const galleryItems = document.querySelectorAll('.gallery-item img');
  galleryItems.forEach(item => {
    item.addEventListener('click', function() {
      // Dapat dikembangkan dengan lightbox library
      console.log('Gallery item clicked:', this.alt);
    });
  });
});
