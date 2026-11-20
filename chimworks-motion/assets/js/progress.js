// ページ上端の読み進み具合のバー（サービス詳細ページ。調速の LP と同じ）。
export function initProgress() {
  const bar = document.querySelector('[data-progress]');
  if (!bar) return;
  let ticking = false;
  const update = () => {
    const max = document.documentElement.scrollHeight - window.innerHeight;
    bar.style.setProperty('--p', (max > 0 ? Math.min(window.scrollY / max, 1) : 0).toFixed(4));
    ticking = false;
  };
  const request = () => {
    if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
  };
  update();
  window.addEventListener('scroll', request, { passive: true });
  window.addEventListener('resize', request, { passive: true });
}
