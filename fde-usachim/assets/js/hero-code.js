// Hero editor — typing animation for code-like snippets.
// Vanilla JS, no deps. Respects prefers-reduced-motion.

const SNIPPETS = [
  {
    name: 'observe.py',
    lines: [
      { t: 'comment', s: '# observe — 現場のヒアリング' },
      { t: 'plain',   s: '' },
      { t: 'key',     s: 'def', after: ' hear(client):' },
      { t: 'plain',   s: '    docs = collect(client.team)' },
      { t: 'plain',   s: '    talk = listen(client.workflow)' },
      { t: 'plain',   s: '    return narrow(docs, talk)' },
      { t: 'plain',   s: '' },
      { t: 'ok',      s: '✓ 23 files indexed' },
      { t: 'ok',      s: '✓ 4 hearings complete' },
    ],
  },
  {
    name: 'build.py',
    lines: [
      { t: 'comment', s: '# build — 共に設計、自らの手で' },
      { t: 'plain',   s: '' },
      { t: 'plain',   s: 'spec  = co_design(goal, data)' },
      { t: 'plain',   s: 'proto = build(spec)' },
      { t: 'plain',   s: 'eval  = score(proto, kpi)' },
      { t: 'plain',   s: '' },
      { t: 'key',     s: 'if', after: ' eval.passes:' },
      { t: 'plain',   s: '    ship(proto)' },
      { t: 'key',     s: 'else', after: ':' },
      { t: 'plain',   s: '    iterate(proto, eval)' },
    ],
  },
  {
    name: 'ship.py',
    lines: [
      { t: 'comment', s: '# ship — 現場で使われる状態へ' },
      { t: 'plain',   s: '' },
      { t: 'plain',   s: 'deploy(env="prod")' },
      { t: 'plain',   s: 'monitor.watch(eval.live)' },
      { t: 'plain',   s: 'handoff(team, doc="runbook.md")' },
      { t: 'plain',   s: '' },
      { t: 'ok',      s: '✓ pipeline live' },
      { t: 'ok',      s: '✓ in-field adoption +37%' },
      { t: 'comment', s: '# 案件を次の3件へ' },
    ],
  },
];

const TYPE_DELAY = { min: 14, max: 38 };
const PAUSE_LINE_END   = 80;
const PAUSE_PARAGRAPH  = 280;
const PAUSE_FINISH     = 2400;
const PAUSE_BEFORE_NEXT = 480;

function rand(min, max) {
  return min + Math.random() * (max - min);
}

function sleep(ms) {
  return new Promise((res) => setTimeout(res, ms));
}

function escapeHtml(s) {
  return s.replace(/[&<>]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[c]));
}

function renderLineMarkup(line) {
  // Pre-render the entire line as HTML so syntax classes are present from the start of the line.
  const { t, s, after } = line;
  const safeS = escapeHtml(s);
  const safeAfter = after ? escapeHtml(after) : '';
  switch (t) {
    case 'comment': return `<span class="tk-comment">${safeS}</span>`;
    case 'key':     return `<span class="tk-key">${safeS}</span>${safeAfter}`;
    case 'ok':      return `<span class="tk-ok">${safeS}</span>`;
    default:        return safeS;
  }
}

function flattenForTyping(snippet) {
  // Build the full text we want to display, and a parallel HTML string to commit per char.
  const fullPlain = snippet.lines.map((l) => (l.s || '') + (l.after || '')).join('\n');
  const lineMarkups = snippet.lines.map(renderLineMarkup);
  return { fullPlain, lineMarkups };
}

async function typeSnippet(target, nameTarget, snippet, opts) {
  nameTarget.textContent = snippet.name;
  const { fullPlain, lineMarkups } = flattenForTyping(snippet);
  const lines = fullPlain.split('\n');

  // Buffer of already-finished lines (as HTML)
  let finishedHtml = '';
  for (let li = 0; li < lines.length; li += 1) {
    const lineText = lines[li];
    const markupHtml = lineMarkups[li];
    // Type character by character, but commit the markup HTML for finished portion.
    for (let i = 1; i <= lineText.length; i += 1) {
      // For typing effect, show finished lines + a slice of current line as plain text.
      const currentSlice = escapeHtml(lineText.slice(0, i));
      target.innerHTML = finishedHtml + currentSlice;
      // Auto-scroll if overflowing.
      target.parentElement.scrollTop = target.parentElement.scrollHeight;
      if (opts.aborted()) return;
      await sleep(rand(TYPE_DELAY.min, TYPE_DELAY.max));
    }
    // Commit the line with its proper markup.
    finishedHtml += markupHtml + (li < lines.length - 1 ? '\n' : '');
    target.innerHTML = finishedHtml;
    target.parentElement.scrollTop = target.parentElement.scrollHeight;
    const isEmpty = lineText.trim().length === 0;
    await sleep(isEmpty ? PAUSE_PARAGRAPH : PAUSE_LINE_END);
    if (opts.aborted()) return;
  }
}

export function initHeroCode() {
  const root   = document.querySelector('[data-hero-code]');
  if (!root) return;
  const target = root.querySelector('[data-hero-code-target]');
  const nameEl = root.querySelector('[data-hero-code-name]');
  const body   = root.querySelector('.hero__code-body');
  if (!target || !nameEl || !body) return;

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) {
    // 静止：1番目のスニペットを最終形で表示
    const { lineMarkups } = flattenForTyping(SNIPPETS[0]);
    target.innerHTML = lineMarkups.join('\n');
    nameEl.textContent = SNIPPETS[0].name;
    return;
  }

  let cancelled = false;
  const opts = { aborted: () => cancelled };

  (async () => {
    let idx = 0;
    while (!cancelled) {
      const snippet = SNIPPETS[idx % SNIPPETS.length];
      await typeSnippet(target, nameEl, snippet, opts);
      if (cancelled) return;
      await sleep(PAUSE_FINISH);
      // フェード切替
      body.dataset.fade = 'true';
      await sleep(320);
      target.innerHTML = '';
      body.scrollTop = 0;
      body.dataset.fade = 'false';
      await sleep(PAUSE_BEFORE_NEXT);
      idx += 1;
    }
  })();

  // Pause when the user leaves the tab to save cycles.
  document.addEventListener('visibilitychange', () => {
    // (We don't bother cancelling/restarting here; the animation is lightweight enough.)
  });

  // Cleanup hook if anyone reloads the module.
  return () => { cancelled = true; };
}
