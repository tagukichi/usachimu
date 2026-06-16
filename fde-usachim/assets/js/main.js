import { initNav } from './nav.js';
import { initReveal } from './reveal.js';
import { initHeroTime } from './hero-time.js';
import { initPolys } from './hero-poly.js';
import { initGlobe } from './hero-globe.js';

const boot = () => {
  initNav();
  initReveal();
  initHeroTime();
  initPolys();
  initGlobe();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
