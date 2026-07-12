// Scroll FX — FV の球体がスクロールで拡大し、Concept がフェードで現れる。
//   - 球体: scale 1 → ~2.1（進行 55% 以降でフェードアウト）
//   - Hero テキスト: パララックスで上へ抜けつつフェード
//   - #concept: 視界に近づくにつれ opacity 0 → 1 + せり上がり
// transform / opacity のみ（コンポジタ処理）なので軽量。
// 値は CSS 変数 (--fx-scale / --fx-fade) 経由で渡し、
// PC / SP それぞれの基本 transform と合成する。

export function initScrollFx() {
  const hero    = document.querySelector('.hero');
  const globe   = document.querySelector('.hero__globe');
  const inner   = document.querySelector('.hero__inner');
  const concept = document.querySelector('#concept');
  if (!hero || !globe) return;

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return; // 演出なし（既定の見た目のまま）

  globe.style.willChange = 'transform, opacity';

  let ticking = false;

  const update = () => {
    ticking = false;
    const vh = window.innerHeight || 800;
    const heroH = hero.offsetHeight || vh;

    // Hero 内の進行度 0..1
    const p = Math.min(1, Math.max(0, window.scrollY / (heroH * 0.9)));

    // 球体：拡大 → 終盤でフェードアウト
    const scale = 1 + p * 1.1;
    const fade = p < 0.55 ? 1 : Math.max(0, 1 - (p - 0.55) / 0.45);
    globe.style.setProperty('--fx-scale', scale.toFixed(3));
    globe.style.setProperty('--fx-fade', fade.toFixed(3));

    // Hero テキスト：ゆっくり上へ抜けつつフェード
    if (inner) {
      inner.style.opacity = Math.max(0, 1 - p * 1.1).toFixed(3);
      inner.style.transform = `translateY(${(-p * 48).toFixed(1)}px)`;
    }

    // Concept：近づくとフェードイン + せり上がり
    if (concept) {
      const r = concept.getBoundingClientRect();
      const cp = Math.min(1, Math.max(0, (vh * 0.88 - r.top) / (vh * 0.45)));
      concept.style.opacity = cp.toFixed(3);
      concept.style.transform = `translateY(${((1 - cp) * 48).toFixed(1)}px)`;
    }
  };

  const onScroll = () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll, { passive: true });
  update();
}
