// Cross-fade image slideshow. Any [data-img-fade] container with multiple
// .is-active/.svc-sub__wide-img (or generic <img>) children cycles them.

const INTERVAL = 4500;

export function initImgFade() {
  const groups = document.querySelectorAll('[data-img-fade]');
  groups.forEach((group) => {
    const imgs = Array.from(group.querySelectorAll('img'));
    if (imgs.length < 2) return;

    let idx = 0;
    imgs.forEach((im, i) => im.classList.toggle('is-active', i === 0));

    setInterval(() => {
      imgs[idx].classList.remove('is-active');
      idx = (idx + 1) % imgs.length;
      imgs[idx].classList.add('is-active');
    }, INTERVAL);
  });
}
