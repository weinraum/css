// Minimal Vanilla Date Picker (single date). No deps, CSP-safe.
// Use with <input type="text" data-date placeholder="YYYY-MM-DD">
(function(){
  const fmt = new Intl.DateTimeFormat('de-DE', { month: 'long', year: 'numeric' });
  const DOW = ['Mo','Di','Mi','Do','Fr','Sa','So']; // Week starts Monday
  const pad = n => String(n).padStart(2, '0');
  const iso = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
  const parse = s => {
    if (!s || typeof s !== 'string') return null;
    const m = s.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (!m) return null;
    const d = new Date(Date.UTC(+m[1], +m[2]-1, +m[3]));
    return isNaN(d) ? null : new Date(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate());
  };
  const clamp = (d, min, max) => {
    if (min && d < min) return min;
    if (max && d > max) return max;
    return d;
  };
  const inRange = (d, min, max) => (!min || d >= min) && (!max || d <= max);

  // Position helper
  function place(panel, input){
    const r = input.getBoundingClientRect();
    const top = r.bottom + window.scrollY + 6;
    const left = Math.min(window.scrollX + r.left, window.scrollX + Math.max(6, window.innerWidth - panel.offsetWidth - 6));
    panel.style.top = `${top}px`;
    panel.style.left = `${left}px`;
  }

  function build(input){
    let sel = parse(input.value) || null;
    const today = new Date(); today.setHours(0,0,0,0);
    let cursor = sel ? new Date(sel) : new Date(today);
    cursor.setDate(1);

    const min = parse(input.dataset.min);
    const max = parse(input.dataset.max);

    const panel = document.createElement('div');
    panel.className = 'date-mini';
    panel.setAttribute('role', 'dialog');
    panel.setAttribute('aria-label', 'Datum auswählen');

    const head = document.createElement('div');
    head.className = 'dm-head';

    const title = document.createElement('div');
    title.className = 'dm-title';

    const nav = document.createElement('div'); nav.className = 'dm-nav';
    const prev = document.createElement('button'); prev.type='button'; prev.className='dm-btn'; prev.textContent='‹';
    const next = document.createElement('button'); next.type='button'; next.className='dm-btn'; next.textContent='›';
    nav.appendChild(prev); nav.appendChild(next);

    head.appendChild(title); head.appendChild(nav);

    const grid = document.createElement('div'); grid.className = 'dm-grid';

    function render(){
      title.textContent = fmt.format(cursor);
      grid.innerHTML = '';

      // weekdays
      for (const d of DOW){
        const w = document.createElement('div'); w.className='dm-dow'; w.textContent=d; grid.appendChild(w);
      }

      // compute first weekday (Mon=1..Sun=7) for cursor month
      const first = new Date(cursor.getFullYear(), cursor.getMonth(), 1);
      let startIdx = (first.getDay() || 7) - 1; // 0..6 (Mon..Sun)
      const daysInMonth = new Date(cursor.getFullYear(), cursor.getMonth()+1, 0).getDate();

      // leading blanks
      for (let i=0;i<startIdx;i++){
        const b = document.createElement('div'); grid.appendChild(b);
      }

      for (let day=1; day<=daysInMonth; day++){
        const d = new Date(cursor.getFullYear(), cursor.getMonth(), day);
        const btn = document.createElement('button');
        btn.type='button'; btn.className='dm-day'; btn.textContent=String(day);
        btn.setAttribute('data-date', iso(d));
        if (sel && d.getTime() === sel.getTime()) btn.setAttribute('aria-selected','true');
        if (d.getTime() === today.getTime()) btn.setAttribute('aria-current','date');
        if (!inRange(d, min, max)) btn.disabled = true;
        grid.appendChild(btn);
      }
    }

    prev.addEventListener('click', () => {
      cursor = new Date(cursor.getFullYear(), cursor.getMonth()-1, 1);
      if (min && new Date(cursor.getFullYear(), cursor.getMonth()+1, 0) < min) {
        cursor = new Date(min.getFullYear(), min.getMonth(), 1);
      }
      render();
    });
    next.addEventListener('click', () => {
      cursor = new Date(cursor.getFullYear(), cursor.getMonth()+1, 1);
      if (max && new Date(cursor.getFullYear(), cursor.getMonth(), 1) > max) {
        cursor = new Date(max.getFullYear(), max.getMonth(), 1);
      }
      render();
    });

    grid.addEventListener('click', (e) => {
      const btn = e.target.closest('button.dm-day');
      if (!btn || btn.disabled) return;
      const d = parse(btn.getAttribute('data-date'));
      if (!d) return;
      sel = clamp(d, min, max);
      input.value = iso(sel);
      close();
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });

    panel.appendChild(head);
    panel.appendChild(grid);
    document.body.appendChild(panel);

    function close(){
      window.removeEventListener('scroll', onpos, true);
      window.removeEventListener('resize', onpos, true);
      document.removeEventListener('click', ondoc, true);
      document.removeEventListener('keydown', onkey, true);
      panel.remove();
      input.dataset.open = '';
    }
    function ondoc(ev){
      if (panel.contains(ev.target) || ev.target === input) return;
      close();
    }
    function onkey(ev){
      if (ev.key === 'Escape') close();
    }
    function onpos(){ place(panel, input); }
    render();
    place(panel, input);
    window.addEventListener('scroll', onpos, true);
    window.addEventListener('resize', onpos, true);
    document.addEventListener('click', ondoc, true);
    document.addEventListener('keydown', onkey, true);

    input.dataset.open = '1';
  }

  document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('focusin', (e) => {
      const inp = e.target.closest('input[data-date]');
      if (!inp) return;
      if (inp.dataset.open === '1') return;
      build(inp);
    });
    // Also allow click to open, if input already focused
    document.addEventListener('click', (e) => {
      const inp = e.target.closest('input[data-date]');
      if (!inp) return;
      if (inp.dataset.open === '1') return;
      build(inp);
    });
  });
})();
