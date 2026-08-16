// Scattered triangle field — a calm cluster of translucent triangles that
// slowly drift, rotate and fade. Used as a corner accent throughout the site
// (the full sphere lives only in the hero).
//
// FV と同じ物語性：最初はグレーの欠片、セクションが視界に入ると
// 1枚ずつ順番に色づいていく（グレー → パレット色へブレンド）。
//
// Markup:  <svg data-tri-field data-tri-count="10" data-tri-seed="123">
//            <g class="tri-field__g"></g>
//          </svg>

const SVG_NS = 'http://www.w3.org/2000/svg';
const BOX = 120;     // matches viewBox 0 0 120 120
const MARGIN = 24;   // wrap margin so shards drift in/out of view

// hero-globe.js と同系のネオンパレット（gsap.com 風フレアカラー）
const PALETTE = [
  [10, 228, 72],   // neon green
  [171, 255, 132], // lime
  [0, 186, 226],   // cyan
  [44, 217, 255],  // sky
  [157, 149, 255], // violet
  [124, 124, 248], // indigo
  [255, 135, 9],   // orange
  [254, 197, 251], // pink
  [255, 215, 38],  // yellow
];

const GREY = [82, 90, 84]; // dark slate — 色づく前の欠片

const COLOR_STAGGER = 320;  // ms — 1枚ごとの色づき開始の間隔
const COLOR_DUR = 1500;     // ms — グレー→色 のブレンド時間

function makeRng(seed) {
  let s = (seed >>> 0) || 1;
  return () => {
    s = (Math.imul(s, 1664525) + 1013904223) >>> 0;
    return s / 4294967296;
  };
}

function blend(a, b, t) {
  return `rgb(${Math.round(a[0] + (b[0] - a[0]) * t)}, ${Math.round(a[1] + (b[1] - a[1]) * t)}, ${Math.round(a[2] + (b[2] - a[2]) * t)})`;
}

function easeOut(t) {
  return 1 - (1 - t) * (1 - t);
}

function setupField(svg, reduce) {
  const g = svg.querySelector('.tri-field__g');
  if (!g) return;

  const count = parseInt(svg.dataset.triCount, 10) || 12;
  const seed = parseInt(svg.dataset.triSeed, 10) || 1;
  const rng = makeRng(seed);

  const tris = [];
  for (let i = 0; i < count; i += 1) {
    const el = document.createElementNS(SVG_NS, 'polygon');
    g.appendChild(el);

    const size = 5 + rng() * 11;
    const corners = [];
    let ang = rng() * Math.PI * 2;
    for (let k = 0; k < 3; k += 1) {
      ang += ((0.7 + rng() * 0.8) * Math.PI * 2) / 3;
      const rr = size * (0.7 + rng() * 0.6);
      corners.push([Math.cos(ang) * rr, Math.sin(ang) * rr]);
    }

    tris.push({
      el,
      corners,
      x: rng() * BOX,
      y: rng() * BOX,
      vx: (rng() - 0.5) * 1.7,
      vy: (rng() - 0.5) * 1.7,
      rot: rng() * Math.PI * 2,
      spin: (rng() - 0.5) * 0.5,
      baseOp: 0.22 + rng() * 0.26,
      opPhase: rng() * Math.PI * 2,
      opSpeed: 0.3 + rng() * 0.5,
      filled: rng() < 0.65,
      color: PALETTE[Math.floor(rng() * PALETTE.length)],
      delay: i * COLOR_STAGGER + rng() * 400, // 色づき開始オフセット
    });
  }

  // 視界に入ったら色づきの時計をスタート
  let colorizeStart = null;
  if (reduce) {
    colorizeStart = -Infinity; // 即・完成形
  } else if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting && colorizeStart === null) {
            colorizeStart = performance.now();
            io.disconnect();
          }
        });
      },
      { threshold: 0.15 }
    );
    io.observe(svg);
  } else {
    colorizeStart = performance.now();
  }

  const draw = (tr, tSec, now) => {
    const c = Math.cos(tr.rot);
    const s = Math.sin(tr.rot);
    let pts = '';
    for (let k = 0; k < 3; k += 1) {
      const [ax, ay] = tr.corners[k];
      pts += `${(tr.x + ax * c - ay * s).toFixed(1)},${(tr.y + ax * s + ay * c).toFixed(1)} `;
    }
    tr.el.setAttribute('points', pts.trim());

    // グレー → 色 のブレンド進行
    let t = 0;
    if (colorizeStart !== null) {
      t = Math.max(0, Math.min(1, (now - colorizeStart - tr.delay) / COLOR_DUR));
    }
    const col = blend(GREY, tr.color, easeOut(t));
    tr.el.setAttribute('fill', col);
    tr.el.setAttribute('stroke', col);

    const op = tr.baseOp * (0.55 + 0.45 * Math.sin(tr.opPhase + tSec * tr.opSpeed));
    tr.el.setAttribute('fill-opacity', (tr.filled ? op : 0).toFixed(3));
    tr.el.setAttribute('stroke-opacity', Math.min(1, op * 1.2).toFixed(3));
  };

  if (reduce) {
    const now = performance.now();
    tris.forEach((tr) => draw(tr, 0, now + 1e9)); // 完全に色づいた静止状態
    return;
  }

  let last = performance.now();
  let raf = 0;
  const frame = (now) => {
    const dt = Math.min(0.05, (now - last) / 1000);
    last = now;
    const tSec = now / 1000;
    tris.forEach((tr) => {
      tr.x += tr.vx * dt;
      tr.y += tr.vy * dt;
      tr.rot += tr.spin * dt;
      if (tr.x < -MARGIN) tr.x = BOX + MARGIN;
      else if (tr.x > BOX + MARGIN) tr.x = -MARGIN;
      if (tr.y < -MARGIN) tr.y = BOX + MARGIN;
      else if (tr.y > BOX + MARGIN) tr.y = -MARGIN;
      draw(tr, tSec, now);
    });
    raf = requestAnimationFrame(frame);
  };
  raf = requestAnimationFrame(frame);

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      cancelAnimationFrame(raf);
    } else {
      last = performance.now();
      raf = requestAnimationFrame(frame);
    }
  });
}

export function initTriFields() {
  const svgs = document.querySelectorAll('[data-tri-field]');
  if (!svgs.length) return;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  svgs.forEach((svg) => setupField(svg, reduce));
}
