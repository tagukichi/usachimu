// CHIMWORKS MOTION — Three.js パーティクルシーン。
// サイト全体の背面に固定した WebGL キャンバスで、約 2,600 個の
// パーティクルがスクロールに合わせて形態を変えながらページを貫く。
//
//   FV       : 球体（右寄り。テキストは左で干渉しない）
//   Concept  : 周縁に散らばる粒子野（中央は本文のため空けておく）
//   Service  : 画面を額縁のように囲む 2 本の軌道リング（中央は空）
//   About    : 左マージンのらせん（ポートレート側。本文は右）
//   Contact  : 中央下へ収束するリング
//
// どの形状も「本文が載る領域を避ける」を第一条件に設計している。
// 形態遷移は GSAP ScrollTrigger の scrub がグローバル進行度 p (0..4)
// を駆動し、フレームごとに隣接シェイプ間を CPU 補間する。
// TOP（FV があるページ）だけで動作し、ブログなど下層ページでは
// 起動しない。WebGL 不可なら false を返し SVG 球体が残る。

import * as THREE from './vendor/three.module.min.js';

// 白背景の上で本文を邪魔しない、淡いパステル基調のパレット。
// 濃色は使わず、締めのグリーンもごく少量にとどめる。
const PALETTE = [
  [0.62, 0.69, 0.80],  // light slate blue（主役 — 薄く上品に）
  [0.55, 0.85, 0.65],  // pale green
  [0.55, 0.80, 0.90],  // pale cyan
  [0.039, 0.894, 0.282], // neon green（少量のアクセント）
];

export function initThreeScene() {
  const gsap = window.gsap;
  const ScrollTrigger = window.ScrollTrigger;
  if (!gsap || !ScrollTrigger) return false;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return false;

  // TOP ページ（FV があるページ）専用。ブログなど下層ページでは
  // 記事の読みやすさを優先してパーティクルを出さない。
  if (!document.querySelector('[data-hero-scroll]')) return false;

  // WebGL 対応チェック（失敗したら SVG 球体のまま）
  let renderer;
  try {
    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' });
  } catch (e) {
    return false;
  }

  const isPc = window.innerWidth >= 1101;
  const COUNT = isPc ? 1800 : 900;

  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setClearColor(0x000000, 0); // 透過 — 背景色は CSS が持つ

  const canvas = renderer.domElement;
  canvas.className = 'webgl';
  canvas.setAttribute('aria-hidden', 'true');
  document.body.prepend(canvas);
  document.documentElement.classList.add('has-webgl');

  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 0.1, 100);
  camera.position.z = 10;

  /* ---- シェイプ定義（各 COUNT 点の座標配列） ------------------------- */
  const shapes = buildShapes(COUNT, isPc);

  /* ---- ジオメトリ ---------------------------------------------------- */
  const geometry = new THREE.BufferGeometry();
  const positions = new Float32Array(shapes[0]); // 初期 = 球体
  geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

  const colors = new Float32Array(COUNT * 3);
  const sizes  = new Float32Array(COUNT);
  const seeds  = new Float32Array(COUNT);
  for (let i = 0; i < COUNT; i++) {
    // 6 割は淡スレート、3.5 割をパステル、ネオングリーンは 5% だけ
    const rr = Math.random();
    const c = PALETTE[rr < 0.6 ? 0 : rr < 0.95 ? 1 + Math.floor(Math.random() * 2) : 3];
    colors[i * 3] = c[0];
    colors[i * 3 + 1] = c[1];
    colors[i * 3 + 2] = c[2];
    sizes[i] = 0.45 + Math.random() * 1.1;
    seeds[i] = Math.random() * Math.PI * 2;
  }
  geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
  geometry.setAttribute('aSize', new THREE.BufferAttribute(sizes, 1));

  /* ---- マテリアル（丸くソフトな点。gl_PointCoord で円形に切る） ------- */
  const material = new THREE.ShaderMaterial({
    transparent: true,
    depthWrite: false,
    uniforms: {
      uScale: { value: window.innerHeight * 0.5 },
      uAlpha: { value: 0 }, // ローダー後にフェードイン
      uBoost: { value: 1.6 }, // FV（球体）だけ少し濃く見せる
    },
    vertexShader: `
      attribute float aSize;
      varying vec3 vColor;
      uniform float uScale;
      void main() {
        vColor = color;
        vec4 mv = modelViewMatrix * vec4(position, 1.0);
        gl_PointSize = aSize * uScale * 0.085 / max(0.1, -mv.z);
        gl_Position = projectionMatrix * mv;
      }
    `,
    fragmentShader: `
      varying vec3 vColor;
      uniform float uAlpha;
      uniform float uBoost;
      void main() {
        float d = length(gl_PointCoord - 0.5);
        if (d > 0.5) discard;
        float soft = smoothstep(0.5, 0.18, d);
        gl_FragColor = vec4(vColor, min(1.0, soft * uAlpha * uBoost));
      }
    `,
    vertexColors: true,
  });

  const points = new THREE.Points(geometry, material);
  scene.add(points);

  /* ---- スクロール → 形態進行度 --------------------------------------- */
  const state = { p: 0 };
  const ranges = [
    ['[data-hero-scroll]', 0], // 0→1 : 球体 → 粒子野
    ['#services', 1],          // 1→2 : 粒子野 → 波
    ['#about', 2],             // 2→3 : 波 → らせん
    ['#contact', 3],           // 3→4 : らせん → 収束リング
  ];
  ranges.forEach(([sel, base]) => {
    const el = document.querySelector(sel);
    if (!el) return;
    ScrollTrigger.create({
      trigger: el,
      start: 'top bottom',
      end: 'bottom top',
      scrub: true,
      onUpdate: (self) => {
        state.p = base + self.progress;
      },
    });
  });

  /* ---- マウス視差（PC のみ） ----------------------------------------- */
  const mouse = { x: 0, y: 0 };
  if (isPc && window.matchMedia('(pointer: fine)').matches) {
    window.addEventListener('mousemove', (e) => {
      mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
      mouse.y = (e.clientY / window.innerHeight) * 2 - 1;
    }, { passive: true });
  }

  /* ---- ローダー後のフェードイン -------------------------------------- */
  // 読みやすさ最優先：全体の不透明度は低め（SP はさらに薄く）
  gsap.to(material.uniforms.uAlpha, { value: isPc ? 0.42 : 0.3, duration: 2.2, ease: 'power2.out', delay: 0.4 });

  /* ---- フレームループ（gsap.ticker に同期） --------------------------- */
  const pos = geometry.attributes.position.array;
  const smooth = (t) => t * t * (3 - 2 * t); // smoothstep

  let rotY = 0;
  const cam = { x: 0, y: 0 };

  const render = (time) => {
    if (document.hidden) return;

    const p = Math.min(state.p, shapes.length - 1.0001);
    const idx = Math.floor(p);
    const t = smooth(p - idx);
    const from = shapes[idx];
    const to = shapes[idx + 1];

    // 隣接シェイプの補間 + 各粒子の呼吸（seed ごとに位相をずらす）
    for (let i = 0; i < COUNT; i++) {
      const j = i * 3;
      const wob = Math.sin(time * 0.7 + seeds[i]) * 0.03;
      pos[j]     = from[j]     + (to[j]     - from[j])     * t + wob;
      pos[j + 1] = from[j + 1] + (to[j + 1] - from[j + 1]) * t + Math.cos(time * 0.6 + seeds[i]) * 0.03;
      pos[j + 2] = from[j + 2] + (to[j + 2] - from[j + 2]) * t;
    }
    geometry.attributes.position.needsUpdate = true;

    // FV（p=0 付近）だけ球体を少し濃く。Concept 以降は等倍へ
    material.uniforms.uBoost.value = 1 + 0.6 * Math.max(0, 1 - p * 1.6);

    // ゆっくり回転 + スクロール進行でわずかに加速
    rotY += 0.0012;
    points.rotation.y = rotY + p * 0.35;

    // カメラのマウス視差（慣性つき）
    cam.x += (mouse.x * 0.6 - cam.x) * 0.04;
    cam.y += (-mouse.y * 0.4 - cam.y) * 0.04;
    camera.position.x = cam.x;
    camera.position.y = cam.y;
    camera.lookAt(0, 0, 0);

    renderer.render(scene, camera);
  };
  gsap.ticker.add(render);

  /* ---- リサイズ ------------------------------------------------------- */
  window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
    material.uniforms.uScale.value = window.innerHeight * 0.5;
  }, { passive: true });

  return true;
}

/* ---- シェイプ生成 ------------------------------------------------------
   すべて COUNT * 3 の Float32Array。単位はワールド座標（カメラ z=10）。
   本文の載る領域（中央のコンテンツ幅）を避けることを最優先に設計。 */
function buildShapes(count, isPc) {
  const sphere = new Float32Array(count * 3);
  const field  = new Float32Array(count * 3);
  const orbit  = new Float32Array(count * 3);
  const helix  = new Float32Array(count * 3);
  const ring   = new Float32Array(count * 3);

  const offX = isPc ? 3.4 : 0; // FV では球体を右へ寄せる（テキストが左）

  for (let i = 0; i < count; i++) {
    const j = i * 3;

    // FV 球体：フィボナッチ格子で均等に
    const k = i + 0.5;
    const phi = Math.acos(1 - (2 * k) / count);
    const theta = Math.PI * (1 + Math.sqrt(5)) * k;
    const r = 2.6 + (Math.random() - 0.5) * 0.18;
    sphere[j]     = Math.cos(theta) * Math.sin(phi) * r + offX;
    sphere[j + 1] = Math.cos(phi) * r;
    sphere[j + 2] = Math.sin(theta) * Math.sin(phi) * r;

    // Concept 粒子野：楕円の環状に散らし、中央（本文）は空けておく
    {
      const a  = Math.random() * Math.PI * 2;
      const rx = 5.5 + Math.random() * 3.5;  // 横半径 5.5〜9
      const ry = 3.2 + Math.random() * 2.2;  // 縦半径 3.2〜5.4
      field[j]     = Math.cos(a) * rx;
      field[j + 1] = Math.sin(a) * ry;
      field[j + 2] = (Math.random() - 0.5) * 4 - 1;
    }

    // Service 軌道リング：画面を額縁のように囲む 2 本の大きな楕円軌道。
    // 中央は空になり、コンテンツがそのまま読める
    {
      const t = (i / count) * Math.PI * 4; // 2 周ぶん → 2 本に分配
      const second = i % 2 === 1;
      const spread = (Math.random() - 0.5) * 0.5; // 軌道の太さ
      const rx = 8.2 + spread;
      const ry = 4.6 + spread;
      const x = Math.cos(t) * rx;
      const y = Math.sin(t) * ry;
      if (second) {
        // 2 本目は逆傾き
        orbit[j]     = x;
        orbit[j + 1] = -y * 0.75 + 0.4;
        orbit[j + 2] = Math.sin(t) * 2.4;
      } else {
        orbit[j]     = x;
        orbit[j + 1] = y * 0.75 - 0.4;
        orbit[j + 2] = Math.cos(t) * 2.4;
      }
    }

    // About らせん：左マージン（ポートレート側）で細く立ち上る
    {
      const ht = (i / count) * Math.PI * 7;
      const arm = i % 2 ? 0 : Math.PI;
      const hx = isPc ? -5.6 : 0;
      helix[j]     = Math.cos(ht + arm) * 1.4 + hx;
      helix[j + 1] = (i / count - 0.5) * 9;
      helix[j + 2] = Math.sin(ht + arm) * 1.4;
    }

    // Contact 収束リング：中央やや下のトーラス（CTA の背面で細く光る）
    {
      const rt = (i / count) * Math.PI * 2;
      const tube = 0.3 + Math.random() * 0.2;
      const ta = Math.random() * Math.PI * 2;
      ring[j]     = (3.0 + tube * Math.cos(ta)) * Math.cos(rt);
      ring[j + 1] = (3.0 + tube * Math.cos(ta)) * Math.sin(rt) * 0.45 - 0.8;
      ring[j + 2] = tube * Math.sin(ta);
    }
  }

  return [sphere, field, orbit, helix, ring];
}
