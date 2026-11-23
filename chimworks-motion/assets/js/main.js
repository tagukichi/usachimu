import { initNav } from './nav.js';
import { initReveal } from './reveal.js';
import { initHeroTime } from './hero-time.js';
import { initGlobe } from './hero-globe.js';
import { initMotion } from './motion.js';

const boot = () => {
  initNav();
  initHeroTime();
  initGlobe();

  // GSAP + Lenis の控えめなモーション。使えない環境・reduced-motion では
  // 従来の IntersectionObserver 実装へフォールバック。
  if (!initMotion()) {
    initReveal();
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
