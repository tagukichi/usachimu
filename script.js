const navToggle = document.querySelector('.nav-toggle');
const nav = document.getElementById('main-nav');
const yearEl = document.getElementById('year');

if (yearEl) {
  yearEl.textContent = new Date().getFullYear();
}

if (navToggle && nav) {
  const toggleMenu = () => {
    const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
    navToggle.setAttribute('aria-expanded', String(!isExpanded));
    nav.setAttribute('aria-hidden', String(isExpanded));
    nav.classList.toggle('is-open');
  };

  navToggle.addEventListener('click', toggleMenu);
  navToggle.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      toggleMenu();
    }
  });

  nav.setAttribute('aria-hidden', 'true');
}
