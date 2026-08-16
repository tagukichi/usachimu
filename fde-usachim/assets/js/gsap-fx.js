// GSAP FX — サイト全体のアニメーションを GSAP + ScrollTrigger で駆動する。
//   1. Hero イントロ：ステートメントの行が順に立ち上がり、球体がフェードイン
//   2. Hero ピン演出：ScrollTrigger の scrub で球体拡大→Concept クロスフェード
//      （レイアウトは既存の .is-pinned / .is-fx の CSS を再利用し、
//        --fx-* CSS 変数を GSAP が補間する）
//   3. セクション見出し・カード類のスクロール連動リビール（batch）
//   4. 数値のカウントアップ、画像・三角形フィールドのパララックス
//   5. PC ではボタン類にマグネットホバー
// GSAP が読み込めない環境・prefers-reduced-motion では false を返し、
// 呼び出し側（main.js）が従来の IntersectionObserver 実装へフォールバックする。

export function initGsapFx() {
  const gsap = window.gsap;
  const ScrollTrigger = window.ScrollTrigger;
  if (!gsap || !ScrollTrigger) return false;

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return false;

  gsap.registerPlugin(ScrollTrigger);
  gsap.defaults({ ease: 'power3.out', duration: 0.9 });

  // モバイル Safari 等はリロード時に前回のスクロール位置を復元する。
  // ピン演出（scrub）が途中の進行度から初期化されると球体が表示され
  // なくなるため、FV のあるページは常にトップから開始する。
  if (document.querySelector('.hero') && 'scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
    window.scrollTo(0, 0);
  }

  heroIntro(gsap);
  heroPin(gsap, ScrollTrigger);
  sectionHeads(gsap);
  batchReveals(gsap, ScrollTrigger);
  countUps(gsap, ScrollTrigger);
  parallax(gsap);
  magneticButtons(gsap);

  return true;
}

/* ---- 1. Hero イントロ ------------------------------------------------ */
function heroIntro(gsap) {
  const hero = document.querySelector('.hero');
  if (!hero) return;

  const lines  = hero.querySelectorAll('.hero__statement-line');
  const lede   = hero.querySelector('.hero__lede');
  const scroll = hero.querySelector('.hero__scroll');
  const globe  = hero.querySelector('.hero__globe');

  const tl = gsap.timeline({ delay: 0.15 });

  if (lines.length) {
    tl.from(lines, {
      y: 48,
      autoAlpha: 0,
      duration: 1.1,
      ease: 'power4.out',
      stagger: 0.14,
    });
  }
  if (lede) {
    tl.from(lede, { y: 28, autoAlpha: 0, duration: 0.9 }, '-=0.65');
  }
  if (scroll) {
    tl.from(scroll, { autoAlpha: 0, duration: 0.8 }, '-=0.4');
  }
  // 球体の配置 transform / opacity は CSS の --fx-* 変数経由なので、
  // inline opacity ではなく --fx-fade をアニメーションする
  // （ピン演出側のフェードアウトと衝突させないため）
  if (globe) {
    tl.fromTo(globe, { '--fx-fade': 0 }, { '--fx-fade': 1, duration: 1.6, ease: 'power2.out' }, 0.3);
  }
}

/* ---- 2. Hero ピン（scrub） ------------------------------------------- */
function heroPin(gsap, ScrollTrigger) {
  const wrap    = document.querySelector('[data-hero-scroll]');
  const hero    = wrap ? wrap.querySelector('.hero') : null;
  const globe   = document.querySelector('.hero__globe');
  const inner   = document.querySelector('.hero__inner');
  const concept = document.querySelector('#concept');
  if (!wrap || !hero || !globe) return;

  wrap.classList.add('is-pinned');
  if (concept) concept.classList.add('is-fx');

  const isPc = () => window.innerWidth >= 1101;

  // 球体を画面中央へ寄せる水平オフセットを実測（scale1/x0 の状態で）
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

  // 球体：中央へ寄りつつ拡大（0→1）、後半でフェードアウト
  // fromTo で開始値を明示：未定義の CSS 変数を to() が「開始値 0」として
  // キャプチャし、トップへ戻ると球体が 0 倍に縮んで消えるバグの対策
  tl.fromTo(globe, { '--fx-scale': 1, '--fx-x': '0px' }, {
    '--fx-scale': () => (isPc() ? 2.4 : 2.1),
    '--fx-x': () => (isPc() ? `${measureOffset().toFixed(1)}px` : '0px'),
    duration: 1,
    immediateRender: false,
  }, 0);
  // fromTo で両端を明示：初回描画がどの進行度で起きても開始値 1 が保証され、
  // トップへ戻れば球体が必ず再表示される。
  // overwrite:'auto' — イントロの --fx-fade トゥイーンが残っていれば停止させる
  tl.fromTo(
    globe,
    { '--fx-fade': 1 },
    { '--fx-fade': 0, duration: 0.3, immediateRender: false, overwrite: 'auto' },
    0.62
  );

  // Hero テキスト：上へ抜けながら退場（前半）
  if (inner) {
    tl.to(inner, { y: -60, autoAlpha: 0, duration: 0.55 }, 0);
  }

  // Concept：球体が消えるのに合わせ、同じ位置にクロスフェードイン
  if (concept) {
    tl.fromTo(concept, { autoAlpha: 0 }, { autoAlpha: 1, duration: 0.4 }, 0.5);
  }
}

/* ---- 3. セクション見出し --------------------------------------------- */
function sectionHeads(gsap) {
  document.querySelectorAll('.sec-head').forEach((head) => {
    const num   = head.querySelector('.sec-head__num');
    const title = head.querySelector('.sec-head__title');
    const meta  = head.querySelector('.sec-head__meta');

    const tl = gsap.timeline({
      scrollTrigger: { trigger: head, start: 'top 82%', once: true },
    });
    if (num)   tl.from(num,   { x: -20, autoAlpha: 0, duration: 0.7 }, 0);
    if (title) tl.from(title, { y: 36, autoAlpha: 0, duration: 1, ease: 'power4.out' }, 0.08);
    if (meta)  tl.from(meta,  { x: 20, autoAlpha: 0, duration: 0.7 }, 0.2);
  });
}

/* ---- 4. カード類の batch リビール ------------------------------------ */
function batchReveals(gsap, ScrollTrigger) {
  const els = document.querySelectorAll(
    [
      '.svc-block__head',
      '.svc-sub',
      '.svc-feature',
      '.about2__portrait',
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

  gsap.set(els, { y: 40, autoAlpha: 0 });

  ScrollTrigger.batch(els, {
    start: 'top 86%',
    once: true,
    onEnter: (batch) =>
      gsap.to(batch, {
        y: 0,
        autoAlpha: 1,
        duration: 0.85,
        stagger: 0.09,
        overwrite: true,
        // 完了後に inline transform を除去：
        // position:sticky（ブログのサイドバー）や CSS ホバーの
        // translateY と衝突させない
        clearProps: 'all',
      }),
  });

  // 画面内に既にある要素の取りこぼし防止
  ScrollTrigger.refresh();
}

/* ---- 5. 数値カウントアップ ------------------------------------------- */
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

/* ---- 6. パララックス -------------------------------------------------- */
function parallax(gsap) {
  // 画像：ビューポート通過中に緩やかに縦移動
  document
    .querySelectorAll('.about2__portrait img, .svc-block__media img, .single-blog__eyecatch img')
    .forEach((img) => {
      gsap.fromTo(
        img,
        { yPercent: -6 },
        {
          yPercent: 6,
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

  // 三角形フィールド：背景としてゆっくり流す
  document.querySelectorAll('.tri-field').forEach((field, i) => {
    gsap.fromTo(
      field,
      { y: i % 2 ? 40 : -40 },
      {
        y: i % 2 ? -40 : 40,
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

/* ---- 7. マグネットホバー（PC のみ） ----------------------------------- */
function magneticButtons(gsap) {
  if (!window.matchMedia('(pointer: fine)').matches) return;

  document.querySelectorAll('.site-cta, .btn, .svc-feature__link').forEach((btn) => {
    const xTo = gsap.quickTo(btn, 'x', { duration: 0.35, ease: 'power3.out' });
    const yTo = gsap.quickTo(btn, 'y', { duration: 0.35, ease: 'power3.out' });

    btn.addEventListener('mousemove', (e) => {
      const r = btn.getBoundingClientRect();
      xTo(((e.clientX - r.left) / r.width - 0.5) * 10);
      yTo(((e.clientY - r.top) / r.height - 0.5) * 8);
    });
    btn.addEventListener('mouseleave', () => {
      xTo(0);
      yTo(0);
    });
  });
}
