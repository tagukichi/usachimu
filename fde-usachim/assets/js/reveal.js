// Scroll-triggered fade-in via IntersectionObserver.
// Respects prefers-reduced-motion.

export function initReveal() {
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const candidates = [
    '.hero',
    '.section-head',
    '.positioning__values > li',
    '.service-card',
    '.approach__item',
    '.work-card',
    '.principles__list > li',
    '.tech-stack__list > div',
    '.service',
    '.about-excerpt__body',
    '.profile__body',
    '.front-cta',
    '.cta-block',
    '.single-work__section',
  ].join(',');

  const els = document.querySelectorAll(candidates);
  if (els.length === 0) return;

  if (prefersReduced || !('IntersectionObserver' in window)) {
    els.forEach((el) => el.classList.add('reveal', 'is-visible'));
    return;
  }

  els.forEach((el, i) => {
    el.classList.add('reveal');
    el.style.transitionDelay = `${Math.min(i * 40, 240)}ms`;
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
    { rootMargin: '0px 0px -10% 0px', threshold: 0.1 }
  );

  els.forEach((el) => observer.observe(el));
}
