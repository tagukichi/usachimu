import { initNav } from './nav.js';
import { initReveal } from './reveal.js';
import { initHeroTime } from './hero-time.js';
import { initHeroPoly } from './hero-poly.js';

const boot = () => {
  initNav();
  initReveal();
  initHeroTime();
  initHeroPoly();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
