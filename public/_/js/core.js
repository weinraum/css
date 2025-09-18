// /public/js/core.js
const Core = (() => {
  const qs  = (sel, ctx = document) => ctx.querySelector(sel);
  const qsa = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

  const ready = (fn) => {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn, { once: true });
    } else fn();
  };

  // ---- CSRF (hidden input based, like your current app)
  const getTokenInput = () => qs('input[name="CSRFToken"]');
  const getCsrf = () => getTokenInput()?.value || null;
  const setCsrf = (val) => { const inp = getTokenInput(); if (inp && val) inp.value = val; };

  const updateCsrfFromJson = (data) => {
    if (data && typeof data === 'object' && 'CSRFToken' in data) setCsrf(data.CSRFToken);
  };

  // ---- HTTP (FormData by default)
  const DEFAULT_TIMEOUT = 15000;

  const _fetch = async (url, opts = {}, timeout = DEFAULT_TIMEOUT) => {
    const ctrl = new AbortController();
    const id = setTimeout(() => ctrl.abort(), timeout);
    try { return await fetch(url, { credentials: 'same-origin', signal: ctrl.signal, ...opts }); }
    finally { clearTimeout(id); }
  };

  const postForm = async (url, obj = {}, { timeout } = {}) => {
    const fd = new FormData();
    // CSRF first
    const t = getCsrf();
    if (t) fd.append('CSRFToken', t);
    // payload
    for (const [k, v] of Object.entries(obj)) {
      if (v !== undefined && v !== null) fd.append(k, String(v));
    }
    const res = await _fetch(url, { method: 'POST', body: fd }, timeout);
    const ct = res.headers.get('content-type') || '';
    const txt = await res.text(); // parse safe
    let data = txt;
    if (ct.includes('application/json') || txt.trim().startsWith('{') || txt.trim().startsWith('[')) {
      try { data = JSON.parse(txt); } catch {}
    }
    // try update CSRF if present
    if (typeof data === 'object') updateCsrfFromJson(data);
    if (!res.ok) {
      const msg = (typeof data === 'object' && data.message) ? data.message : `HTTP ${res.status}`;
      const err = new Error(msg); err.status = res.status; err.payload = data; throw err;
    }
    return data;
  };

  // ---- small utils
  const debounce = (fn, wait = 200) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), wait); }; };

  // Tiny toast (non-blocking)
  const flash = (msg, { ms = 1800 } = {}) => {
    let region = qs('#js-live-region');
    if (!region) {
      region = document.createElement('div');
      region.id = 'js-live-region';
      region.setAttribute('role','status');
      region.setAttribute('aria-live','polite');
      region.style.position = 'fixed';
      region.style.top = '10px';
      region.style.left = '0'; region.style.right = '0';
      region.style.display = 'grid'; region.style.placeItems = 'center';
      region.style.zIndex = '2147483647';
      region.style.pointerEvents = 'none';
      document.body.appendChild(region);
    }
    const box = document.createElement('div');
    box.textContent = msg;
    box.style.padding = '10px 14px'; box.style.background = 'rgba(0,0,0,.82)';
    box.style.color = '#fff'; box.style.borderRadius = '10px'; box.style.fontSize = '14px';
    box.style.boxShadow = '0 6px 24px rgba(0,0,0,.3)';
    region.appendChild(box); setTimeout(() => box.remove(), ms);
  };

  return { qs, qsa, ready, postForm, debounce, flash, getCsrf, setCsrf };
})();

export default Core;
export const qs = Core.qs;
export const qsa = Core.qsa;
export const ready = Core.ready;
export const postForm = Core.postForm;
export const debounce = Core.debounce;
export const flash = Core.flash;
export const getCsrf = Core.getCsrf;
export const setCsrf = Core.setCsrf;
