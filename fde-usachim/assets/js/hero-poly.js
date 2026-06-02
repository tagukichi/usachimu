// Polyhedron visuals — irregular octahedrons rendered as wireframes
// with light pulses travelling vertex-to-vertex along edges.
// Pure JS 3D: rotate verts each frame, project to 2D, update SVG.
//
// Multiple shape configs are supported via the `data-poly` attribute.

const SVG_NS = 'http://www.w3.org/2000/svg';

const CONFIGS = {
  // Hero (large) — 6 vertices, lively rotation, 3 pulses
  hero: {
    verts: [
      { x:  1.15, y:  0.05, z:  0.00 },
      { x: -1.00, y: -0.12, z:  0.18 },
      { x:  0.10, y:  1.22, z: -0.10 },
      { x: -0.06, y: -1.05, z:  0.12 },
      { x:  0.00, y:  0.18, z:  1.12 },
      { x:  0.12, y: -0.10, z: -1.28 },
    ],
    edges: [
      [0, 2], [0, 3], [0, 4], [0, 5],
      [1, 2], [1, 3], [1, 4], [1, 5],
      [2, 4], [4, 3], [3, 5], [5, 2],
    ],
    pulseCount: 3,
    rotateSpeed: 0.0045,
    rotateOffset: 0,
    xAmp: 0.5,
    xPhase: 0.25,
    spread: 56,
    bigVertIndex: 2,
  },

  // News-A (top-left) — 7 vertices, slower, only 2 pulses
  'news-a': {
    verts: [
      { x:  0.95, y:  0.32, z: -0.20 },
      { x: -1.12, y:  0.18, z:  0.10 },
      { x:  0.22, y:  1.08, z:  0.30 },
      { x: -0.34, y: -0.88, z: -0.18 },
      { x:  0.18, y:  0.05, z:  1.18 },
      { x:  0.24, y: -0.30, z: -1.08 },
      { x: -0.58, y:  0.65, z:  0.58 },
    ],
    edges: [
      [0, 2], [0, 4], [0, 5], [0, 6],
      [1, 2], [1, 3], [1, 4], [1, 6],
      [2, 4], [4, 3], [3, 5], [5, 2],
      [6, 3], [6, 5],
    ],
    pulseCount: 2,
    rotateSpeed: 0.0022,
    rotateOffset: 1.4,
    xAmp: 0.35,
    xPhase: 0.18,
    spread: 54,
    bigVertIndex: 6,
  },

  // News-B (bottom-right) — 5 vertices, slower still, calmer motion
  'news-b': {
    verts: [
      { x:  1.05, y:  0.10, z:  0.05 },
      { x: -0.95, y:  0.25, z: -0.20 },
      { x:  0.10, y:  1.10, z:  0.25 },
      { x:  0.00, y: -0.95, z: -0.10 },
      { x: -0.15, y:  0.05, z:  1.05 },
    ],
    edges: [
      [0, 2], [0, 3], [0, 4],
      [1, 2], [1, 3], [1, 4],
      [2, 4], [3, 4], [2, 3],
    ],
    pulseCount: 2,
    rotateSpeed: 0.0018,
    rotateOffset: -0.8,
    xAmp: 0.28,
    xPhase: 0.14,
    spread: 58,
    bigVertIndex: 2,
  },
};

const CENTER = 100;
const DIST = 3.1;

function buildAdj(verts, edges) {
  const adj = verts.map(() => []);
  edges.forEach(([a, b], i) => {
    adj[a].push({ edge: i, to: b });
    adj[b].push({ edge: i, to: a });
  });
  return adj;
}

function rotate(v, ay, ax) {
  const x1 =  v.x * Math.cos(ay) + v.z * Math.sin(ay);
  const z1 = -v.x * Math.sin(ay) + v.z * Math.cos(ay);
  const y2 =  v.y * Math.cos(ax) - z1 * Math.sin(ax);
  const z2 =  v.y * Math.sin(ax) + z1 * Math.cos(ax);
  return { x: x1, y: y2, z: z2 };
}

function project(v, spread) {
  const f = DIST / (v.z + DIST);
  return {
    x: CENTER + v.x * spread * f,
    y: CENTER + v.y * spread * f,
    depth: v.z,
    f,
  };
}

function depthAlpha(depth) {
  const t = (depth + 1.3) / 2.6;
  return 1 - t * 0.75;
}

function setupOne(svg, cfg, reduce) {
  const gEdges  = svg.querySelector('.hero__poly-edges');
  const gVerts  = svg.querySelector('.hero__poly-verts');
  const gPulses = svg.querySelector('.hero__poly-pulses');
  if (!gEdges || !gVerts || !gPulses) return;

  const adj = buildAdj(cfg.verts, cfg.edges);

  const lines = cfg.edges.map(() => {
    const ln = document.createElementNS(SVG_NS, 'line');
    gEdges.appendChild(ln);
    return ln;
  });

  const dots = cfg.verts.map((_, i) => {
    const c = document.createElementNS(SVG_NS, 'circle');
    c.setAttribute('r', i === cfg.bigVertIndex ? '3.2' : '2.4');
    gVerts.appendChild(c);
    return c;
  });

  const pulses = [];
  for (let i = 0; i < cfg.pulseCount; i += 1) {
    const c = document.createElementNS(SVG_NS, 'circle');
    c.setAttribute('r', '2.6');
    c.setAttribute('class', 'hero__poly-pulse');
    gPulses.appendChild(c);
    const start = Math.floor(Math.random() * cfg.edges.length);
    pulses.push({
      el: c,
      from: cfg.edges[start][0],
      to: cfg.edges[start][1],
      t: Math.random(),
      speed: 0.006 + Math.random() * 0.006,
    });
  }

  const render = (ay, ax) => {
    const proj = cfg.verts.map((v) => project(rotate(v, ay, ax), cfg.spread));

    cfg.edges.forEach(([a, b], i) => {
      const pa = proj[a];
      const pb = proj[b];
      const ln = lines[i];
      ln.setAttribute('x1', pa.x.toFixed(2));
      ln.setAttribute('y1', pa.y.toFixed(2));
      ln.setAttribute('x2', pb.x.toFixed(2));
      ln.setAttribute('y2', pb.y.toFixed(2));
      ln.setAttribute('stroke-opacity', (depthAlpha((pa.depth + pb.depth) / 2) * 0.55).toFixed(3));
    });

    proj.forEach((p, i) => {
      dots[i].setAttribute('cx', p.x.toFixed(2));
      dots[i].setAttribute('cy', p.y.toFixed(2));
      dots[i].setAttribute('fill-opacity', depthAlpha(p.depth).toFixed(3));
      dots[i].setAttribute('r', ((i === cfg.bigVertIndex ? 3.2 : 2.4) * p.f).toFixed(2));
    });

    pulses.forEach((pl) => {
      const pa = proj[pl.from];
      const pb = proj[pl.to];
      pl.el.setAttribute('cx', (pa.x + (pb.x - pa.x) * pl.t).toFixed(2));
      pl.el.setAttribute('cy', (pa.y + (pb.y - pa.y) * pl.t).toFixed(2));
      pl.el.setAttribute('fill-opacity', depthAlpha(pa.depth + (pb.depth - pa.depth) * pl.t).toFixed(3));
    });
  };

  if (reduce) {
    render(cfg.rotateOffset - 0.5, 0.4);
    return;
  }

  let t = cfg.rotateOffset;
  const step = () => {
    t += cfg.rotateSpeed;
    const ay = t;
    const ax = Math.sin(t * cfg.xPhase) * cfg.xAmp + 0.25;

    pulses.forEach((pl) => {
      pl.t += pl.speed;
      if (pl.t >= 1) {
        pl.t -= 1;
        const arrived = pl.to;
        const options = adj[arrived].filter((o) => o.to !== pl.from);
        const next = (options.length ? options : adj[arrived])[
          Math.floor(Math.random() * (options.length || adj[arrived].length))
        ];
        pl.from = arrived;
        pl.to = next.to;
      }
    });

    render(ay, ax);
    raf = requestAnimationFrame(step);
  };

  let raf = requestAnimationFrame(step);

  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      cancelAnimationFrame(raf);
    } else {
      raf = requestAnimationFrame(step);
    }
  });
}

export function initPolys() {
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // 後方互換: 旧 data-hero-poly は hero 形状とみなす
  document.querySelectorAll('[data-hero-poly]').forEach((svg) => {
    setupOne(svg, CONFIGS.hero, reduce);
  });

  document.querySelectorAll('[data-poly]').forEach((svg) => {
    const name = svg.getAttribute('data-poly');
    const cfg = CONFIGS[name];
    if (cfg) setupOne(svg, cfg, reduce);
  });
}
