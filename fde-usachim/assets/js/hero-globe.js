// Hero globe — a triangulated translucent sphere (monochrome), inspired by
// low-poly "atoms" hero visuals. A jittered icosphere is rendered as
// overlapping translucent triangles; faces build in over time then persist.
// Near hemisphere is brighter, far side fades. A few triangles drift off
// the surface for flavour. Slow axial-tilted rotation.

const SVG_NS = 'http://www.w3.org/2000/svg';

const CENTER = 100;   // viewBox 0..200
const S = 64;         // projected sphere scale
const DIST = 2.7;     // perspective camera distance (in sphere radii)
const TILT = 0.41;    // ~23.5deg axial tilt
const SUBDIV = 2;     // icosphere subdivisions (2 -> 320 faces / 162 verts)
const JITTER = 0.085; // vertex irregularity

const REVEAL_STEP_MS = 18; // a face is born this often
const REVEAL_EASE = 36;    // a face eases in over this many steps
const FLOATERS = 6;

function makeRng(seed) {
  let s = seed >>> 0;
  return () => {
    s = (Math.imul(s, 1664525) + 1013904223) >>> 0;
    return s / 4294967296;
  };
}

function norm(v) {
  const l = Math.hypot(v[0], v[1], v[2]) || 1;
  return [v[0] / l, v[1] / l, v[2] / l];
}

function icosphere(level) {
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
  for (let l = 0; l < level; l += 1) {
    const nf = [];
    faces.forEach(([a, b, c]) => {
      const ab = mid(a, b);
      const bc = mid(b, c);
      const ca = mid(c, a);
      nf.push([a, ab, ca], [b, bc, ab], [c, ca, bc], [ab, bc, ca]);
    });
    faces = nf;
  }
  return { verts, faces };
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

function projectScreen(v) {
  const f = DIST / (DIST - v[2]);
  return { x: CENTER + v[0] * S * f, y: CENTER - v[1] * S * f, z: v[2], f };
}

// 0 (far) .. 1 (near) front factor.
function front(z) {
  return Math.max(0, Math.min(1, (z + 0.2) / 0.5));
}

function tangentBasis(dir) {
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

function setupOne(svg, reduce) {
  const gFaces    = svg.querySelector('.hero__globe-faces');
  const gFloaters = svg.querySelector('.hero__globe-floaters');
  const gDots     = svg.querySelector('.hero__globe-dots');
  if (!gFaces || !gDots) return;

  const rng = makeRng(20260616);
  const { verts, faces } = icosphere(SUBDIV);

  // jitter vertices for an irregular look (still on the sphere)
  const V = verts.map((v) => norm([
    v[0] + (rng() - 0.5) * JITTER,
    v[1] + (rng() - 0.5) * JITTER,
    v[2] + (rng() - 0.5) * JITTER,
  ]));

  // shuffled reveal order so faces pop in scattered
  const orderPool = faces.map((_, i) => i);
  for (let i = orderPool.length - 1; i > 0; i -= 1) {
    const j = Math.floor(rng() * (i + 1));
    [orderPool[i], orderPool[j]] = [orderPool[j], orderPool[i]];
  }

  const faceData = faces.map((f, i) => {
    const el = document.createElementNS(SVG_NS, 'polygon');
    gFaces.appendChild(el);
    return {
      el,
      idx: f,
      base: 0.05 + rng() * 0.11, // per-face translucency
      order: orderPool[i],
    };
  });

  const dotData = V.map(() => {
    const el = document.createElementNS(SVG_NS, 'circle');
    gDots.appendChild(el);
    return el;
  });

  // floaters: small triangles that drift radially out and back
  const floaters = [];
  if (gFloaters) {
    for (let i = 0; i < FLOATERS; i += 1) {
      const dir = norm([rng() - 0.5, rng() - 0.5, rng() - 0.5]);
      const [u, w] = tangentBasis(dir);
      const corners = [];
      for (let k = 0; k < 3; k += 1) {
        const a = (rng() - 0.5) * 0.22;
        const b = (rng() - 0.5) * 0.22;
        corners.push([a, b]);
      }
      const el = document.createElementNS(SVG_NS, 'polygon');
      gFloaters.appendChild(el);
      floaters.push({
        el, dir, u, w, corners,
        phase: rng(),
        speed: 0.04 + rng() * 0.05,
      });
    }
  }

  const screenVerts = new Array(V.length);

  const render = (spin, revealF, tSec) => {
    // rotate + project all vertices once
    for (let i = 0; i < V.length; i += 1) {
      screenVerts[i] = projectScreen(rotX(rotY(V[i], spin), TILT));
    }

    // faces
    faceData.forEach((fd) => {
      const [a, b, c] = fd.idx;
      const pa = screenVerts[a];
      const pb = screenVerts[b];
      const pc = screenVerts[c];
      const born = Math.max(0, Math.min(1, (revealF - fd.order) / REVEAL_EASE));
      if (born <= 0) {
        fd.el.setAttribute('fill-opacity', '0');
        fd.el.setAttribute('stroke-opacity', '0');
        return;
      }
      const cz = (pa.z + pb.z + pc.z) / 3;
      const ff = front(cz);
      fd.el.setAttribute('points',
        `${pa.x.toFixed(1)},${pa.y.toFixed(1)} ${pb.x.toFixed(1)},${pb.y.toFixed(1)} ${pc.x.toFixed(1)},${pc.y.toFixed(1)}`);
      fd.el.setAttribute('fill-opacity', (born * fd.base * (0.18 + 0.82 * ff)).toFixed(3));
      fd.el.setAttribute('stroke-opacity', (born * (0.05 + 0.16 * ff)).toFixed(3));
    });

    // vertex dots
    for (let i = 0; i < V.length; i += 1) {
      const p = screenVerts[i];
      const ff = front(p.z);
      const el = dotData[i];
      el.setAttribute('cx', p.x.toFixed(1));
      el.setAttribute('cy', p.y.toFixed(1));
      el.setAttribute('r', (1.0 * p.f).toFixed(2));
      el.setAttribute('fill-opacity', (0.12 + 0.55 * ff).toFixed(3));
    }

    // floaters
    floaters.forEach((fl) => {
      let tt = fl.phase + tSec * fl.speed;
      tt -= Math.floor(tt);             // 0..1 loop
      const r = 1.08 + tt * 0.7;        // drift outward
      const op = Math.sin(Math.PI * tt) * 0.12;
      let pts = '';
      for (let k = 0; k < 3; k += 1) {
        const [a, b] = fl.corners[k];
        const p3 = [
          fl.dir[0] * r + fl.u[0] * a + fl.w[0] * b,
          fl.dir[1] * r + fl.u[1] * a + fl.w[1] * b,
          fl.dir[2] * r + fl.u[2] * a + fl.w[2] * b,
        ];
        const pr = projectScreen(rotX(rotY(p3, spin), TILT));
        pts += `${pr.x.toFixed(1)},${pr.y.toFixed(1)} `;
      }
      fl.el.setAttribute('points', pts.trim());
      fl.el.setAttribute('fill-opacity', (op * 0.7).toFixed(3));
      fl.el.setAttribute('stroke-opacity', op.toFixed(3));
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
