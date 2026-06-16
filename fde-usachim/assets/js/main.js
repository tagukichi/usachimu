import { initNav } from './nav.js';
import { initReveal } from './reveal.js';
import { initHeroTime } from './hero-time.js';
import { initGlobe } from './hero-globe.js';
import { initTriFields } from './tri-field.js';

const boot = () => {
  initNav();
  initReveal();
  initHeroTime();
  initGlobe();
  initTriFields();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
