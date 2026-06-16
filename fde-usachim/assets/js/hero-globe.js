// Hero globe — a two-layer translucent sphere (monochrome).
//   inner layer : faint wireframe (lines + vertex dots)
//   outer layer : irregular independent translucent triangle shards,
//                 each with a dot only at its three corners.
// Shards build in over time then persist; near hemisphere brighter,
// far side fades. Slow axial-tilted rotation. A few shards drift off.

const SVG_NS = 'http://www.w3.org/2000/svg';
const TAU = Math.PI * 2;
const GOLDEN = Math.PI * (3 - Math.sqrt(5));

const CENTER = 100;
const S = 60;          // projected sphere scale (leaves room for floaters)
const DIST = 2.7;      // perspective distance
const TILT = 0.41;     // axial tilt
const INNER_R = 0.82;  // inner wireframe radius

const SHARDS = 76;     // outer triangle count
const REVEAL_STEP_MS = 26;
const REVEAL_EASE = 34;
const FLOATERS = 18;

function makeRng(seed) {
  let s = (seed >>> 0) || 1;
  return () => {
    s = (Math.imul(s, 1664525) + 1013904223) >>> 0;
    return s / 4294967296;
  };
}

function norm(v) {
  const l = Math.hypot(v[0], v[1], v[2]) || 1;
  return [v[0] / l, v[1] / l, v[2] / l];
}

function rotY(v, a) {
  const x =  v[0] * Math.cos(a) + v[2] * Math.sin(a);
  const z = -v[0] * Math.sin(a) + v[2] * Math.cos(a);
  return [x, v[1], z];
}
function rotX(v, a) {
  const y = v[1] * Math.cos(a) - v[2] * Math.sin(a);
  const z = v[1] * Math.sin(a) + v[2] * Math.cos(a);
  return [v[0], y, z];
}
// rotate vector around an arbitrary unit axis (Rodrigues)
function rotAxis(v, ax, ang) {
  const c = Math.cos(ang);
  const s = Math.sin(ang);
  const d = ax[0] * v[0] + ax[1] * v[1] + ax[2] * v[2];
  return [
    v[0] * c + (ax[1] * v[2] - ax[2] * v[1]) * s + ax[0] * d * (1 - c),
    v[1] * c + (ax[2] * v[0] - ax[0] * v[2]) * s + ax[1] * d * (1 - c),
    v[2] * c + (ax[0] * v[1] - ax[1] * v[0]) * s + ax[2] * d * (1 - c),
  ];
}
function project(v) {
  const f = DIST / (DIST - v[2]);
  return { x: CENTER + v[0] * S * f, y: CENTER - v[1] * S * f, z: v[2], f };
}
function front(z) {
  return Math.max(0, Math.min(1, (z + 0.2) / 0.5));
}

function tangent(dir) {
  const ref = Math.abs(dir[1]) > 0.9 ? [1, 0, 0] : [0, 1, 0];
  const u = norm([
    dir[1] * ref[2] - dir[2] * ref[1],
    dir[2] * ref[0] - dir[0] * ref[2],
    dir[0] * ref[1] - dir[1] * ref[0],
  ]);
  const w = [
    dir[1] * u[2] - dir[2] * u[1],
    dir[2] * u[0] - dir[0] * u[2],
    dir[0] * u[1] - dir[1] * u[0],
  ];
  return [u, w];
}

// jittered icosphere(1) for the inner wireframe
function innerWire(rng) {
  const t = (1 + Math.sqrt(5)) / 2;
  let verts = [
    [-1, t, 0], [1, t, 0], [-1, -t, 0], [1, -t, 0],
    [0, -1, t], [0, 1, t], [0, -1, -t], [0, 1, -t],
    [t, 0, -1], [t, 0, 1], [-t, 0, -1], [-t, 0, 1],
  ].map(norm);
  let faces = [
    [0, 11, 5], [0, 5, 1], [0, 1, 7], [0, 7, 10], [0, 10, 11],
    [1, 5, 9], [5, 11, 4], [11, 10, 2], [10, 7, 6], [7, 1, 8],
    [3, 9, 4], [3, 4, 2], [3, 2, 6], [3, 6, 8], [3, 8, 9],
    [4, 9, 5], [2, 4, 11], [6, 2, 10], [8, 6, 7], [9, 8, 1],
  ];
  const cache = new Map();
  const mid = (a, b) => {
    const key = a < b ? `${a}_${b}` : `${b}_${a}`;
    if (cache.has(key)) return cache.get(key);
    const va = verts[a];
    const vb = verts[b];
    const vm = norm([va[0] + vb[0], va[1] + vb[1], va[2] + vb[2]]);
    const idx = verts.length;
    verts.push(vm);
    cache.set(key, idx);
    return idx;
  };
  const nf = [];
  faces.forEach(([a, b, c]) => {
    const ab = mid(a, b);
    const bc = mid(b, c);
    const ca = mid(c, a);
    nf.push([a, ab, ca], [b, bc, ab], [c, ca, bc], [ab, bc, ca]);
  });
  faces = nf;

  verts = verts.map((v) => {
    const j = norm([
      v[0] + (rng() - 0.5) * 0.12,
      v[1] + (rng() - 0.5) * 0.12,
      v[2] + (rng() - 0.5) * 0.12,
    ]);
    return [j[0] * INNER_R, j[1] * INNER_R, j[2] * INNER_R];
  });

  const seen = new Set();
  const edges = [];
  faces.forEach(([a, b, c]) => {
    [[a, b], [b, c], [c, a]].forEach(([x, y]) => {
      const key = x < y ? `${x}_${y}` : `${y}_${x}`;
      if (!seen.has(key)) { seen.add(key); edges.push([x, y]); }
    });
  });
  return { verts, edges };
}

// independent irregular shards on the unit sphere
function buildShards(rng) {
  const out = [];
  for (let i = 0; i < SHARDS; i += 1) {
    const y = 1 - (i / (SHARDS - 1)) * 2;
    const r = Math.sqrt(Math.max(0, 1 - y * y));
    const phi = i * GOLDEN;
    let dir = [Math.cos(phi) * r, y, Math.sin(phi) * r];
    dir = norm([
      dir[0] + (rng() - 0.5) * 0.16,
      dir[1] + (rng() - 0.5) * 0.16,
      dir[2] + (rng() - 0.5) * 0.16,
    ]);
    const [u, w] = tangent(dir);
    const size = 0.13 + rng() * 0.32;
    const a0 = rng() * TAU;
    const corners = [];
    for (let k = 0; k < 3; k += 1) {
      const ang = a0 + (k * TAU) / 3 + (rng() - 0.5) * 1.0;
      const rad = size * (0.5 + rng() * 1.0);
      corners.push(norm([
        dir[0] + (u[0] * Math.cos(ang) + w[0] * Math.sin(ang)) * rad,
        dir[1] + (u[1] * Math.cos(ang) + w[1] * Math.sin(ang)) * rad,
        dir[2] + (u[2] * Math.cos(ang) + w[2] * Math.sin(ang)) * rad,
      ]));
    }
    out.push({ corners, base: 0.05 + rng() * 0.12 });
  }
  return out;
}

function setupOne(svg, reduce) {
  const gGrid     = svg.querySelector('.hero__globe-grid');
  const gGridDots = svg.querySelector('.hero__globe-grid-dots');
  const gFaces    = svg.querySelector('.hero__globe-faces');
  const gFloaters = svg.querySelector('.hero__globe-floaters');
  const gDots     = svg.querySelector('.hero__globe-dots');
  if (!gFaces || !gDots) return;

  const rng = makeRng(20260616);

  // ---- inner wireframe ----
  const wire = innerWire(rng);
  const wireLineEls = wire.edges.map(() => {
    const el = document.createElementNS(SVG_NS, 'line');
    gGrid.appendChild(el);
    return el;
  });
  const wireDotEls = wire.verts.map(() => {
    const el = document.createElementNS(SVG_NS, 'circle');
    el.setAttribute('r', '0.6');
    gGridDots.appendChild(el);
    return el;
  });

  // ---- outer shards ----
  const shards = buildShards(rng);
  const order = shards.map((_, i) => i);
  for (let i = order.length - 1; i > 0; i -= 1) {
    const j = Math.floor(rng() * (i + 1));
    [order[i], order[j]] = [order[j], order[i]];
  }
  const shardData = shards.map((sh, i) => {
    const poly = document.createElementNS(SVG_NS, 'polygon');
    gFaces.appendChild(poly);
    const dotEls = [0, 1, 2].map(() => {
      const c = document.createElementNS(SVG_NS, 'circle');
      gDots.appendChild(c);
      return c;
    });
    return { ...sh, poly, dotEls, order: order[i] };
  });

  // ---- floaters : triangles that flutter & orbit around the sphere ----
  const floaters = [];
  if (gFloaters) {
    for (let i = 0; i < FLOATERS; i += 1) {
      const baseDir = norm([rng() - 0.5, rng() - 0.5, rng() - 0.5]);
      const axis = norm([rng() - 0.5, rng() - 0.5, rng() - 0.5]);
      const size = 0.05 + rng() * 0.085;
      const corners = [];
      let a = rng() * TAU;
      for (let k = 0; k < 3; k += 1) {
        a += ((0.7 + rng() * 0.8) * TAU) / 3;
        const rr = size * (0.7 + rng() * 0.6);
        corners.push([Math.cos(a) * rr, Math.sin(a) * rr]);
      }
      const el = document.createElementNS(SVG_NS, 'polygon');
      gFloaters.appendChild(el);
      floaters.push({
        el, baseDir, axis, corners,
        orbitSpeed: (rng() - 0.5) * 0.5,
        rBase: 1.14 + rng() * 0.14,
        rAmp: 0.05 + rng() * 0.08,
        rPhase: rng() * TAU,
        rSpeed: 0.4 + rng() * 0.5,
        flutPhase: rng() * TAU,
        flutSpeed: 1.1 + rng() * 1.7,   // leaf-flip speed
        spinPhase: rng() * TAU,
        spinSpeed: (rng() - 0.5) * 1.8, // in-plane tumble
        base: 0.06 + rng() * 0.11,
      });
    }
  }

  const project3 = (v, spin) => project(rotX(rotY(v, spin), TILT));

  const render = (spin, revealF, tSec) => {
    // inner wireframe
    const wp = wire.verts.map((v) => project3(v, spin));
    wire.edges.forEach(([a, b], i) => {
      const pa = wp[a];
      const pb = wp[b];
      const op = (0.05 + 0.12 * front((pa.z + pb.z) / 2));
      const el = wireLineEls[i];
      el.setAttribute('x1', pa.x.toFixed(1));
      el.setAttribute('y1', pa.y.toFixed(1));
      el.setAttribute('x2', pb.x.toFixed(1));
      el.setAttribute('y2', pb.y.toFixed(1));
      el.setAttribute('stroke-opacity', op.toFixed(3));
    });
    wp.forEach((p, i) => {
      const el = wireDotEls[i];
      el.setAttribute('cx', p.x.toFixed(1));
      el.setAttribute('cy', p.y.toFixed(1));
      el.setAttribute('r', (0.6 * p.f).toFixed(2));
      el.setAttribute('fill-opacity', (0.07 + 0.16 * front(p.z)).toFixed(3));
    });

    // outer shards + corner dots
    shardData.forEach((sd) => {
      const born = Math.max(0, Math.min(1, (revealF - sd.order) / REVEAL_EASE));
      const p0 = project3(sd.corners[0], spin);
      const p1 = project3(sd.corners[1], spin);
      const p2 = project3(sd.corners[2], spin);
      if (born <= 0) {
        sd.poly.setAttribute('fill-opacity', '0');
        sd.poly.setAttribute('stroke-opacity', '0');
        sd.dotEls.forEach((d) => d.setAttribute('fill-opacity', '0'));
        return;
      }
      const cz = (p0.z + p1.z + p2.z) / 3;
      const ff = front(cz);
      sd.poly.setAttribute('points',
        `${p0.x.toFixed(1)},${p0.y.toFixed(1)} ${p1.x.toFixed(1)},${p1.y.toFixed(1)} ${p2.x.toFixed(1)},${p2.y.toFixed(1)}`);
      sd.poly.setAttribute('fill-opacity', (born * sd.base * (0.18 + 0.82 * ff)).toFixed(3));
      sd.poly.setAttribute('stroke-opacity', (born * (0.05 + 0.18 * ff)).toFixed(3));

      [p0, p1, p2].forEach((p, k) => {
        const d = sd.dotEls[k];
        d.setAttribute('cx', p.x.toFixed(1));
        d.setAttribute('cy', p.y.toFixed(1));
        d.setAttribute('r', (0.55 * p.f).toFixed(2));
        d.setAttribute('fill-opacity', (born * (0.18 + 0.5 * front(p.z))).toFixed(3));
      });
    });

    // floaters — orbit around the sphere while fluttering (leaf-flip)
    floaters.forEach((fl) => {
      const dir = rotAxis(fl.baseDir, fl.axis, tSec * fl.orbitSpeed);
      const [u, w] = tangent(dir);
      const r = fl.rBase + fl.rAmp * Math.sin(fl.rPhase + tSec * fl.rSpeed);
      const flut = Math.cos(fl.flutPhase + tSec * fl.flutSpeed); // -1..1 squash
      const sp = fl.spinPhase + tSec * fl.spinSpeed;
      const cs = Math.cos(sp);
      const sn = Math.sin(sp);
      let pts = '';
      for (let k = 0; k < 3; k += 1) {
        const a = fl.corners[k][0] * flut; // squash along u → flip
        const b = fl.corners[k][1];
        const ar = a * cs - b * sn;
        const br = a * sn + b * cs;
        const p3 = [
          dir[0] * r + u[0] * ar + w[0] * br,
          dir[1] * r + u[1] * ar + w[1] * br,
          dir[2] * r + u[2] * ar + w[2] * br,
        ];
        const pr = project3(p3, spin);
        pts += `${pr.x.toFixed(1)},${pr.y.toFixed(1)} `;
      }
      fl.el.setAttribute('points', pts.trim());
      const vis = fl.base * (0.4 + 0.6 * Math.abs(flut)); // fainter edge-on
      fl.el.setAttribute('fill-opacity', (vis * 0.7).toFixed(3));
      fl.el.setAttribute('stroke-opacity', vis.toFixed(3));
    });
  };

  if (reduce) {
    render(0.6, 1e9, 0);
    return;
  }

  const start = performance.now();
  let raf = 0;
  const frame = (now) => {
    const elapsed = now - start;
    render(elapsed * 0.00016, elapsed / REVEAL_STEP_MS, elapsed / 1000);
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
