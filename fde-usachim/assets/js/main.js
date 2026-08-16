import { initNav } from './nav.js';
import { initReveal } from './reveal.js';
import { initHeroTime } from './hero-time.js';
import { initGlobe } from './hero-globe.js';
import { initTriFields } from './tri-field.js';
import { initSliders } from './slider.js';
import { initImgFade } from './img-fade.js';
import { initScrollFx } from './scroll-fx.js';
import { initGsapFx } from './gsap-fx.js';

const boot = () => {
  initNav();
  initHeroTime();
  initGlobe();
  initTriFields();
  initSliders();
  initImgFade();

  // GSAP が使えれば全アニメーションを GSAP + ScrollTrigger に委譲。
  // 読み込めない環境・reduced-motion では従来実装へフォールバック。
  if (!initGsapFx()) {
    initReveal();
    initScrollFx();
  }
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
