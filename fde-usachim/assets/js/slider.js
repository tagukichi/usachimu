// Lightweight horizontal slider — prev/next arrows scroll the track by
// one item width. Used for the WORKS slider in Services.

export function initSliders() {
  const tracks = document.querySelectorAll('[data-slider]');
  tracks.forEach((track) => {
    const wrap = track.closest('.svc-works') || track.parentElement;
    if (!wrap) return;
    const prev = wrap.querySelector('[data-slider-prev]');
    const next = wrap.querySelector('[data-slider-next]');

    const step = () => {
      const first = track.querySelector(':scope > *');
      if (!first) return track.clientWidth * 0.8;
      const gap = parseFloat(getComputedStyle(track).columnGap || '16') || 16;
      return first.getBoundingClientRect().width + gap;
    };

    const update = () => {
      if (!prev || !next) return;
      const maxScroll = track.scrollWidth - track.clientWidth - 2;
      prev.disabled = track.scrollLeft <= 2;
      next.disabled = track.scrollLeft >= maxScroll;
    };

    prev?.addEventListener('click', () => {
      track.scrollBy({ left: -step(), behavior: 'smooth' });
    });
    next?.addEventListener('click', () => {
      track.scrollBy({ left: step(), behavior: 'smooth' });
    });
    track.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
    update();
  });
}
