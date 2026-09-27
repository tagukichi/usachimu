// CHIMWORKS MOTION — 控えめな GSAP モーション（1 ページ構成向け）。
//   0. Lenis スムーススクロール（ScrollTrigger と同期、アンカー対応）
//   1. FV：文字ごとのポップイン、球体のフェードイン、マウス視差、
//      スクロールで球体がゆっくり大きくなりながら退く
//   2. セクション見出し・行・カードの控えめなリビール
// GSAP 不在・prefers-reduced-motion では false を返し、main.js が
// 従来の IntersectionObserver 実装へフォールバックする。

const MO = {
  out:  'expo.out',
  snap: 'back.out(1.7)',
};

export function initMotion() {
  const gsap = window.gsap;
  const ScrollTrigger = window.ScrollTrigger;
  if (!gsap || !ScrollTrigger) return false;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return false;

  gsap.registerPlugin(ScrollTrigger);
  gsap.defaults({ ease: MO.out, duration: 0.8 });
  document.documentElement.classList.add('has-motion');

  const lenis = initLenis(gsap, ScrollTrigger);

  heroIntro(gsap);
  heroGlobe(gsap);
  sectionHeads(gsap);
  batchReveals(gsap, ScrollTrigger);
  anchorScroll(lenis);

  return true;
}

/* ---- 0. Lenis ------------------------------------------------------- */
function initLenis(gsap, ScrollTrigger) {
  const Lenis = window.Lenis;
  if (!Lenis) return null;

  const lenis = new Lenis({
    duration: 1.1,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
  });
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((time) => lenis.raf(time * 1000));
  gsap.ticker.lagSmoothing(0);
  return lenis;
}

/* ---- 文字分割（SplitText の簡易版・多バイト対応） --------------------- */
function splitChars(root) {
  const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
  const nodes = [];
  while (walker.nextNode()) nodes.push(walker.currentNode);

  const chars = [];
  nodes.forEach((node) => {
    const text = node.nodeValue.replace(/\s+/g, ' ').trim();
    if (!text) return;
    const frag = document.createDocumentFragment();
    Array.from(text).forEach((ch) => {
      const span = document.createElement('span');
      span.className = 'split-char';
      span.textContent = ch;
      frag.appendChild(span);
      chars.push(span);
    });
    node.parentNode.replaceChild(frag, node);
  });
  return chars;
}

/* ---- 1a. FV イントロ --------------------------------------------------- */
function heroIntro(gsap) {
  const hero = document.querySelector('.hero');
  if (!hero) return;

  const lines  = hero.querySelectorAll('.hero__statement-line');
  const lede   = hero.querySelector('.hero__lede');
  const scroll = hero.querySelector('.hero__scroll');
  const globe  = hero.querySelector('.hero__globe');

  const chars = [];
  lines.forEach((line) => chars.push(...splitChars(line)));

  // グラデ文字は clip が transform 子要素で壊れるため、1 文字ずつ
  // 補間したソリッドカラーを与える
  hero.querySelectorAll('.hero__statement-em').forEach((em) => {
    em.classList.add('is-split');
    const emChars = em.querySelectorAll('.split-char');
    const colorAt = gsap.utils.interpolate(['#2fae0a', '#0a9b4b', '#0086b8']);
    emChars.forEach((c, i) => {
      c.style.color = colorAt(emChars.length > 1 ? i / (emChars.length - 1) : 0.5);
    });
  });

  const tl = gsap.timeline({ delay: 0.2 });
  if (chars.length) {
    tl.from(chars, {
      yPercent: () => gsap.utils.random(-120, -50),
      rotation: () => gsap.utils.random(-20, 20),
      scale: 0.3,
      autoAlpha: 0,
      duration: 0.9,
      ease: MO.snap,
      stagger: { each: 0.035, from: 'random' },
    }, 0);
  }
  if (lede)   tl.from(lede,   { y: 26, autoAlpha: 0, duration: 0.8 }, '-=0.45');
  if (scroll) tl.from(scroll, { autoAlpha: 0, duration: 0.7 }, '-=0.3');
  if (globe) {
    // 配置 transform / opacity は CSS の --fx-* 変数経由（衝突回避）
    tl.fromTo(globe, { '--fx-fade': 0 }, { '--fx-fade': 1, duration: 1.6, ease: 'power2.out' }, 0.3);
  }

  // PC はホバーで文字が跳ねる
  if (window.matchMedia('(pointer: fine)').matches) {
    chars.forEach((ch) => {
      let busy = false;
      ch.addEventListener('mouseenter', () => {
        if (busy) return;
        busy = true;
        gsap.timeline({ onComplete: () => { busy = false; } })
          .to(ch, { yPercent: -20, scale: 1.1, rotation: gsap.utils.random(-8, 8), duration: 0.16, ease: 'power2.out' })
          .to(ch, { yPercent: 0, scale: 1, rotation: 0, duration: 0.8, ease: 'elastic.out(1, 0.35)' });
      });
    });
  }
}

/* ---- 1b. FV 球体：マウス視差 + スクロールで退場 ------------------------ */
function heroGlobe(gsap) {
  const hero  = document.querySelector('.hero');
  const globe = document.querySelector('.hero__globe');
  if (!hero || !globe) return;

  // スクロールで少し大きくなりながら薄れていく。fromTo で両端を明示し、
  // 戻ったときに確実に元の見た目へ復帰させる
  gsap.fromTo(globe,
    { '--fx-scale': 1, '--fx-fade': 1 },
    {
      '--fx-scale': 1.18,
      '--fx-fade': 0,
      ease: 'none',
      immediateRender: false,
      scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 0.5 },
    });

  if (!window.matchMedia('(pointer: fine)').matches) return;

  const state = { mx: 0, my: 0 };
  hero.addEventListener('mousemove', (e) => {
    const r = hero.getBoundingClientRect();
    state.mx = (e.clientX - r.left) / r.width - 0.5;
    state.my = (e.clientY - r.top) / r.height - 0.5;
  }, { passive: true });
  hero.addEventListener('mouseleave', () => { state.mx = 0; state.my = 0; });

  const setX = gsap.quickSetter(globe, '--px', 'px');
  const setY = gsap.quickSetter(globe, '--py', 'px');
  const cur = { x: 0, y: 0 };
  gsap.ticker.add(() => {
    cur.x += (state.mx - cur.x) * 0.06;
    cur.y += (state.my - cur.y) * 0.06;
    setX(cur.x * -14);
    setY(cur.y * -14);
  });
}

/* ---- 2a. セクション見出し ---------------------------------------------- */
function sectionHeads(gsap) {
  document.querySelectorAll('.sec-head').forEach((head) => {
    const num   = head.querySelector('.sec-head__num');
    const title = head.querySelector('.sec-head__title');
    const meta  = head.querySelector('.sec-head__meta');
    const tl = gsap.timeline({ scrollTrigger: { trigger: head, start: 'top 84%', once: true } });
    if (num)   tl.from(num,   { x: -16, autoAlpha: 0, duration: 0.6 }, 0);
    if (title) tl.from(title, { y: 24, autoAlpha: 0, duration: 0.9 }, 0.05);
    if (meta)  tl.from(meta,  { x: 16, autoAlpha: 0, duration: 0.6 }, 0.15);
  });
}

/* ---- 2b. 行・カードの控えめなリビール ---------------------------------- */
function batchReveals(gsap, ScrollTrigger) {
  const els = document.querySelectorAll(
    ['.product__lead', '.product__main', '.product__media', '.product__point', '.svc-intro', '.svc-row', '.company-list__row', '.contact__aside', '.contact__form', '.site-footer__top',
     '.blog2-card', '.blog-card', '.single-blog__main', '.single-blog__side'].join(',')
  );
  if (!els.length) return;

  gsap.set(els, { y: 28, autoAlpha: 0 });
  ScrollTrigger.batch(els, {
    start: 'top 88%',
    once: true,
    onEnter: (batch) => gsap.to(batch, {
      y: 0, autoAlpha: 1, duration: 0.8, stagger: 0.07, overwrite: true,
      // sticky サイドバー等と衝突しないよう完了後に inline transform を除去
      clearProps: 'all',
    }),
  });
  ScrollTrigger.refresh();
}

/* ---- アンカーリンクを Lenis でスムーズに ------------------------------- */
function anchorScroll(lenis) {
  if (!lenis) return;
  document.querySelectorAll('a[href*="#"]').forEach((a) => {
    a.addEventListener('click', (e) => {
      const url = new URL(a.href, window.location.href);
      if (url.pathname !== window.location.pathname || url.origin !== window.location.origin) return;
      const target = url.hash ? document.querySelector(url.hash) : null;
      if (!target) return;
      e.preventDefault();
      lenis.scrollTo(target, { offset: -72, duration: 1.2 });
    });
  });
}
