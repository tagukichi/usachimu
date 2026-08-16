// Tag the Hero with a time-of-day data attribute so CSS can swap
// the aurora gradient. Re-checks every 10 minutes while the page is open.

const PERIODS = [
  { name: 'dawn',    from: 5,  to: 8  },
  { name: 'morning', from: 8,  to: 11 },
  { name: 'day',     from: 11, to: 16 },
  { name: 'evening', from: 16, to: 19 },
  { name: 'dusk',    from: 19, to: 22 },
  // 22:00 – 5:00 → night
];

function periodFor(hour) {
  for (const p of PERIODS) {
    if (hour >= p.from && hour < p.to) return p.name;
  }
  return 'night';
}

export function initHeroTime() {
  const hero = document.querySelector('[data-hero-time]');
  if (!hero) return;

  const apply = () => {
    const h = new Date().getHours();
    hero.dataset.timeOfDay = periodFor(h);
  };

  apply();
  // 10分ごとに再評価（境界またぎ対応）
  setInterval(apply, 10 * 60 * 1000);
}
