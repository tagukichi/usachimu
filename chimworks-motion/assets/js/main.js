import { initNav } from './nav.js';
import { initReveal } from './reveal.js';
import { initHeroTime } from './hero-time.js';
import { initGlobe } from './hero-globe.js';
import { initTriFields } from './tri-field.js';
import { initSliders } from './slider.js';
import { initImgFade } from './img-fade.js';
import { initScrollFx } from './scroll-fx.js';
import { initMotion } from './motion.js';

const boot = () => {
  initNav();
  initHeroTime();
  initGlobe();
  initTriFields();
  initSliders();
  initImgFade();

  // GSAP + Lenis のフルモーション。使えない環境・reduced-motion では
  // 従来の IntersectionObserver 実装へフォールバック。
  if (!initMotion()) {
    initReveal();
    initScrollFx();
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
