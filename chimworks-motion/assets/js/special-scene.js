// 特設ページ（3D）— Three.js シーン。
//   web : 浮遊するワイヤーフレームの街。グリッドの地平に、画面の
//         ワイヤーフレームを思わせる半透明パネルと細い立方体が立ち上がり、
//         ゆっくり回る。マウス／ドラッグで視点が動く。
//   dx  : 脈打つネットワーク。ノードが浮かび、近いもの同士が線で結ばれ、
//         光のパルスが線を伝う。マウス／ドラッグで視点が動く。
// どちらも白背景・緑/シアンのブランド色で、本文は下にスクロールして読む。
// WebGL 不可・reduced-motion ではキャンバスを消して静かな見出しだけ残す。

import * as THREE from './vendor/three.module.min.js';

const GREEN = 0x0ae448;
const DEEP  = 0x0a9b4b;
const CYAN  = 0x00bae2;
const INK   = 0x16213a;

export function initSpecialScene() {
  const root   = document.querySelector('[data-sp-scene]');
  const hero   = document.querySelector('[data-sp-hero]');
  const canvas = document.querySelector('[data-sp-canvas]');
  if (!root || !hero || !canvas) return false;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    canvas.remove();
    return false;
  }

  let renderer;
  try {
    renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
  } catch (e) {
    canvas.remove();
    return false;
  }

  const kind = root.getAttribute('data-sp-scene') === 'dx' ? 'dx' : 'web';
  const size = () => ({ w: hero.clientWidth, h: hero.clientHeight });

  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(size().w, size().h, false);
  renderer.setClearColor(0x000000, 0);

  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(42, size().w / size().h, 0.1, 200);
  camera.position.set(0, 3.2, 16);

  const world = new THREE.Group();
  scene.add(world);

  const build = kind === 'dx' ? buildNetwork : buildCity;
  const update = build(world);

  /* ---- 視点操作：マウス移動で緩く、ドラッグでしっかり回す ------------- */
  const target = { x: 0, y: 0 };
  const cur    = { x: 0, y: 0 };
  let dragging = false;
  let last = { x: 0, y: 0 };

  const onMove = (x, y) => {
    if (dragging) {
      target.y += (x - last.x) * 0.006;
      target.x += (y - last.y) * 0.004;
      target.x = Math.max(-0.6, Math.min(0.6, target.x));
      last = { x, y };
      return;
    }
    const nx = (x / window.innerWidth) * 2 - 1;
    const ny = (y / window.innerHeight) * 2 - 1;
    target.y = nx * 0.35;
    target.x = ny * 0.18;
  };

  hero.addEventListener('pointerdown', (e) => { dragging = true; last = { x: e.clientX, y: e.clientY }; hero.classList.add('is-dragging'); });
  window.addEventListener('pointerup',   () => { dragging = false; hero.classList.remove('is-dragging'); });
  window.addEventListener('pointermove', (e) => onMove(e.clientX, e.clientY), { passive: true });

  /* ---- ループ ------------------------------------------------------- */
  const clock = new THREE.Clock();
  let raf = 0;
  const loop = () => {
    raf = requestAnimationFrame(loop);
    if (document.hidden) return;
    const t = clock.getElapsedTime();

    cur.x += (target.x - cur.x) * 0.05;
    cur.y += (target.y - cur.y) * 0.05;
    world.rotation.y = cur.y + t * 0.05;
    world.rotation.x = cur.x;

    update(t);
    renderer.render(scene, camera);
  };
  loop();

  /* ---- FV が画面外に出たら止める（スクロールして本文を読むとき） ------- */
  const io = new IntersectionObserver((entries) => {
    entries.forEach((en) => {
      if (en.isIntersecting) { if (!raf) loop(); }
      else { cancelAnimationFrame(raf); raf = 0; }
    });
  }, { threshold: 0.02 });
  io.observe(hero);

  window.addEventListener('resize', () => {
    const { w, h } = size();
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
    renderer.setSize(w, h, false);
  }, { passive: true });

  root.classList.add('has-3d');
  return true;
}

/* ====================================================================
   web : 浮遊するワイヤーフレームの街
   ==================================================================== */
function buildCity(world) {
  // 地平のグリッド
  const grid = new THREE.GridHelper(60, 60, DEEP, 0xd7ded9);
  grid.position.y = -2.2;
  grid.material.transparent = true;
  grid.material.opacity = 0.45;
  world.add(grid);

  // 細いワイヤーの塔（ビル群）
  const towers = new THREE.Group();
  const edgeMat = new THREE.LineBasicMaterial({ color: INK, transparent: true, opacity: 0.55 });
  const accentMat = new THREE.LineBasicMaterial({ color: DEEP, transparent: true, opacity: 0.9 });
  const rng = mulberry32(7);
  for (let i = 0; i < 46; i++) {
    const w = 0.5 + rng() * 1.1;
    const h = 1 + rng() * 6;
    const geo = new THREE.BoxGeometry(w, h, w);
    const edges = new THREE.EdgesGeometry(geo);
    const line = new THREE.LineSegments(edges, rng() < 0.18 ? accentMat : edgeMat);
    const a = rng() * Math.PI * 2;
    const r = 3 + rng() * 11;
    line.position.set(Math.cos(a) * r, -2.2 + h / 2, Math.sin(a) * r);
    line.userData.base = line.position.y;
    line.userData.phase = rng() * Math.PI * 2;
    towers.add(line);
  }
  world.add(towers);

  // 浮遊する「画面」パネル：半透明のガラス板 + 中に UI を思わせる横線
  const panels = new THREE.Group();
  const glass = new THREE.MeshBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.55, side: THREE.DoubleSide, depthWrite: false });
  const frame = new THREE.LineBasicMaterial({ color: INK, transparent: true, opacity: 0.7 });
  const uiMat = new THREE.LineBasicMaterial({ color: CYAN, transparent: true, opacity: 0.8 });
  for (let i = 0; i < 9; i++) {
    const g = new THREE.Group();
    const pw = 2.4 + rng() * 1.6;
    const ph = pw * (0.62 + rng() * 0.2);
    g.add(new THREE.Mesh(new THREE.PlaneGeometry(pw, ph), glass));
    g.add(new THREE.LineSegments(new THREE.EdgesGeometry(new THREE.PlaneGeometry(pw, ph)), frame));
    // 中の "UI" ライン
    const pts = [];
    const rows = 3 + Math.floor(rng() * 3);
    for (let r = 0; r < rows; r++) {
      const y = ph * 0.32 - r * (ph * 0.16);
      const len = pw * (0.3 + rng() * 0.5);
      pts.push(new THREE.Vector3(-pw * 0.4, y, 0.01), new THREE.Vector3(-pw * 0.4 + len, y, 0.01));
    }
    g.add(new THREE.LineSegments(new THREE.BufferGeometry().setFromPoints(pts), uiMat));
    const a = rng() * Math.PI * 2;
    const rad = 4 + rng() * 6;
    g.position.set(Math.cos(a) * rad, 0.5 + rng() * 4.5, Math.sin(a) * rad);
    g.rotation.y = -a + Math.PI / 2 + (rng() - 0.5) * 0.6;
    g.userData.base = g.position.y;
    g.userData.phase = rng() * Math.PI * 2;
    panels.add(g);
  }
  world.add(panels);

  // 緑の光点
  const dots = makeDots(160, 14, GREEN, 0.09, rng);
  world.add(dots);

  return (t) => {
    panels.children.forEach((g) => {
      g.position.y = g.userData.base + Math.sin(t * 0.6 + g.userData.phase) * 0.25;
      g.rotation.z = Math.sin(t * 0.4 + g.userData.phase) * 0.03;
    });
    towers.children.forEach((l, i) => {
      if (i % 5 === 0) l.position.y = l.userData.base + Math.sin(t * 0.8 + l.userData.phase) * 0.12;
    });
    dots.rotation.y = -t * 0.03;
  };
}

/* ====================================================================
   dx : 脈打つネットワーク
   ==================================================================== */
function buildNetwork(world) {
  const rng = mulberry32(11);
  const N = 70;
  const nodes = [];
  for (let i = 0; i < N; i++) {
    // ゆるい球殻状に散らす
    const u = rng() * 2 - 1;
    const th = rng() * Math.PI * 2;
    const r = 4.5 + rng() * 3.5;
    const s = Math.sqrt(1 - u * u);
    nodes.push(new THREE.Vector3(s * Math.cos(th) * r, u * r * 0.7, s * Math.sin(th) * r));
  }

  // ノード
  const nodeGeo = new THREE.SphereGeometry(0.09, 12, 12);
  const nodeMat = new THREE.MeshBasicMaterial({ color: INK });
  const hubMat  = new THREE.MeshBasicMaterial({ color: DEEP });
  const nodeGroup = new THREE.Group();
  nodes.forEach((p, i) => {
    const m = new THREE.Mesh(nodeGeo, i % 9 === 0 ? hubMat : nodeMat);
    m.position.copy(p);
    if (i % 9 === 0) m.scale.setScalar(1.9);
    m.userData.phase = rng() * Math.PI * 2;
    nodeGroup.add(m);
  });
  world.add(nodeGroup);

  // 近い同士を線で結ぶ
  const links = [];
  for (let i = 0; i < N; i++) {
    for (let j = i + 1; j < N; j++) {
      if (nodes[i].distanceTo(nodes[j]) < 3.4 && rng() < 0.55) links.push([i, j]);
    }
  }
  const linePts = [];
  links.forEach(([i, j]) => linePts.push(nodes[i], nodes[j]));
  const lines = new THREE.LineSegments(
    new THREE.BufferGeometry().setFromPoints(linePts),
    new THREE.LineBasicMaterial({ color: INK, transparent: true, opacity: 0.22 })
  );
  world.add(lines);

  // 線を伝うパルス（小さな緑の球が線分上を移動）
  const pulseGeo = new THREE.SphereGeometry(0.07, 8, 8);
  const pulseMat = new THREE.MeshBasicMaterial({ color: GREEN });
  const pulses = [];
  for (let k = 0; k < 28; k++) {
    const m = new THREE.Mesh(pulseGeo, pulseMat);
    m.userData = { link: Math.floor(rng() * links.length), t: rng(), speed: 0.25 + rng() * 0.35 };
    world.add(m);
    pulses.push(m);
  }

  // 背景の薄い光点
  const dots = makeDots(220, 13, CYAN, 0.06, rng);
  world.add(dots);

  return (t) => {
    nodeGroup.children.forEach((m, i) => {
      if (i % 9 === 0) {
        const s = 1.9 + Math.sin(t * 2 + m.userData.phase) * 0.25;
        m.scale.setScalar(s);
      }
    });
    pulses.forEach((p) => {
      const d = p.userData;
      d.t += d.speed * 0.016;
      if (d.t > 1) { d.t = 0; d.link = Math.floor(Math.random() * links.length); }
      const [i, j] = links[d.link];
      p.position.lerpVectors(nodes[i], nodes[j], d.t);
    });
    dots.rotation.y = t * 0.02;
  };
}

/* ---- 共通：光点 -------------------------------------------------------- */
function makeDots(count, radius, color, size, rng) {
  const pos = new Float32Array(count * 3);
  for (let i = 0; i < count; i++) {
    pos[i * 3]     = (rng() - 0.5) * radius * 2;
    pos[i * 3 + 1] = (rng() - 0.5) * radius;
    pos[i * 3 + 2] = (rng() - 0.5) * radius * 2;
  }
  const geo = new THREE.BufferGeometry();
  geo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
  return new THREE.Points(geo, new THREE.PointsMaterial({ color, size, transparent: true, opacity: 0.75, sizeAttenuation: true }));
}

/* ---- 決定的な乱数（リロードしても同じ街・同じ網になる） ---------------- */
function mulberry32(a) {
  return () => {
    a |= 0; a = (a + 0x6D2B79F5) | 0;
    let t = Math.imul(a ^ (a >>> 15), 1 | a);
    t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}
