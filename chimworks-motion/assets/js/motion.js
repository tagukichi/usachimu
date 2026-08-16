// CHIMWORKS MOTION — GSAP フル駆動のモーションオーケストレーション。
//   0. Lenis スムーススクロール（ScrollTrigger と同期）
//   1. ローダー：カウントアップ → カーテンリフト → Hero イントロへ連鎖
//   2. カスタムカーソル（dot + ring、リンクホバーで拡大）
//   3. Hero：文字分割の 3D 立ち上がり、ピン＋scrub で球体拡大→Concept
//   4. セクション見出し：文字カスケード＋メタのスクランブル
//   5. マーキー：無限ループ＋スクロール速度に反応して加速・スキュー
//   6. カード類の batch リビール、画像のクリップリビール
//   7. カウントアップ、パララックス、マグネットホバー
// GSAP 不在・prefers-reduced-motion では false を返し、main.js が
// 従来の IntersectionObserver 実装へフォールバックする。

export function initMotion() {
  const gsap = window.gsap;
  const ScrollTrigger = window.ScrollTrigger;
  if (!gsap || !ScrollTrigger) return false;

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return false;

  gsap.registerPlugin(ScrollTrigger);
  gsap.defaults({ ease: 'power3.out', duration: 0.9 });

  const lenis = initLenis(gsap, ScrollTrigger);
  initCursor(gsap);

  const heroTl = buildHeroIntro(gsap); // paused — loader 完了後に再生
  initLoader(gsap, lenis, heroTl);

  heroPin(gsap);
  sectionHeads(gsap);
  marquees(gsap, ScrollTrigger);
  batchReveals(gsap, ScrollTrigger);
  clipReveals(gsap);
  countUps(gsap, ScrollTrigger);
  parallax(gsap);
  magneticButtons(gsap);
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

/* ---- 文字分割ユーティリティ（SplitText の簡易版・多バイト対応） ------- */
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

/* ---- 1. ローダー ------------------------------------------------------ */
function initLoader(gsap, lenis, heroTl) {
  const loader = document.querySelector('[data-loader]');
  const count  = document.querySelector('[data-loader-count]');
  const bar    = document.querySelector('[data-loader-bar]');

  const done = () => {
    if (lenis) lenis.start();
    if (heroTl) heroTl.play();
  };

  if (!loader || !count || !bar) {
    done();
    return;
  }

  loader.classList.add('is-on');
  if (lenis) lenis.stop();

  const state = { n: 0 };
  const tl = gsap.timeline({ onComplete: done });

  tl.to(state, {
    n: 100,
    duration: 1.05,
    ease: 'power2.inOut',
    onUpdate: () => {
      count.textContent = String(Math.round(state.n));
    },
  }, 0);
  tl.to(bar, { scaleX: 1, duration: 1.05, ease: 'power2.inOut' }, 0);
  tl.to(loader, {
    yPercent: -100,
    duration: 0.75,
    ease: 'power4.inOut',
    onComplete: () => loader.remove(),
  }, '+=0.1');
}

/* ---- 2. カスタムカーソル ---------------------------------------------- */
function initCursor(gsap) {
  if (!window.matchMedia('(pointer: fine)').matches) return;

  const wrap = document.querySelector('[data-cursor]');
  const dot  = document.querySelector('[data-cursor-dot]');
  const ring = document.querySelector('[data-cursor-ring]');
  if (!wrap || !dot || !ring) return;

  wrap.classList.add('is-on');
  document.body.classList.add('has-mcursor');

  const dotX  = gsap.quickTo(dot,  'x', { duration: 0.12, ease: 'power3.out' });
  const dotY  = gsap.quickTo(dot,  'y', { duration: 0.12, ease: 'power3.out' });
  const ringX = gsap.quickTo(ring, 'x', { duration: 0.4,  ease: 'power3.out' });
  const ringY = gsap.quickTo(ring, 'y', { duration: 0.4,  ease: 'power3.out' });

  window.addEventListener('mousemove', (e) => {
    dotX(e.clientX);
    dotY(e.clientY);
    ringX(e.clientX);
    ringY(e.clientY);
  }, { passive: true });

  const hoverables = 'a, button, .btn, .site-cta, [data-nav-toggle]';
  document.addEventListener('mouseover', (e) => {
    if (e.target.closest(hoverables)) wrap.classList.add('is-hover');
  });
  document.addEventListener('mouseout', (e) => {
    if (e.target.closest(hoverables)) wrap.classList.remove('is-hover');
  });
}

/* ---- 3a. Hero イントロ（paused で構築、ローダー後に再生） -------------- */
function buildHeroIntro(gsap) {
  const hero = document.querySelector('.hero');
  if (!hero) return null;

  const lines  = hero.querySelectorAll('.hero__statement-line');
  const lede   = hero.querySelector('.hero__lede');
  const scroll = hero.querySelector('.hero__scroll');
  const globe  = hero.querySelector('.hero__globe');

  const tl = gsap.timeline({ paused: true });

  lines.forEach((line, i) => {
    line.classList.add('split-line');
    const chars = splitChars(line);
    if (!chars.length) return;
    tl.from(chars, {
      yPercent: 120,
      rotateX: -40,
      autoAlpha: 0,
      duration: 1.0,
      ease: 'power4.out',
      stagger: 0.035,
    }, i * 0.16);
  });

  if (lede)   tl.from(lede,   { y: 30, autoAlpha: 0, duration: 0.9 }, '-=0.55');
  if (scroll) tl.from(scroll, { autoAlpha: 0, duration: 0.8 }, '-=0.4');
  if (globe) {
    // 配置 transform / opacity は CSS の --fx-* 変数経由（衝突回避）
    tl.fromTo(globe, { '--fx-fade': 0 }, { '--fx-fade': 1, duration: 1.6, ease: 'power2.out' }, 0.35);
  }
  return tl;
}

/* ---- 3b. Hero ピン（scrub） ------------------------------------------ */
function heroPin(gsap) {
  const wrap    = document.querySelector('[data-hero-scroll]');
  const hero    = wrap ? wrap.querySelector('.hero') : null;
  const globe   = document.querySelector('.hero__globe');
  const inner   = document.querySelector('.hero__inner');
  const concept = document.querySelector('#concept');
  if (!wrap || !hero || !globe) return;

  wrap.classList.add('is-pinned');
  if (concept) concept.classList.add('is-fx');

  const isPc = () => window.innerWidth >= 1101;

  const measureOffset = () => {
    gsap.set(globe, { '--fx-scale': 1, '--fx-x': '0px', '--fx-y': '0px' });
    const r = globe.getBoundingClientRect();
    return window.innerWidth / 2 - (r.left + r.width / 2);
  };

  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: wrap,
      start: 'top top',
      end: 'bottom bottom',
      scrub: 0.6,
      invalidateOnRefresh: true,
    },
    defaults: { ease: 'none' },
  });

  tl.to(globe, {
    '--fx-scale': () => (isPc() ? 2.6 : 2.2),
    '--fx-x': () => (isPc() ? `${measureOffset().toFixed(1)}px` : '0px'),
    duration: 1,
  }, 0);
  tl.to(globe, { '--fx-fade': 0, duration: 0.3, overwrite: 'auto' }, 0.62);

  if (inner) {
    tl.to(inner, { y: -80, autoAlpha: 0, skewY: -2, duration: 0.55 }, 0);
  }
  if (concept) {
    tl.fromTo(concept, { autoAlpha: 0 }, { autoAlpha: 1, duration: 0.4 }, 0.5);
  }
}

/* ---- 4. セクション見出し（文字カスケード＋スクランブル） --------------- */
const SCRAMBLE_CHARS = 'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿABCDEFGHJKLMNPQRSTUVWXYZ0123456789#/\\<>';

function scrambleTo(gsap, el) {
  const orig = el.textContent;
  const len = Array.from(orig).length;
  const state = { p: 0 };
  gsap.to(state, {
    p: 1,
    duration: 0.9,
    ease: 'power2.out',
    onUpdate: () => {
      const settled = Math.floor(state.p * len);
      let out = '';
      Array.from(orig).forEach((ch, i) => {
        if (i < settled || ch === ' ') {
          out += ch;
        } else {
          out += SCRAMBLE_CHARS[Math.floor(Math.random() * SCRAMBLE_CHARS.length)];
        }
      });
      el.textContent = out;
    },
    onComplete: () => {
      el.textContent = orig;
    },
  });
}

function sectionHeads(gsap) {
  document.querySelectorAll('.sec-head').forEach((head) => {
    const num   = head.querySelector('.sec-head__num');
    const title = head.querySelector('.sec-head__title');
    const meta  = head.querySelector('.sec-head__meta');

    let chars = [];
    if (title) {
      title.classList.add('split-line');
      chars = splitChars(title);
    }

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: head,
        start: 'top 84%',
        once: true,
        onEnter: () => {
          if (meta) scrambleTo(gsap, meta);
        },
      },
    });
    if (num) {
      tl.from(num, { scale: 1.6, autoAlpha: 0, duration: 0.7, ease: 'back.out(2)' }, 0);
    }
    if (chars.length) {
      tl.from(chars, {
        yPercent: 120,
        autoAlpha: 0,
        duration: 0.9,
        ease: 'power4.out',
        stagger: 0.03,
      }, 0.05);
    }
  });
}

/* ---- 5. マーキー（無限ループ＋速度反応） ------------------------------ */
function marquees(gsap, ScrollTrigger) {
  document.querySelectorAll('[data-marquee]').forEach((marquee) => {
    const track = marquee.querySelector('[data-marquee-track]');
    if (!track) return;

    const dir = parseInt(marquee.getAttribute('data-marquee-dir') || '1', 10) < 0 ? -1 : 1;

    // シームレスループに足りるまで内容を複製（最低 2 セット）
    const original = track.innerHTML;
    let sets = 1;
    while (sets < 6 && track.scrollWidth < window.innerWidth * 2) {
      track.innerHTML += original;
      sets += 1;
    }
    track.innerHTML += track.innerHTML; // 半分ずらしループ用にもう一倍

    const tween = gsap.fromTo(
      track,
      { xPercent: dir === 1 ? 0 : -50 },
      { xPercent: dir === 1 ? -50 : 0, duration: 22, ease: 'none', repeat: -1 }
    );

    // スクロール速度でループを加速・傾ける
    const skewTo = gsap.quickTo(track, 'skewX', { duration: 0.4, ease: 'power2.out' });
    let speed = 1;
    ScrollTrigger.create({
      trigger: marquee,
      start: 'top bottom',
      end: 'bottom top',
      onUpdate: (self) => {
        const v = self.getVelocity();
        speed = gsap.utils.clamp(0.6, 5, 1 + Math.abs(v) / 350);
        skewTo(gsap.utils.clamp(-8, 8, v / 220));
      },
    });
    gsap.ticker.add(() => {
      tween.timeScale(gsap.utils.interpolate(tween.timeScale(), speed, 0.08));
      speed = gsap.utils.interpolate(speed, 1, 0.04); // 減衰
    });
  });
}

/* ---- 6a. カード類の batch リビール ------------------------------------ */
function batchReveals(gsap, ScrollTrigger) {
  const els = document.querySelectorAll(
    [
      '.svc-block__head',
      '.svc-sub',
      '.svc-feature',
      '.about2__body',
      '.blog2-card',
      '.blog-card',
      '.contact__grid > *',
      '.single-blog__main',
      '.single-blog__side',
      '.site-footer__top',
    ].join(',')
  );
  if (!els.length) return;

  gsap.set(els, { y: 56, autoAlpha: 0, rotate: 0.6 });

  ScrollTrigger.batch(els, {
    start: 'top 88%',
    once: true,
    onEnter: (batch) =>
      gsap.to(batch, {
        y: 0,
        autoAlpha: 1,
        rotate: 0,
        duration: 0.9,
        ease: 'power4.out',
        stagger: 0.08,
        overwrite: true,
        // sticky サイドバーや CSS ホバー transform と衝突させない
        clearProps: 'all',
      }),
  });

  ScrollTrigger.refresh();
}

/* ---- 6b. 画像のクリップリビール --------------------------------------- */
function clipReveals(gsap) {
  document
    .querySelectorAll('.about2__portrait, .svc-block__media, .svc-sub__wide-media, .single-blog__eyecatch')
    .forEach((el) => {
      gsap.fromTo(
        el,
        { clipPath: 'inset(0 0 100% 0)' },
        {
          clipPath: 'inset(0 0 0% 0)',
          duration: 1.2,
          ease: 'power4.inOut',
          scrollTrigger: { trigger: el, start: 'top 85%', once: true },
          clearProps: 'clipPath',
        }
      );
    });
}

/* ---- 7a. 数値カウントアップ ------------------------------------------- */
function countUps(gsap, ScrollTrigger) {
  document.querySelectorAll('.svc-sub__stat-v').forEach((el) => {
    const target = parseInt(el.textContent.replace(/[^\d]/g, ''), 10);
    if (!Number.isFinite(target) || target <= 0) return;

    const state = { n: 0 };
    ScrollTrigger.create({
      trigger: el,
      start: 'top 85%',
      once: true,
      onEnter: () =>
        gsap.to(state, {
          n: target,
          duration: 1.6,
          ease: 'power2.out',
          onUpdate: () => {
            el.textContent = String(Math.round(state.n));
          },
        }),
    });
  });
}

/* ---- 7b. パララックス ------------------------------------------------- */
function parallax(gsap) {
  document
    .querySelectorAll('.about2__portrait img, .svc-block__media img, .single-blog__eyecatch img')
    .forEach((img) => {
      gsap.fromTo(
        img,
        { yPercent: -7 },
        {
          yPercent: 7,
          ease: 'none',
          scrollTrigger: {
            trigger: img.closest('figure, div') || img,
            start: 'top bottom',
            end: 'bottom top',
            scrub: true,
          },
        }
      );
    });

  document.querySelectorAll('.tri-field').forEach((field, i) => {
    gsap.fromTo(
      field,
      { y: i % 2 ? 60 : -60 },
      {
        y: i % 2 ? -60 : 60,
        ease: 'none',
        scrollTrigger: {
          trigger: field.closest('section') || field,
          start: 'top bottom',
          end: 'bottom top',
          scrub: true,
        },
      }
    );
  });
}

/* ---- 7c. マグネットホバー（PC のみ） ---------------------------------- */
function magneticButtons(gsap) {
  if (!window.matchMedia('(pointer: fine)').matches) return;

  document.querySelectorAll('.site-cta, .btn, .svc-feature__link, .nav-toggle').forEach((btn) => {
    const xTo = gsap.quickTo(btn, 'x', { duration: 0.35, ease: 'power3.out' });
    const yTo = gsap.quickTo(btn, 'y', { duration: 0.35, ease: 'power3.out' });

    btn.addEventListener('mousemove', (e) => {
      const r = btn.getBoundingClientRect();
      xTo(((e.clientX - r.left) / r.width - 0.5) * 14);
      yTo(((e.clientY - r.top) / r.height - 0.5) * 10);
    });
    btn.addEventListener('mouseleave', () => {
      xTo(0);
      yTo(0);
    });
  });
}

/* ---- 8. アンカーリンクを Lenis でスムーズに --------------------------- */
function anchorScroll(lenis) {
  if (!lenis) return;

  document.querySelectorAll('a[href*="#"]').forEach((a) => {
    a.addEventListener('click', (e) => {
      const url = new URL(a.href, window.location.href);
      if (url.pathname !== window.location.pathname || url.origin !== window.location.origin) return;
      const target = url.hash ? document.querySelector(url.hash) : null;
      if (!target) return;
      e.preventDefault();
      lenis.scrollTo(target, { offset: -72, duration: 1.4 });
    });
  });
}
