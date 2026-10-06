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

  // 特設ページ（3D）だけ Three.js を遅延読込（他ページに 670KB を配らない）
  if (document.querySelector('[data-sp-scene]')) {
    import('./special-scene.js').then((m) => m.initSpecialScene()).catch(() => {});
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
