// 横スクロールのスライダー（TOP の実績の事例カード）。
// 中身は CSS の scroll-snap で横に並べ、ここでは
//   ・前へ／次へボタン（端ではボタンを無効化）
//   ・下の進み具合バー
//   ・PC でのドラッグ操作（ドラッグ後のクリックは無効化）
// を足すだけ。スマホは指でそのまま横にスワイプできる。

export function initSliders() {
  document.querySelectorAll('[data-slider]').forEach(setup);
}

function setup(root) {
  const track = root.querySelector('[data-slider-track]');
  if (!track) return;
  const prev = root.querySelector('[data-slider-prev]');
  const next = root.querySelector('[data-slider-next]');
  const bar  = root.querySelector('[data-slider-bar]');

  const step = () => {
    const card = track.firstElementChild;
    if (!card) return track.clientWidth;
    const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
    return card.getBoundingClientRect().width + gap;
  };

  const update = () => {
    const max = track.scrollWidth - track.clientWidth;
    const atStart = track.scrollLeft <= 2;
    const atEnd = track.scrollLeft >= max - 2;
    if (prev) prev.disabled = atStart;
    if (next) next.disabled = atEnd;
    root.classList.toggle('is-static', max <= 2);
    if (bar) {
      const visible = track.clientWidth / track.scrollWidth;
      const progress = max > 0 ? track.scrollLeft / max : 0;
      bar.style.width = `${Math.max(visible, 0.08) * 100}%`;
      bar.style.transform = `translateX(${progress * (1 / Math.max(visible, 0.08) - 1) * 100}%)`;
    }
  };

  prev?.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
  next?.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
  track.addEventListener('scroll', update, { passive: true });
  window.addEventListener('resize', update);

  // キーボード：フォーカス中は ← → で移動
  track.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight') { e.preventDefault(); track.scrollBy({ left: step(), behavior: 'smooth' }); }
    if (e.key === 'ArrowLeft')  { e.preventDefault(); track.scrollBy({ left: -step(), behavior: 'smooth' }); }
  });

  // PC（マウス）のドラッグ
  let down = false, startX = 0, startLeft = 0, moved = false;
  track.addEventListener('pointerdown', (e) => {
    if (e.pointerType !== 'mouse' || e.button !== 0) return;
    down = true; moved = false;
    startX = e.clientX; startLeft = track.scrollLeft;
    track.classList.add('is-grabbing');
  });
  window.addEventListener('pointermove', (e) => {
    if (!down) return;
    const dx = e.clientX - startX;
    if (Math.abs(dx) > 4) moved = true;
    track.scrollLeft = startLeft - dx;
  });
  window.addEventListener('pointerup', () => {
    if (!down) return;
    down = false;
    track.classList.remove('is-grabbing');
  });
  track.addEventListener('click', (e) => {
    if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; }
  }, true);

  update();
}
