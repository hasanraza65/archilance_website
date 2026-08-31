/* Admin panel behaviour: sidebar, counters, repeatables, media picker, sorting. */
(function () {
  'use strict';
  var $ = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  /* ---- mobile sidebar */
  var side = $('#side'), toggle = $('#sideToggle');
  if (toggle && side) {
    toggle.addEventListener('click', function () {
      var open = side.classList.toggle('is-open');
      if (open) {
        var scrim = document.createElement('div');
        scrim.className = 'scrim';
        scrim.addEventListener('click', function () { side.classList.remove('is-open'); scrim.remove(); });
        document.body.appendChild(scrim);
      } else {
        var s = $('.scrim'); if (s) s.remove();
      }
    });
  }

  /* ---- SEO character counters: colour the count as it approaches the limit */
  $$('[data-counter]').forEach(function (input) {
    var max = parseInt(input.dataset.counter, 10);
    var out = document.createElement('span');
    out.className = 'counter';
    var label = input.closest('.field').querySelector('label');
    if (label) label.appendChild(out);

    function paint() {
      var n = input.value.length;
      out.textContent = n + ' / ' + max;
      out.classList.toggle('is-warn', n > max * 0.9 && n <= max);
      out.classList.toggle('is-err', n > max);
    }
    input.addEventListener('input', paint);
    paint();
  });

  /* ---- slug auto-fill from the title, but never overwrite a manual slug */
  var src = $('[data-slug-source]'), slug = $('[data-slug-target]');
  if (src && slug && !slug.value) {
    src.addEventListener('input', function () {
      if (slug.dataset.touched) return;
      slug.value = src.value.toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
    });
    slug.addEventListener('input', function () { slug.dataset.touched = '1'; });
  }

  /* ---- repeatable JSON blocks (deliverables, stats, features…) */
  $$('[data-repeat]').forEach(function (host) {
    var name = host.dataset.repeat;
    var fields = JSON.parse(host.dataset.fields || '[]');
    // getElementById, not querySelector — page-editor ids come from field keys.
    var store = document.getElementById(name + '_json');
    if (!store) return;
    var rows = [];
    try { rows = JSON.parse(store.value || '[]') || []; } catch (e) { rows = []; }

    function sync() {
      store.value = JSON.stringify(rows);
    }

    function render() {
      host.innerHTML = '';
      rows.forEach(function (row, i) {
        var box = document.createElement('div');
        box.className = 'rep';
        var head = document.createElement('div');
        head.className = 'rep__head';
        head.innerHTML = '<b>#' + (i + 1) + '</b>';

        var up = document.createElement('button');
        up.type = 'button'; up.className = 'btn btn--sm'; up.textContent = '↑';
        up.addEventListener('click', function () {
          if (i === 0) return;
          rows.splice(i - 1, 0, rows.splice(i, 1)[0]); sync(); render();
        });
        var down = document.createElement('button');
        down.type = 'button'; down.className = 'btn btn--sm'; down.textContent = '↓';
        down.addEventListener('click', function () {
          if (i === rows.length - 1) return;
          rows.splice(i + 1, 0, rows.splice(i, 1)[0]); sync(); render();
        });
        var del = document.createElement('button');
        del.type = 'button'; del.className = 'btn btn--sm btn--danger'; del.textContent = 'Remove';
        del.addEventListener('click', function () { rows.splice(i, 1); sync(); render(); });

        head.appendChild(up); head.appendChild(down); head.appendChild(del);
        box.appendChild(head);

        fields.forEach(function (f) {
          var wrap = document.createElement('div');
          wrap.className = 'field';
          var lab = document.createElement('label');
          lab.textContent = f.label;
          var input = f.type === 'textarea' ? document.createElement('textarea') : document.createElement('input');
          input.className = 'ctrl';
          if (f.type !== 'textarea') input.type = 'text';
          input.value = (typeof row === 'string') ? row : (row[f.key] || '');
          input.addEventListener('input', function () {
            if (typeof rows[i] === 'string') rows[i] = input.value;
            else rows[i][f.key] = input.value;
            sync();
          });
          wrap.appendChild(lab); wrap.appendChild(input);
          box.appendChild(wrap);
        });

        host.appendChild(box);
      });
    }

    var add = document.querySelector('[data-repeat-add="' + name + '"]');
    if (add) {
      add.addEventListener('click', function () {
        if (fields.length === 1 && fields[0].key === '_') rows.push('');
        else {
          var blank = {};
          fields.forEach(function (f) { blank[f.key] = ''; });
          rows.push(blank);
        }
        sync(); render();
      });
    }

    render(); sync();
  });

  /* ---- media picker */
  $$('[data-picker]').forEach(function (box) {
    var target = $('#' + box.dataset.picker);
    var preview = $('#' + box.dataset.picker + '_preview');
    box.addEventListener('click', function (e) {
      var btn = e.target.closest('button[data-path]');
      if (!btn) return;
      e.preventDefault();
      target.value = btn.dataset.path;
      $$('button', box).forEach(function (b) { b.classList.toggle('is-on', b === btn); });
      if (preview) preview.src = btn.querySelector('img').src;
    });
  });

  /* ---- drag to reorder, persisted on drop */
  var sortBody = $('[data-sortable]');
  if (sortBody) {
    var dragging = null;
    $$('tr', sortBody).forEach(function (tr) {
      tr.draggable = true;
      tr.addEventListener('dragstart', function () { dragging = tr; tr.classList.add('is-dragging'); });
      tr.addEventListener('dragend', function () {
        tr.classList.remove('is-dragging');
        dragging = null;
        persist();
      });
      tr.addEventListener('dragover', function (e) {
        e.preventDefault();
        if (!dragging || dragging === tr) return;
        var r = tr.getBoundingClientRect();
        var after = (e.clientY - r.top) / r.height > 0.5;
        tr.parentNode.insertBefore(dragging, after ? tr.nextSibling : tr);
      });
    });

    function persist() {
      var order = $$('tr', sortBody).map(function (tr) { return tr.dataset.id; });
      fetch(sortBody.dataset.sortable, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ order: order }),
      }).then(function () {
        var note = $('#sortNote');
        if (note) { note.textContent = 'Order saved.'; setTimeout(function () { note.textContent = ''; }, 2000); }
      });
    }
  }

  /* ---- rich text fields on the page editor
     execCommand is deprecated but it is the only thing that gives a working
     selection-based toolbar without pulling in an editor library, and the
     output here is a sentence or two of inline markup. */
  $$('[data-rt]').forEach(function (rt) {
    var area = $('.rt__area', rt);
    var src = $('.rt__src', rt);
    var bar = $('.rt__bar', rt);
    if (!area || !src) return;

    function sync() { src.value = area.innerHTML.trim(); }

    area.addEventListener('input', sync);
    area.addEventListener('blur', sync);

    // Paste as plain text so Word and Google Docs cannot smuggle in styles.
    area.addEventListener('paste', function (e) {
      e.preventDefault();
      var text = (e.clipboardData || window.clipboardData).getData('text/plain');
      document.execCommand('insertText', false, text);
    });

    bar.addEventListener('click', function (e) {
      var btn = e.target.closest('button[data-cmd]');
      if (!btn) return;
      e.preventDefault();
      var cmd = btn.dataset.cmd;

      if (cmd === 'source') {
        var raw = src.hidden;
        src.hidden = !raw;
        area.hidden = raw;
        if (raw) { src.classList.add('rt__src--on'); src.focus(); }
        else { area.innerHTML = src.value; area.focus(); }
        btn.classList.toggle('is-on', raw);
        return;
      }

      area.focus();

      if (cmd === 'gold') {
        var sel = window.getSelection();
        if (!sel.rangeCount || sel.isCollapsed) return;
        var span = document.createElement('span');
        span.className = 'text-gold';
        try { sel.getRangeAt(0).surroundContents(span); } catch (err) { /* selection crossed elements */ }
      } else if (cmd === 'createLink') {
        var url = window.prompt('Link URL', 'https://');
        if (url) document.execCommand('createLink', false, url);
      } else {
        document.execCommand(cmd, false, null);
      }
      sync();
    });

    src.addEventListener('input', function () { area.innerHTML = src.value; });
    sync();
  });

  /* ---- savebar: highlight the section you are looking at */
  var tabs = $$('.savebar__tab');
  if (tabs.length) {
    var targets = tabs.map(function (t) { return document.getElementById(t.hash.slice(1)); });
    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var i = targets.indexOf(en.target);
        tabs.forEach(function (t, n) { t.classList.toggle('is-on', n === i); });
      });
    }, { rootMargin: '-35% 0px -60% 0px' });
    targets.forEach(function (el) { if (el) spy.observe(el); });
  }

  /* ---- confirm before destructive posts */
  document.addEventListener('submit', function (e) {
    var msg = e.target.dataset.confirm;
    if (msg && !window.confirm(msg)) e.preventDefault();
  });
})();
