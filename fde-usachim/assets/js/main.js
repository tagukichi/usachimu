import { initNav } from './nav.js';
import { initReveal } from './reveal.js';
import { initHeroCode } from './hero-code.js';

const boot = () => {
  initNav();
  initReveal();
  initHeroCode();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
