import { initNav } from './nav.js';
import { initReveal } from './reveal.js';

const boot = () => {
  initNav();
  initReveal();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
