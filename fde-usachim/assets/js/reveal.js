// Scroll-triggered fade-in via IntersectionObserver.
// Respects prefers-reduced-motion.

export function initReveal() {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const candidates = [
    '.hero',
    '.news__item',
    '.sec-head',
    '.why2',
    '.why2__pillar',
    '.about__grid',
    '.svc__item',
    '.wk__item',
    '.writing-card',
    '.contact__grid',
    '.site-footer__top',
  ].join(',');

  const els = document.querySelectorAll(candidates);
  if (els.length === 0) return;

  if (prefersReduced || !('IntersectionObserver' in window)) {
    els.forEach((el) => el.classList.add('reveal', 'is-visible'));
    return;
  }

  els.forEach((el, i) => {
    el.classList.add('reveal');
    el.style.transitionDelay = `${Math.min(i * 30, 180)}ms`;
  });

  const observer = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          obs.unobserve(entry.target);
        }
      });
    },
    { rootMargin: '0px 0px -8% 0px', threshold: 0.05 }
  );

  els.forEach((el) => observer.observe(el));
}
