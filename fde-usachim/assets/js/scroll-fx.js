// Scroll FX — FV のスクロールロック（sticky pin）演出。
//   1. .hero-scroll を pin 化（200vh）。この区間はスクロールしても
//      Hero が viewport に固定され「ロック」されているように見える。
//   2. 進行度 p (0→1) に応じて球体が画面中央へ寄りながら拡大。
//      p>0.62 で球体はフェードアウト、Hero テキストも退場。
//   3. 球体が消え切るころ、Concept セクションがフェードインで現れる。
// transform / opacity のみ（コンポジタ処理）で軽量。
// prefers-reduced-motion では pin せず通常表示。

export function initScrollFx() {
  const wrap    = document.querySelector('[data-hero-scroll]');
  const hero    = wrap ? wrap.querySelector('.hero') : null;
  const globe   = document.querySelector('.hero__globe');
  const inner   = document.querySelector('.hero__inner');
  const concept = document.querySelector('#concept');
  if (!wrap || !hero || !globe) return;

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return; // 演出なし。CSS も is-pinned を付けないので通常表示

  wrap.classList.add('is-pinned');
  globe.style.willChange = 'transform, opacity';
  if (concept) {
    concept.classList.add('is-fx'); // pin に重ねてクロスフェードさせる
  }

  // 球体を中央へ寄せるための水平オフセット(px)を実測（scale1/x0 の状態で）
  let offsetToCenter = 0;
  const measure = () => {
    globe.style.setProperty('--fx-scale', '1');
    globe.style.setProperty('--fx-x', '0px');
    globe.style.setProperty('--fx-y', '0px');
    const r = globe.getBoundingClientRect();
    offsetToCenter = window.innerWidth / 2 - (r.left + r.width / 2);
  };

  let ticking = false;
  const update = () => {
    ticking = false;
    const total = wrap.offsetHeight - window.innerHeight;
    const scrolled = -wrap.getBoundingClientRect().top;
    const p = total > 0 ? Math.min(1, Math.max(0, scrolled / total)) : 0;

    // 球体：中央へ寄せつつ拡大、後半でフェードアウト
    const isPc = window.innerWidth >= 1101;
    const scale = 1 + p * (isPc ? 1.4 : 1.1);
    const fade = p < 0.62 ? 1 : Math.max(0, 1 - (p - 0.62) / 0.3);
    globe.style.setProperty('--fx-scale', scale.toFixed(3));
    globe.style.setProperty('--fx-fade', fade.toFixed(3));
    if (isPc) {
      globe.style.setProperty('--fx-x', `${(offsetToCenter * p).toFixed(1)}px`);
    } else {
      globe.style.setProperty('--fx-x', '0px');
    }

    // Hero テキスト：上へ抜けつつフェード（前半で退場）
    if (inner) {
      inner.style.opacity = Math.max(0, 1 - p * 1.6).toFixed(3);
      inner.style.transform = `translateY(${(-p * 60).toFixed(1)}px)`;
    }

    // Concept：球体が中央で拡大→消えるのに合わせ、同じ位置に重ねてフェードイン
    // （pin 進行度 p 0.5→0.9 でクロスフェード）
    if (concept) {
      const cp = Math.min(1, Math.max(0, (p - 0.5) / 0.4));
      concept.style.opacity = cp.toFixed(3);
    }
  };

  const onScroll = () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  };

  const onResize = () => {
    measure();
    update();
  };

  measure();
  update();
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onResize, { passive: true });
}
