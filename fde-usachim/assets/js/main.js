import { initNav } from './nav.js';
import { initReveal } from './reveal.js';
import { initHeroTime } from './hero-time.js';
import { initGlobe } from './hero-globe.js';
import { initTriFields } from './tri-field.js';
import { initSliders } from './slider.js';
import { initImgFade } from './img-fade.js';
import { initScrollFx } from './scroll-fx.js';

const boot = () => {
  initNav();
  initReveal();
  initHeroTime();
  initGlobe();
  initTriFields();
  initSliders();
  initImgFade();
  initScrollFx();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
