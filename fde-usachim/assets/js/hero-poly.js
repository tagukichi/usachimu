// Hero polyhedron — an irregular octahedron rendered as a rotating
// wireframe, with light pulses travelling vertex-to-vertex along edges.
// Pure JS 3D: rotate verts each frame, project to 2D, update SVG.

const SVG_NS = 'http://www.w3.org/2000/svg';

// Irregular octahedron: 6 vertices (2 axis + 4 around), perturbed off-axis.
const VERTS = [
  { x:  1.15, y:  0.05, z:  0.00 },
  { x: -1.00, y: -0.12, z:  0.18 },
  { x:  0.10, y:  1.22, z: -0.10 },
  { x: -0.06, y: -1.05, z:  0.12 },
  { x:  0.00, y:  0.18, z:  1.12 },
  { x:  0.12, y: -0.10, z: -1.28 },
];

// 12 edges (octahedron connectivity).
const EDGES = [
  [0, 2], [0, 3], [0, 4], [0, 5],
  [1, 2], [1, 3], [1, 4], [1, 5],
  [2, 4], [4, 3], [3, 5], [5, 2],
];

// Adjacency for pulse routing.
const ADJ = VERTS.map(() => []);
EDGES.forEach(([a, b], i) => {
  ADJ[a].push({ edge: i, to: b });
  ADJ[b].push({ edge: i, to: a });
});

const CENTER = 100;   // viewBox is 0..200
const SPREAD = 56;    // projection scale
const DIST = 3.1;     // camera distance

function rotate(v, ay, ax) {
  // around Y
  const x1 = v.x * Math.cos(ay) + v.z * Math.sin(ay);
  const z1 = -v.x * Math.sin(ay) + v.z * Math.cos(ay);
  // around X
  const y2 = v.y * Math.cos(ax) - z1 * Math.sin(ax);
  const z2 = v.y * Math.sin(ax) + z1 * Math.cos(ax);
  return { x: x1, y: y2, z: z2 };
}

function project(v) {
  const f = DIST / (v.z + DIST);
  return {
    x: CENTER + v.x * SPREAD * f,
    y: CENTER + v.y * SPREAD * f,
    depth: v.z, // -1.x (near) .. +1.x (far)
    f,
  };
}

function depthAlpha(depth) {
  // depth -1.3 (near) -> 1.0 ; +1.3 (far) -> 0.25
  const t = (depth + 1.3) / 2.6;
  return 1 - t * 0.75;
}

function setupOne(svg, reduce) {
  const gEdges  = svg.querySelector('.hero__poly-edges');
  const gVerts  = svg.querySelector('.hero__poly-verts');
  const gPulses = svg.querySelector('.hero__poly-pulses');
  if (!gEdges || !gVerts || !gPulses) return;

  // Build edge lines.
  const lines = EDGES.map(() => {
    const ln = document.createElementNS(SVG_NS, 'line');
    gEdges.appendChild(ln);
    return ln;
  });

  // Build vertex dots.
  const dots = VERTS.map((_, i) => {
    const c = document.createElementNS(SVG_NS, 'circle');
    c.setAttribute('r', i === 2 ? '3.2' : '2.4');
    gVerts.appendChild(c);
    return c;
  });

  // Pulses: dots that travel along edges, vertex to vertex.
  const PULSE_COUNT = 3;
  const pulses = [];
  for (let i = 0; i < PULSE_COUNT; i += 1) {
    const c = document.createElementNS(SVG_NS, 'circle');
    c.setAttribute('r', '2.6');
    c.setAttribute('class', 'hero__poly-pulse');
    gPulses.appendChild(c);
    const start = Math.floor(Math.random() * EDGES.length);
    pulses.push({
      el: c,
      from: EDGES[start][0],
      to: EDGES[start][1],
      t: Math.random(),
      speed: 0.006 + Math.random() * 0.006,
    });
  }

  const render = (ay, ax) => {
    const proj = VERTS.map((v) => project(rotate(v, ay, ax)));

    EDGES.forEach(([a, b], i) => {
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
      dots[i].setAttribute('r', ((i === 2 ? 3.2 : 2.4) * p.f).toFixed(2));
    });

    pulses.forEach((pl) => {
      const pa = proj[pl.from];
      const pb = proj[pl.to];
      const x = pa.x + (pb.x - pa.x) * pl.t;
      const y = pa.y + (pb.y - pa.y) * pl.t;
      const depth = pa.depth + (pb.depth - pa.depth) * pl.t;
      pl.el.setAttribute('cx', x.toFixed(2));
      pl.el.setAttribute('cy', y.toFixed(2));
      pl.el.setAttribute('fill-opacity', depthAlpha(depth).toFixed(3));
    });
  };

  if (reduce) {
    // Static, slightly tilted pose.
    render(-0.5, 0.4);
    return;
  }

  let t = 0;
  const step = () => {
    t += 0.0045;
    const ay = t;
    const ax = Math.sin(t * 0.42) * 0.5 + 0.25;

    // advance pulses vertex-to-vertex
    pulses.forEach((pl) => {
      pl.t += pl.speed;
      if (pl.t >= 1) {
        pl.t -= 1;
        const arrived = pl.to;
        const options = ADJ[arrived].filter((o) => o.to !== pl.from);
        const next = (options.length ? options : ADJ[arrived])[
          Math.floor(Math.random() * (options.length || ADJ[arrived].length))
        ];
        pl.from = arrived;
        pl.to = next.to;
      }
    });

    render(ay, ax);
    raf = requestAnimationFrame(step);
  };

  let raf = requestAnimationFrame(step);

  // Pause when tab hidden to save cycles.
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
      cancelAnimationFrame(raf);
    } else {
      raf = requestAnimationFrame(step);
    }
  });
}

export function initHeroPoly() {
  const svgs = document.querySelectorAll('[data-hero-poly]');
  if (!svgs.length) return;
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  svgs.forEach((svg) => setupOne(svg, reduce));
}
