// Mobile navigation: toggle, focus trap, ESC to close, link close.

const FOCUSABLE = [
  'a[href]',
  'button:not([disabled])',
  'input:not([disabled])',
  'textarea:not([disabled])',
  'select:not([disabled])',
  '[tabindex]:not([tabindex="-1"])',
].join(',');

export function initNav() {
  const toggle = document.querySelector('[data-nav-toggle]');
  const panel  = document.querySelector('[data-nav-panel]');
  if (!toggle || !panel) return;

  let lastFocus = null;

  const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';

  const open = () => {
    lastFocus = document.activeElement;
    toggle.setAttribute('aria-expanded', 'true');
    toggle.setAttribute('aria-label', 'メニューを閉じる');
    panel.setAttribute('data-nav-open', 'true');
    document.body.classList.add('is-nav-open');

    const first = panel.querySelector(FOCUSABLE);
    if (first) first.focus();
  };

  const close = () => {
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'メニューを開く');
    panel.removeAttribute('data-nav-open');
    document.body.classList.remove('is-nav-open');
    if (lastFocus instanceof HTMLElement) lastFocus.focus();
  };

  toggle.addEventListener('click', () => {
    isOpen() ? close() : open();
  });

  // パネル内の閉じるボタン
  const closeBtn = panel.querySelector('[data-nav-close]');
  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      if (isOpen()) close();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (!isOpen()) return;
    if (e.key === 'Escape') {
      e.preventDefault();
      close();
      return;
    }
    if (e.key === 'Tab') {
      const focusables = Array.from(panel.querySelectorAll(FOCUSABLE)).filter(
        (el) => !el.hasAttribute('disabled') && el.offsetParent !== null
      );
      if (focusables.length === 0) return;
      const first = focusables[0];
      const last  = focusables[focusables.length - 1];
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }
  });

  panel.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    if (link && isOpen()) close();
  });

  // Close if viewport grows to desktop.
  const mql = window.matchMedia('(min-width: 768px)');
  const onChange = () => { if (mql.matches && isOpen()) close(); };
  mql.addEventListener?.('change', onChange);
}
