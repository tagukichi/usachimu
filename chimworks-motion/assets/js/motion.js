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

/* ---- モーショントークン（motion.css の --mo-* と対になる値） ----------
   duration / easing / stagger をここに集約し、サイト全体の演出が
   同じリズムで鳴るようにする。 */
const MO = {
  fast: 0.2,   // ホバー反応・カーソル・下線
  base: 0.6,   // リビール・カード登場
  hero: 1.1,   // FV・メニュー・ページ遷移
  out:  'expo.out',        // 標準の登場
  snap: 'back.out(1.7)',   // ポップな止まり際（要所限定）
  stagChar: 0.03,
  stagCard: 0.08,
};

export function initMotion() {
  const gsap = window.gsap;
  const ScrollTrigger = window.ScrollTrigger;
  if (!gsap || !ScrollTrigger) return false;

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return false;

  gsap.registerPlugin(ScrollTrigger);
  gsap.defaults({ ease: MO.out, duration: MO.base });

  // CSS 側のフォールバック用エンターアニメーションを無効化するフラグ
  document.documentElement.classList.add('has-motion');

  const lenis = initLenis(gsap, ScrollTrigger);
  initCursor(gsap);

  // モバイル Safari 等はリロード時に前回のスクロール位置を復元する。
  // ピン演出（scrub）が途中の進行度から初期化されるとフェード用
  // トゥイーンが誤った開始値をキャプチャし、FV の球体が表示されなく
  // なるため、FV のあるページは常にトップから開始する。
  if (document.querySelector('.hero') && 'scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);
    if (lenis) lenis.scrollTo(0, { immediate: true, force: true });
  }

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

  // Phase 1 — 手触り
  cardTilt(gsap);
  // Phase 2 — セクションの見せ場
  heroPointerParallax(gsap);
  conceptScrub(ScrollTrigger);
  aboutTimeline(gsap, ScrollTrigger);
  bigCta(gsap);
  // Phase 3 — サイト体験の接続
  fullscreenMenu(gsap, lenis);
  pageTransition(gsap);

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

/* ---- 1. ローダー（モダンゲーム UI 風） --------------------------------
   ヘアラインの進捗バーが滑らかに満ちる。100% で LOADED に切り替え、
   バーが一度強く光ってからフェードアウト。 */
function initLoader(gsap, lenis, heroTl) {
  const loader = document.querySelector('[data-loader]');
  const count  = document.querySelector('[data-loader-count]');
  const bar    = document.querySelector('[data-loader-bar]');
  const text   = document.querySelector('[data-loader-text]');

  const done = () => {
    if (lenis) lenis.start();
    if (heroTl) heroTl.play();
  };

  if (!loader || !count || !bar) {
    done();
    return;
  }

  // ローダーはセッションの最初の 1 回だけ。ページ遷移のたびに
  // 再生されると煩わしいため、2 回目以降は本編から始める。
  // （プライベートモード等で sessionStorage が落ちても動くように try/catch）
  const SEEN = 'chimworks:loaded';
  let seen = false;
  try {
    seen = window.sessionStorage.getItem(SEEN) === '1';
    window.sessionStorage.setItem(SEEN, '1');
  } catch (e) {
    seen = false;
  }

  if (seen) {
    loader.remove();
    done();
    return;
  }

  loader.classList.add('is-on');
  if (lenis) lenis.stop();

  const state = { n: 0 };
  const tl = gsap.timeline({ onComplete: done });

  tl.to(state, {
    n: 100,
    duration: 1.6,
    ease: 'power2.inOut',
    onUpdate: () => {
      count.textContent = String(Math.round(state.n));
    },
  }, 0);
  tl.to(bar, { width: '100%', duration: 1.6, ease: 'power2.inOut' }, 0);

  // 100%：LOADED に切り替え、バーがひと呼吸強く光る
  tl.add(() => {
    if (text) text.textContent = 'LOADED';
  });
  tl.to(bar, {
    boxShadow: '0 0 42px rgba(10, 228, 72, 1)',
    duration: 0.22,
    yoyo: true,
    repeat: 1,
    ease: 'power2.inOut',
  }, '+=0.05');

  // フェード＋わずかなスケールで本編へ（モダンゲームのシーン遷移風）
  tl.to(loader, {
    autoAlpha: 0,
    scale: 1.04,
    duration: 0.6,
    ease: 'power2.inOut',
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

  // 対象ごとにカーソルのラベルを変える（触れるものの意味を伝える）
  const label = document.querySelector('[data-cursor-label]');
  const LABELS = [
    ['.blog2-card, .blog-card, .side-post__link', 'READ'],
    ['a[target="_blank"]', 'OPEN ↗'],
    ['[data-slider], .svc-work', 'DRAG'],
  ];

  const labelFor = (target) => {
    for (const [sel, text] of LABELS) {
      if (target.closest(sel)) return text;
    }
    return '';
  };

  document.addEventListener('mouseover', (e) => {
    const text = label ? labelFor(e.target) : '';
    if (text) {
      label.textContent = text;
      wrap.classList.add('is-label');
      wrap.classList.remove('is-hover');
      return;
    }
    if (e.target.closest(hoverables)) wrap.classList.add('is-hover');
  });

  document.addEventListener('mouseout', (e) => {
    if (label && labelFor(e.target)) wrap.classList.remove('is-label');
    if (e.target.closest(hoverables)) wrap.classList.remove('is-hover');
  });
}

/* ---- 3a. Hero イントロ（paused で構築、ローダー後に再生） --------------
   gsap.com の "Animate anything" 風：クリーム色の極太文字が 1 文字ずつ
   ランダム順にポップイン（back オーバーシュート）し、装飾シェイプが
   弾みながら現れる。ホバーで文字が跳ねる。 */
function buildHeroIntro(gsap) {
  const hero = document.querySelector('.hero');
  if (!hero) return null;

  const lines  = hero.querySelectorAll('.hero__statement-line');
  const lede   = hero.querySelector('.hero__lede');
  const scroll = hero.querySelector('.hero__scroll');
  const globe  = hero.querySelector('.hero__globe');

  // 文字分割。グラデ clip は transform された子 span で壊れる（緑のブロブ化）
  // ため、-em は clip をやめて各文字へ補間したソリッドカラーを与える。
  const allChars = [];
  lines.forEach((line) => {
    allChars.push(...splitChars(line));
  });

  hero.querySelectorAll('.hero__statement-em').forEach((em) => {
    em.classList.add('is-split');
    const chars = em.querySelectorAll('.split-char');
    // 白背景で読める彩度・明度のグリーン→シアン
    const colorAt = gsap.utils.interpolate(['#2fae0a', '#0a9b4b', '#0086b8']);
    chars.forEach((c, i) => {
      c.style.color = colorAt(chars.length > 1 ? i / (chars.length - 1) : 0.5);
    });
  });

  const tl = gsap.timeline({ paused: true });

  if (allChars.length) {
    tl.from(allChars, {
      yPercent: () => gsap.utils.random(-140, -60),
      rotation: () => gsap.utils.random(-28, 28),
      scale: 0.2,
      autoAlpha: 0,
      duration: 0.9,
      ease: 'back.out(1.7)',
      stagger: { each: 0.04, from: 'random' },
    }, 0);
  }

  if (lede)   tl.from(lede,   { y: 30, autoAlpha: 0, duration: 0.9 }, '-=0.5');
  if (scroll) tl.from(scroll, { autoAlpha: 0, duration: 0.8 }, '-=0.4');
  if (globe) {
    // 配置 transform / opacity は CSS の --fx-* 変数経由（衝突回避）
    tl.fromTo(globe, { '--fx-fade': 0 }, { '--fx-fade': 1, duration: 1.6, ease: 'power2.out' }, 0.35);
  }

  // ホバーで文字が跳ねる（PC のみ）
  if (window.matchMedia('(pointer: fine)').matches) {
    allChars.forEach((ch) => {
      let busy = false;
      ch.addEventListener('mouseenter', () => {
        if (busy) return;
        busy = true;
        gsap.timeline({ onComplete: () => { busy = false; } })
          .to(ch, { yPercent: -22, scale: 1.12, rotation: gsap.utils.random(-10, 10), duration: 0.16, ease: 'power2.out' })
          .to(ch, { yPercent: 0, scale: 1, rotation: 0, duration: 0.9, ease: 'elastic.out(1, 0.35)' });
      });
    });
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

  // fromTo で開始値を明示：未定義の CSS 変数を to() が「開始値 0」として
  // キャプチャし、トップへ戻ると球体が 0 倍に縮んで消えるバグの対策
  tl.fromTo(globe, { '--fx-scale': 1, '--fx-x': '0px' }, {
    '--fx-scale': () => (isPc() ? 2.6 : 2.2),
    '--fx-x': () => (isPc() ? `${measureOffset().toFixed(1)}px` : '0px'),
    duration: 1,
    immediateRender: false,
  }, 0);
  // fromTo で両端を明示：初回描画がどの進行度で起きても開始値 1 が保証され、
  // トップへ戻れば球体が必ず再表示される
  tl.fromTo(
    globe,
    { '--fx-fade': 1 },
    { '--fx-fade': 0, duration: 0.3, immediateRender: false, overwrite: 'auto' },
    0.62
  );

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

/* ============================================================
   PHASE 1 — 手触りの底上げ
   ============================================================ */

/* ---- カードの 3D チルト＋光沢スイープ（PC のみ） ---------------------- */
function cardTilt(gsap) {
  if (!window.matchMedia('(pointer: fine)').matches) return;

  const cards = document.querySelectorAll('.svc-sub, .svc-feature, .blog2-card, .blog-card');
  if (!cards.length) return;

  cards.forEach((card) => {
    card.classList.add('tilt');
    // perspective は親ではなくカード自身に持たせ、レイアウトに影響させない
    gsap.set(card, { transformPerspective: 900 });

    const rotX = gsap.quickTo(card, 'rotationX', { duration: 0.5, ease: 'power3.out' });
    const rotY = gsap.quickTo(card, 'rotationY', { duration: 0.5, ease: 'power3.out' });

    card.addEventListener('mousemove', (e) => {
      const r = card.getBoundingClientRect();
      const px = (e.clientX - r.left) / r.width;
      const py = (e.clientY - r.top) / r.height;
      rotY((px - 0.5) * 7);
      rotX((0.5 - py) * 7);
      card.style.setProperty('--tilt-mx', `${(px * 100).toFixed(1)}%`);
      card.style.setProperty('--tilt-my', `${(py * 100).toFixed(1)}%`);
    });

    card.addEventListener('mouseleave', () => {
      rotX(0);
      rotY(0);
    });
  });
}

/* ============================================================
   PHASE 2 — セクションの見せ場
   ============================================================ */

/* ---- FV：マウス追従パララックス（球体とオーロラが逆方向に微視差） ----- */
function heroPointerParallax(gsap) {
  if (!window.matchMedia('(pointer: fine)').matches) return;

  const hero  = document.querySelector('.hero');
  const globe = document.querySelector('.hero__globe');
  // オーロラは CSS keyframe が transform を占有しているため、
  // 視差レイヤーにはグリッド層を使う（球体と逆方向に動かして奥行きを出す）
  const grid  = document.querySelector('.hero__grid');
  if (!hero || (!globe && !grid)) return;

  // 球体は --fx-* 経由で配置済みのため、ごく浅い translate を
  // 別変数（--px/--py）で足し込む（CSS 側で transform に合成）
  const layers = [];
  if (globe) layers.push({ el: globe, amt: -14 });
  if (grid)  layers.push({ el: grid,  amt: 22 });

  const setters = layers.map(({ el, amt }) => ({
    amt,
    x: gsap.quickSetter(el, '--px', 'px'),
    y: gsap.quickSetter(el, '--py', 'px'),
  }));

  const state = { mx: 0, my: 0 };

  hero.addEventListener('mousemove', (e) => {
    const r = hero.getBoundingClientRect();
    state.mx = (e.clientX - r.left) / r.width - 0.5;
    state.my = (e.clientY - r.top) / r.height - 0.5;
  }, { passive: true });

  hero.addEventListener('mouseleave', () => {
    state.mx = 0;
    state.my = 0;
  });

  // 慣性つきで追従
  const cur = { x: 0, y: 0 };
  gsap.ticker.add(() => {
    cur.x += (state.mx - cur.x) * 0.06;
    cur.y += (state.my - cur.y) * 0.06;
    setters.forEach((s) => {
      s.x(cur.x * s.amt);
      s.y(cur.y * s.amt);
    });
  });
}

/* ---- Concept：スクラブ・テキストリビール ------------------------------
   本文を単語単位に分割し、スクロール進行に応じて薄グレー→本来色へ
   順に染めていく（読み進む実感を動きにする）。 */
function conceptScrub(ScrollTrigger) {
  const body = document.querySelector('.concept__body');
  if (!body) return;

  // テキストノードを単語（日本語は文字）単位で span 化
  const walker = document.createTreeWalker(body, NodeFilter.SHOW_TEXT, null);
  const nodes = [];
  while (walker.nextNode()) nodes.push(walker.currentNode);

  const words = [];
  nodes.forEach((node) => {
    const text = node.nodeValue;
    if (!text.trim()) return;
    const frag = document.createDocumentFragment();
    // 英単語はまとめ、日本語は 1 文字ずつ（自然な改行位置を保つ）
    const parts = text.match(/[A-Za-z0-9._%+-]+|\s+|[^A-Za-z0-9\s]/g) || [];
    parts.forEach((part) => {
      if (!part.trim()) {
        frag.appendChild(document.createTextNode(part));
        return;
      }
      const span = document.createElement('span');
      span.className = 'scrub-word';
      span.textContent = part;
      frag.appendChild(span);
      words.push(span);
    });
    node.parentNode.replaceChild(frag, node);
  });

  if (!words.length) return;

  ScrollTrigger.create({
    trigger: body,
    start: 'top 78%',
    end: 'bottom 62%',
    scrub: true,
    onUpdate: (self) => {
      const lit = Math.round(self.progress * words.length);
      words.forEach((w, i) => w.classList.toggle('is-lit', i < lit));
    },
  });
}

/* ---- About：タイムライン描画（線が伸び、ドットが順に点灯） ------------ */
function aboutTimeline(gsap, ScrollTrigger) {
  const tl = document.querySelector('.about2__tl');
  if (!tl) return;

  const rows = tl.querySelectorAll('.about2__tl-row');
  const setProgress = gsap.quickSetter(tl, '--tl-progress');

  ScrollTrigger.create({
    trigger: tl,
    start: 'top 80%',
    end: 'bottom 70%',
    scrub: 0.4,
    onUpdate: (self) => {
      setProgress(self.progress);
      const lit = Math.ceil(self.progress * rows.length);
      rows.forEach((row, i) => row.classList.toggle('is-lit', i < lit));
    },
  });
}

/* ---- Contact：巨大タイポ CTA（ホバーで文字が波打つ） ------------------ */
function bigCta(gsap) {
  const text = document.querySelector('[data-bigcta-text]');
  const cta  = document.querySelector('[data-bigcta]');
  if (!text || !cta) return;

  const chars = [];
  const source = text.textContent;
  text.textContent = '';
  Array.from(source).forEach((ch) => {
    const span = document.createElement('span');
    span.className = 'bigcta__char';
    span.textContent = ch;
    text.appendChild(span);
    chars.push(span);
  });

  if (!window.matchMedia('(pointer: fine)').matches) return;

  let wave = null;
  cta.addEventListener('mouseenter', () => {
    if (wave) wave.kill();
    wave = gsap.fromTo(
      chars,
      { yPercent: 0 },
      {
        yPercent: -18,
        duration: 0.34,
        ease: 'sine.inOut',
        stagger: { each: 0.035, yoyo: true, repeat: 1 },
      }
    );
  });
}

/* ============================================================
   PHASE 3 — サイト体験の接続
   ============================================================ */

/* ---- フルスクリーンメニュー -------------------------------------------
   開閉の状態管理（ARIA / フォーカストラップ / ESC）は nav.js が担当。
   ここは data-nav-open の変化を監視して演出だけを担う。 */
function fullscreenMenu(gsap, lenis) {
  const panel = document.querySelector('[data-nav-panel]');
  if (!panel) return;

  const curtain = panel.querySelector('.nav-panel__curtain');
  const items   = panel.querySelectorAll('.nav-panel__item a');
  const meta    = panel.querySelector('.nav-panel__meta');
  const close   = panel.querySelector('.nav-panel__close');

  const hidden = { yPercent: 110, autoAlpha: 0 };
  gsap.set([...items], hidden);
  if (meta) gsap.set(meta, { autoAlpha: 0, y: 20 });
  if (close) gsap.set(close, { autoAlpha: 0, rotate: -90 });

  let tl = null;

  const open = () => {
    if (tl) tl.kill();
    if (lenis) lenis.stop();
    tl = gsap.timeline();
    if (curtain) {
      tl.fromTo(curtain,
        { scaleY: 0, transformOrigin: 'bottom center' },
        { scaleY: 1, duration: 0.55, ease: 'power4.inOut' }, 0);
    }
    tl.to(items, {
      yPercent: 0,
      autoAlpha: 1,
      duration: 0.7,
      ease: MO.out,
      stagger: 0.06,
    }, 0.3);
    if (meta)  tl.to(meta,  { autoAlpha: 1, y: 0, duration: 0.5 }, 0.6);
    if (close) tl.to(close, { autoAlpha: 1, rotate: 0, duration: 0.5 }, 0.4);
  };

  const shut = () => {
    if (tl) tl.kill();
    tl = gsap.timeline({ onComplete: () => { if (lenis) lenis.start(); } });
    tl.to([...items].reverse(), {
      yPercent: -60,
      autoAlpha: 0,
      duration: 0.32,
      ease: 'power3.in',
      stagger: 0.035,
    }, 0);
    if (meta)  tl.to(meta,  { autoAlpha: 0, duration: 0.2 }, 0);
    if (close) tl.to(close, { autoAlpha: 0, duration: 0.2 }, 0.1);
    if (curtain) {
      tl.to(curtain, {
        scaleY: 0,
        transformOrigin: 'top center',
        duration: 0.5,
        ease: 'power4.inOut',
        onComplete: () => gsap.set(items, hidden),
      }, 0.28);
    } else {
      tl.add(() => gsap.set(items, hidden));
    }
  };

  const observer = new MutationObserver(() => {
    panel.getAttribute('data-nav-open') === 'true' ? open() : shut();
  });
  observer.observe(panel, { attributes: true, attributeFilter: ['data-nav-open'] });
}

/* ---- ページ遷移カーテン -----------------------------------------------
   内部リンクのクリックでカーテンを閉じてから遷移し、到着後に開く。
   WordPress の MPA 構成のまま「ひとつのサイト体験」に見せる。 */
function pageTransition(gsap) {
  const curtain = document.querySelector('[data-curtain]');
  if (!curtain) return;

  // 到着時：カーテンを上へ抜く
  gsap.set(curtain, { scaleY: 1, transformOrigin: 'top center' });
  gsap.to(curtain, {
    scaleY: 0,
    duration: 0.6,
    ease: 'power4.inOut',
    onComplete: () => gsap.set(curtain, { scaleY: 0 }),
  });

  const isInternal = (a) => {
    if (!a || !a.href) return false;
    if (a.target === '_blank' || a.hasAttribute('download')) return false;
    const url = new URL(a.href, window.location.href);
    if (url.origin !== window.location.origin) return false;
    // 同一ページ内アンカーは対象外（Lenis のスムーススクロールに任せる）
    if (url.pathname === window.location.pathname && url.hash) return false;
    if (url.href === window.location.href) return false;
    return !/\.(zip|pdf|jpe?g|png|gif|svg|webp|mp4)$/i.test(url.pathname);
  };

  document.addEventListener('click', (e) => {
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
    const a = e.target.closest('a');
    if (!isInternal(a)) return;

    e.preventDefault();
    const href = a.href;
    gsap.set(curtain, { transformOrigin: 'bottom center' });
    gsap.to(curtain, {
      scaleY: 1,
      duration: 0.5,
      ease: 'power4.inOut',
      onComplete: () => { window.location.href = href; },
    });
  });

  // ブラウザバックで bfcache から復帰したときにカーテンが残らないように
  window.addEventListener('pageshow', (e) => {
    if (e.persisted) gsap.set(curtain, { scaleY: 0 });
  });
}
