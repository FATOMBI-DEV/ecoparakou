document.addEventListener('DOMContentLoaded', () => {
  // Toggle du menu mobile
  const menuToggle = document.getElementById('menuToggle');
  const mainNav = document.getElementById('mainNav');

  menuToggle.addEventListener('click', () => {
    const expanded = menuToggle.getAttribute('aria-expanded') === 'true' || false;
    menuToggle.setAttribute('aria-expanded', !expanded);
    mainNav.querySelector('ul').classList.toggle('show');
  });

  // Gestion dropdown Secteurs
  const dropdown = document.querySelector('.dropdown');
  const dropdownToggle = dropdown.querySelector('.dropdown-toggle');

  // Affichage au hover sur desktop
  dropdown.addEventListener('mouseenter', () => {
    if (window.innerWidth > 768) {
      dropdown.classList.add('open');
      dropdownToggle.setAttribute('aria-expanded', 'true');
    }
  });

  dropdown.addEventListener('mouseleave', () => {
    if (window.innerWidth > 768) {
      dropdown.classList.remove('open');
      dropdownToggle.setAttribute('aria-expanded', 'false');
    }
  });

  // Toggle au clic sur mobile
  dropdownToggle.addEventListener('click', (e) => {
    if (window.innerWidth <= 768) {
      e.preventDefault();
      const isOpen = dropdown.classList.toggle('open');
      dropdownToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }
  });
});
