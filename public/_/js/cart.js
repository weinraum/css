// /public/js/cart.js
// Vanilla JS, keine jQuery-Abhängigkeit. Spricht dein Backend /ajax/modify_basket an.
// Erwartet ein verstecktes <input name="CSRFToken"> irgendwo im DOM (siehe core.js).

import Core, { ready, qs, postForm, debounce, flash } from './core.js';

'use strict';

// --- Fixe Defaults (bei Bedarf hier im Code ändern) ---
const MODIFY_URL      = '/ajax/modify_basket';
const BOBBLE_SELECTOR = 'div.top-desk div.office a.warenkorb span.bobble';
// 'async'  -> Kopf-Bobble live aktualisieren
// 'reload' -> nach erfolgreichem Add/Update Seite neu laden
const CART_MODE       = 'async';

// --- Helpers ---
const setBusy = (el, busy = true) => { if (!el) return; el.toggleAttribute('aria-busy', busy); el.disabled = !!busy; };

const readQtyNear = (origin) => {
  const wrap  = origin.closest('[data-product], [data-cart-row], form') || document;
  const input = wrap.querySelector('[data-cart-qty], input[name="qty"]');
  const attr  = origin.getAttribute('data-qty');
  const raw   = (input && input.value) || attr || '1';
  const n     = parseInt(raw, 10);
  return Number.isFinite(n) && n > 0 ? n : 1;
};

const currentBobble = () => (qs(BOBBLE_SELECTOR)?.textContent || '').trim();

const updateBobble = (count) => {
  const el = qs(BOBBLE_SELECTOR);
  if (!el) return;
  const c = parseInt(String(count || '0'), 10) || 0;
  if (c <= 0) {
    el.textContent = '';
    el.classList.remove('block');
    el.classList.add('none');
  } else {
    el.textContent = String(c);
    el.classList.remove('none');
    el.classList.add('block');
  }
};

// /ajax/modify_basket erwartet (aus deiner Altlogik):
// CSRFToken (kommt aus Core.postForm), weiID, num_wei, pakID, num_pak, oldNum
const modifyBasket = async ({ weiID, quantity, oldNum }) => {
  const payload = { weiID, num_wei: quantity, pakID: '', num_pak: '', oldNum };
  const data = await postForm(MODIFY_URL, payload);
  // Versuch, Kopfzähler aus JSON zu lesen (falls vorhanden):
  if (data && typeof data === 'object' && 'positions' in data) {
    updateBobble(data.positions);
  }
  return data;
};

// --- Actions ---
const onAddClick = async (btn) => {
  const weinId = btn.getAttribute('data-wei-id') || btn.getAttribute('data-product-id');
  if (!weinId) return;

  const qty    = readQtyNear(btn);
  const oldNum = currentBobble();

  setBusy(btn, true);
  try {
    await modifyBasket({ weiID: weinId, quantity: qty, oldNum });
    if (CART_MODE === 'reload') {
      location.reload();
      return;
    }
    flash('In den Warenkorb gelegt');
  } catch (e) {
    console.error(e);
    flash(e?.message || 'Fehler beim Hinzufügen');
  } finally {
    setBusy(btn, false);
  }
};

const onQtyIncDec = (btn, delta) => {
  const wrap  = btn.closest('[data-product], [data-cart-row], form') || document;
  const input = wrap.querySelector('[data-cart-qty], input[name="qty"]');
  if (!input) return;
  const cur  = parseInt(input.value || '1', 10) || 1;
  const next = Math.max(1, cur + delta);
  input.value = String(next);
  input.dispatchEvent(new Event('change', { bubbles: true }));
};

const onQtyChange = async (input) => {
  // Produkt-ID am Reihen-Container oder direkt am Input/Button
  const row    = input.closest('[data-cart-row], [data-product]');
  const weinId =
    (row && (row.getAttribute('data-wei-id') || row.getAttribute('data-product-id'))) ||
    input.getAttribute('data-wei-id') ||
    input.getAttribute('data-product-id');
  if (!weinId) return;

  const qty    = parseInt(input.value || '1', 10) || 1;
  const oldNum = currentBobble();

  input.setAttribute('aria-busy','true');
  try {
    await modifyBasket({ weiID: weinId, quantity: qty, oldNum });
    if (CART_MODE === 'reload') {
      location.reload();
      return;
    }
  } catch (e) {
    console.error(e);
    flash(e?.message || 'Fehler beim Aktualisieren');
  } finally {
    input.removeAttribute('aria-busy');
  }
};

// Optional: Schnellmengen-Chips (z. B. 1,3,6,12)
// <button data-qty-chip="6">6</button> in der Nähe eines [data-cart-qty]
const onQtyChip = (chip) => {
  const wrap  = chip.closest('[data-product], form') || document;
  const input = wrap.querySelector('[data-cart-qty], input[name="qty"]');
  if (!input) return;
  const n = parseInt(chip.getAttribute('data-qty-chip') || '1', 10) || 1;
  input.value = String(n);
  input.dispatchEvent(new Event('change', { bubbles: true }));
};

// --- Mount (Delegation, keine Inline-Events) ---
ready(() => {
  // Klick-Delegation
  document.addEventListener('click', (e) => {
    const addBtn = e.target.closest('[data-add-to-cart]');
    if (addBtn) { e.preventDefault(); onAddClick(addBtn); return; }

    const incBtn = e.target.closest('[data-cart-qty-inc]');
    if (incBtn) { e.preventDefault(); onQtyIncDec(incBtn, +1); return; }

    const decBtn = e.target.closest('[data-cart-qty-dec]');
    if (decBtn) { e.preventDefault(); onQtyIncDec(decBtn, -1); return; }

    const chip = e.target.closest('[data-qty-chip]');
    if (chip) { e.preventDefault(); onQtyChip(chip); return; }
  });

  // Mengenänderungen per Eingabe (debounced) oder Change
  const deb = debounce((el) => onQtyChange(el), 350);
  document.addEventListener('input', (e) => {
    const inp = e.target.closest('[data-cart-qty], input[name="qty"]');
    if (inp) deb(inp);
  });
  document.addEventListener('change', (e) => {
    const inp = e.target.closest('[data-cart-qty], input[name="qty"]');
    if (inp) onQtyChange(inp);
  });
});
