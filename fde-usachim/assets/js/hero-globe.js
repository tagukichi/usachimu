// Hero globe — an Earth-like wireframe sphere rendered in pure JS + SVG.
// The sphere keeps its shape while continents (terrain) progressively
// "form" over time and then persist. Near hemisphere is bright, far side
// fades; back-facing terrain is culled. Slow axial-tilted rotation.

const SVG_NS = 'http://www.w3.org/2000/svg';

const CENTER = 100;   // viewBox 0..200
const S = 66;         // projected sphere scale (limb ~ this radius)
const DIST = 2.6;     // perspective camera distance (in sphere radii)
const TILT = 0.41;    // ~23.5deg axial tilt

// --- wireframe density ---
const PARALLELS = [-60, -40, -20, 0, 20, 40, 60].map((d) => (d * Math.PI) / 180);
const MERIDIAN_COUNT = 12;
const SEG = 44; // points per grid line

// --- terrain timing ---
const REVEAL_STEP_MS = 220; // a new terrain dot is born this often
const REVEAL_EASE = 4;      // a dot eases in over this many steps

// Deterministic PRNG so the continents look identical every load.
function makeRng(seed) {
  let s = seed >>> 0;
  return () => {
    s = (Math.imul(s, 1664525) + 1013904223) >>> 0;
    return s / 4294967296;
  };
}

function sph(lat, lng) {
  const cl = Math.cos(lat);
  return { x: cl * Math.cos(lng), y: Math.sin(lat), z: cl * Math.sin(lng) };
}

function rotateY(p, a) {
  const x =  p.x * Math.cos(a) + p.z * Math.sin(a);
  const z = -p.x * Math.sin(a) + p.z * Math.cos(a);
  return { x, y: p.y, z };
}

function rotateX(p, a) {
  const y = p.y * Math.cos(a) - p.z * Math.sin(a);
  const z = p.y * Math.sin(a) + p.z * Math.cos(a);
  return { x: p.x, y, z };
}

function project(p) {
  // camera looks along -z; near hemisphere = +z
  const f = DIST / (DIST - p.z);
  return {
    x: CENTER + p.x * S * f,
    y: CENTER - p.y * S * f,
    z: p.z,
    f,
  };
}

// 0 (far / back) .. 1 (near / front) smooth front factor.
function frontFactor(z) {
  // visible from ~z>0; fade across the limb.
  return Math.max(0, Math.min(1, (z + 0.1) / 0.45));
}

// Build continents as random-walk blobs on the sphere surface.
function buildContinents() {
  const rng = makeRng(20260616);
  const centers = [
    [ 0.55,  0.4 ],
    [ 0.15,  2.3 ],
    [-0.5,   3.5 ],
    [ 0.7,   4.7 ],
    [-0.25,  5.6 ],
    [ 0.35,  1.4 ],
  ];
  const continents = centers.map(([clat, clng]) => {
    const count = 8 + Math.floor(rng() * 6);
    let lat = clat;
    let lng = clng;
    const dots = [];
    for (let k = 0; k < count; k += 1) {
      dots.push({ lat, lng });
      lat += (rng() - 0.5) * 0.5;
      lng += (rng() - 0.5) * 0.65;
      lat = Math.max(-1.35, Math.min(1.35, lat));
    }
    return dots;
  });
  return continents;
}

function setupOne(svg, reduce) {
  const gGrid = svg.querySelector('.hero__globe-grid');
  const gLand = svg.querySelector('.hero__globe-land');
  const gDots = svg.querySelector('.hero__globe-dots');
  if (!gGrid || !gLand || !gDots) return;

  // ----- grid geometry (parallels + meridians) -----
  const gridLines = [];

  PARALLELS.forEach((lat) => {
    const pts = [];
    for (let i = 0; i <= SEG; i += 1) {
      pts.push(sph(lat, (i / SEG) * Math.PI * 2));
    }
    const pl = document.createElementNS(SVG_NS, 'polyline');
    gGrid.appendChild(pl);
    gridLines.push({ el: pl, pts, kind: 'parallel' });
  });

  for (let m = 0; m < MERIDIAN_COUNT; m += 1) {
    const lng = (m / MERIDIAN_COUNT) * Math.PI * 2;
    const pts = [];
    for (let i = 0; i <= SEG; i += 1) {
      const lat = -Math.PI / 2 + (i / SEG) * Math.PI;
      pts.push(sph(lat, lng));
    }
    const pl = document.createElementNS(SVG_NS, 'polyline');
    gGrid.appendChild(pl);
    gridLines.push({ el: pl, pts, kind: 'meridian' });
  }

  // ----- terrain geometry -----
  const continents = buildContinents();

  // flat list of dots, each with a reveal order (round-robin across
  // continents so they grow together) + the coastline segment to prev dot.
  const dots = [];
  const maxLen = Math.max(...continents.map((c) => c.length));
  let order = 0;
  for (let k = 0; k < maxLen; k += 1) {
    continents.forEach((c, ci) => {
      if (k < c.length) {
        const dotEl = document.createElementNS(SVG_NS, 'circle');
        dotEl.setAttribute('r', '1.7');
        gDots.appendChild(dotEl);

        let lineEl = null;
        if (k > 0) {
          lineEl = document.createElementNS(SVG_NS, 'line');
          gLand.appendChild(lineEl);
        }
        dots.push({
          ci,
          base: c[k],
          prev: k > 0 ? c[k - 1] : null,
          el: dotEl,
          lineEl,
          order: order++,
        });
      }
    });
  }

  const project3 = (base, spin) =>
    project(rotateX(rotateY(sph(base.lat, base.lng), spin), TILT));

  const renderGrid = (spin) => {
    gridLines.forEach((g) => {
      let pointsStr = '';
      let zSum = 0;
      for (let i = 0; i < g.pts.length; i += 1) {
        const pr = project(rotateX(rotateY(g.pts[i], spin), TILT));
        pointsStr += `${pr.x.toFixed(1)},${pr.y.toFixed(1)} `;
        zSum += pr.z;
      }
      g.el.setAttribute('points', pointsStr.trim());
      // meridians fade front/back as a whole; parallels stay even.
      const avg = zSum / g.pts.length;
      const op = g.kind === 'meridian'
        ? 0.1 + frontFactor(avg) * 0.32
        : 0.18;
      g.el.setAttribute('stroke-opacity', op.toFixed(3));
    });
  };

  const renderTerrain = (spin, revealF) => {
    dots.forEach((d) => {
      const born = Math.max(0, Math.min(1, (revealF - d.order) / REVEAL_EASE));
      if (born <= 0) {
        d.el.setAttribute('fill-opacity', '0');
        if (d.lineEl) d.lineEl.setAttribute('stroke-opacity', '0');
        return;
      }
      const pr = project3(d.base, spin);
      const ff = frontFactor(pr.z);
      d.el.setAttribute('cx', pr.x.toFixed(1));
      d.el.setAttribute('cy', pr.y.toFixed(1));
      d.el.setAttribute('r', (1.7 * pr.f).toFixed(2));
      d.el.setAttribute('fill-opacity', (born * ff * 0.9).toFixed(3));

      if (d.lineEl && d.prev) {
        const pp = project3(d.prev, spin);
        d.lineEl.setAttribute('x1', pp.x.toFixed(1));
        d.lineEl.setAttribute('y1', pp.y.toFixed(1));
        d.lineEl.setAttribute('x2', pr.x.toFixed(1));
        d.lineEl.setAttribute('y2', pr.y.toFixed(1));
        const lff = frontFactor((pr.z + pp.z) / 2);
        d.lineEl.setAttribute('stroke-opacity', (born * lff * 0.5).toFixed(3));
      }
    });
  };

  if (reduce) {
    const spin = 0.6;
    renderGrid(spin);
    renderTerrain(spin, 9999); // fully formed, static
    return;
  }

  const start = performance.now();
  let raf = 0;

  const frame = (now) => {
    const elapsed = now - start;
    const spin = elapsed * 0.00018;          // ~35s per rotation
    const revealF = elapsed / REVEAL_STEP_MS; // terrain growth clock
    renderGrid(spin);
    renderTerrain(spin, revealF);
    raf = requestAnimationFrame(frame);
  };
  raf = requestAnimationFrame(frame);

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      cancelAnimationFrame(raf);
    } else {
      raf = requestAnimationFrame(frame);
    }
  });
}

export function initGlobe() {
  const svgs = document.querySelectorAll('[data-hero-globe]');
  if (!svgs.length) return;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  svgs.forEach((svg) => setupOne(svg, reduce));
}
