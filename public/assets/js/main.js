/* ==========================================================================
   Archilance LLC — v2 interactions
   Preloader · smooth scroll · header · hero · reveals · counters
   shared lightbox · portfolio filter · projects archive (FLIP + load more)
   testimonials · FAQ · form
   ========================================================================== */
(function () {
  'use strict';

  var $  = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var isTouch = window.matchMedia('(hover: none)').matches;

  /* ---------------------------------------------------------------- Preloader */
  (function preloader() {
    var el = $('#preloader');
    var bar = $('#preloaderBar');
    if (!el) return;

    var pct = 0;
    var done = false;
    var tick = setInterval(function () {
      pct = Math.min(pct + Math.random() * 18 + 6, 92);
      if (bar) bar.style.width = pct + '%';
    }, 130);

    function finish() {
      if (done) return;
      done = true;
      clearInterval(tick);
      if (bar) bar.style.width = '100%';
      setTimeout(function () {
        el.classList.add('is-done');
        document.body.classList.remove('is-locked');
        startHeroIntro();
        setTimeout(function () { el.remove(); }, 700);
      }, 260);
    }

    document.body.classList.add('is-locked');
    if (document.readyState === 'complete') finish();
    else window.addEventListener('load', finish);
    // Never trap the visitor if an asset stalls
    setTimeout(finish, 4500);
  })();

  /* ------------------------------------------------------------ Smooth scroll */
  var lenis = null;
  if (!reduceMotion && window.Lenis) {
    lenis = new window.Lenis({ duration: 1.05, smoothWheel: true, wheelMultiplier: 0.95, touchMultiplier: 1.6 });
    var raf = function (t) { lenis.raf(t); requestAnimationFrame(raf); };
    requestAnimationFrame(raf);
  }

  function scrollToTarget(target) {
    if (!target) return;
    var header = $('#siteHeader');
    var offset = header ? header.offsetHeight - 1 : 0;
    if (lenis) lenis.scrollTo(target, { offset: -offset, duration: 1.1 });
    else {
      var y = target.getBoundingClientRect().top + window.pageYOffset - offset;
      window.scrollTo({ top: y, behavior: reduceMotion ? 'auto' : 'smooth' });
    }
  }

  document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href^="#"]');
    if (!a) return;
    var id = a.getAttribute('href');
    if (!id || id === '#' || id.length < 2) return;
    var target = document.getElementById(id.slice(1));
    if (!target) return;
    e.preventDefault();
    closeDrawer();
    scrollToTarget(target);
    if (history.replaceState) history.replaceState(null, '', id);
  });

  /* ----------------------------------------------------------------- Header */
  (function header() {
    var head = $('#siteHeader');
    var toTop = $('#toTop');
    if (!head) return;

    var ticking = false;

    /* The header used to hide on scroll-down and return on scroll-up. On a
       touch swipe that fires as a burst of downward deltas and then stops,
       so it vanished mid-swipe and snapped back the moment the finger left
       the screen. It now simply stays put and condenses once you leave the
       top of the page. */
    function update() {
      var y = window.pageYOffset;

      head.classList.toggle('is-stuck', y > 12);
      if (toTop) toTop.classList.toggle('is-on', y > 700);

      ticking = false;
    }

    window.addEventListener('scroll', function () {
      if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    update();

    if (toTop) {
      toTop.addEventListener('click', function () {
        if (lenis) lenis.scrollTo(0, { duration: 1.2 });
        else window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
      });
    }
  })();

  /* ----------------------------------------------------------- Mobile drawer */
  var drawer = $('#drawer');
  var scrim = $('#scrim');
  var burger = $('#burger');

  function openDrawer() {
    if (!drawer) return;
    drawer.classList.add('is-open');
    drawer.setAttribute('aria-hidden', 'false');
    if (scrim) { scrim.hidden = false; requestAnimationFrame(function () { scrim.classList.add('is-open'); }); }
    if (burger) { burger.setAttribute('aria-expanded', 'true'); burger.setAttribute('aria-label', 'Close menu'); }
    document.body.classList.add('is-locked');
    if (lenis) lenis.stop();
    var first = drawer.querySelector('a, button');
    if (first) first.focus();
  }

  function closeDrawer() {
    if (!drawer || !drawer.classList.contains('is-open')) return;
    drawer.classList.remove('is-open');
    drawer.setAttribute('aria-hidden', 'true');
    if (scrim) {
      scrim.classList.remove('is-open');
      setTimeout(function () { scrim.hidden = true; }, 450);
    }
    if (burger) { burger.setAttribute('aria-expanded', 'false'); burger.setAttribute('aria-label', 'Open menu'); }
    document.body.classList.remove('is-locked');
    if (lenis) lenis.start();
  }

  if (burger) burger.addEventListener('click', function () {
    drawer.classList.contains('is-open') ? closeDrawer() : openDrawer();
  });
  if (scrim) scrim.addEventListener('click', closeDrawer);
  var drawerClose = $('#drawerClose');
  if (drawerClose) drawerClose.addEventListener('click', function () { closeDrawer(); if (burger) burger.focus(); });

  // Accordion inside the drawer
  $$('.drawer__link[aria-controls]').forEach(function (btn) {
    var panel = document.getElementById(btn.getAttribute('aria-controls'));
    if (!panel) return;
    btn.addEventListener('click', function () {
      var open = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!open));
      panel.style.maxHeight = open ? '0px' : panel.scrollHeight + 'px';
    });
  });

  /* ------------------------------------------------------------- Hero slider */
  function startHeroIntro() {
    var title = $('#heroTitle');
    if (!title) return;
    var lines = $$('.mask-line', title);
    lines.forEach(function (l, i) {
      setTimeout(function () { l.classList.add('is-in'); }, reduceMotion ? 0 : 120 * i);
    });
    // Reveal the rest of the hero straight away rather than waiting for scroll
    $$('.hero [data-reveal], .hero [data-stagger]').forEach(function (el, i) {
      setTimeout(function () { el.classList.add('is-in'); runCounters(el); }, reduceMotion ? 0 : 420 + 110 * i);
    });
  }

  (function heroSlider() {
    var slides = $$('.hero__slide');
    var dots = $$('.hero__dot');
    if (slides.length < 2) return;

    var index = 0;
    var timer = null;
    var DELAY = 7000;

    function go(next) {
      index = (next + slides.length) % slides.length;
      slides.forEach(function (s, i) { s.classList.toggle('is-active', i === index); });
      // Flipping aria-selected off then on restarts the ::after fill animation
      dots.forEach(function (d) { d.setAttribute('aria-selected', 'false'); });
      if (dots[index]) {
        void dots[index].offsetWidth;
        dots[index].setAttribute('aria-selected', 'true');
      }
    }

    function play() { if (!reduceMotion) { stop(); timer = setInterval(function () { go(index + 1); }, DELAY); } }
    function stop() { if (timer) { clearInterval(timer); timer = null; } }

    dots.forEach(function (d, i) {
      d.addEventListener('click', function () { go(i); play(); });
    });

    document.addEventListener('visibilitychange', function () {
      document.hidden ? stop() : play();
    });

    play();
  })();

  /* ------------------------------------------------------------- Counters */
  function runCounters(scope) {
    $$('[data-count]', scope).forEach(function (el) {
      if (el.dataset.counted) return;
      el.dataset.counted = '1';
      var end = parseFloat(el.dataset.count);
      var decimals = parseInt(el.dataset.decimals || '0', 10);
      var suffix = el.dataset.suffix || '';
      if (reduceMotion) { el.textContent = end.toFixed(decimals) + suffix; return; }

      var start = performance.now();
      var dur = 1500;
      function frame(now) {
        var p = Math.min((now - start) / dur, 1);
        var eased = 1 - Math.pow(1 - p, 3);
        el.textContent = (end * eased).toFixed(decimals) + suffix;
        if (p < 1) requestAnimationFrame(frame);
      }
      requestAnimationFrame(frame);
    });
  }

  /* -------------------------------------------------------------- Reveals */
  (function reveals() {
    var items = $$('[data-reveal], [data-stagger]').filter(function (el) { return !el.closest('.hero'); });
    if (!items.length) return;

    if (!('IntersectionObserver' in window) || reduceMotion) {
      items.forEach(function (el) { el.classList.add('is-in'); runCounters(el); });
      return;
    }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        el.classList.add('is-in');

        if (el.hasAttribute('data-stagger')) {
          Array.prototype.forEach.call(el.children, function (child, i) {
            child.style.transitionDelay = (i * 90) + 'ms';
          });
        }
        runCounters(el);
        io.unobserve(el);
      });
    }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });

    items.forEach(function (el) { io.observe(el); });
  })();

  /* -------------------------------------------------------------- Parallax */
  (function parallax() {
    var items = $$('[data-parallax]');
    if (reduceMotion || !items.length) return;

    items.forEach(function (el) {
      // over-scale so the drift never exposes an edge
      var amount = parseFloat(el.dataset.parallax) || 8;
      el.style.transform = 'scale(' + (1 + amount / 100 * 2.2) + ')';
      el.dataset.pAmount = amount;
    });

    var running = false;
    function frame() {
      var vh = window.innerHeight;
      items.forEach(function (el) {
        var host = el.parentElement;
        var r = host.getBoundingClientRect();
        if (r.bottom < -200 || r.top > vh + 200) return;
        var amount = parseFloat(el.dataset.pAmount);
        // -1 (below the fold) → 1 (above it)
        var progress = 1 - (r.top + r.height / 2) / (vh / 2 + r.height / 2);
        var shift = Math.max(-1, Math.min(1, progress)) * amount;
        el.style.transform = 'translate3d(0,' + shift + '%,0) scale(' + (1 + amount / 100 * 2.2) + ')';
      });
      running = false;
    }
    function request() { if (!running) { running = true; requestAnimationFrame(frame); } }

    window.addEventListener('scroll', request, { passive: true });
    window.addEventListener('resize', request, { passive: true });
    request();
  })();

  /* ------------------------------------------- Deferred hero slide images */
  (function heroDefer() {
    function load() {
      $$('.hero__slide img[data-src]').forEach(function (img) {
        if (img.dataset.srcset) img.srcset = img.dataset.srcset;
        img.src = img.dataset.src;
        img.removeAttribute('data-src');
        img.removeAttribute('data-srcset');
      });
    }
    // wait for load first so these never compete with the LCP image
    function schedule() {
      if ('requestIdleCallback' in window) window.requestIdleCallback(load, { timeout: 3000 });
      else setTimeout(load, 1200);
    }
    if (document.readyState === 'complete') schedule();
    else window.addEventListener('load', schedule);
  })();

  /* --------------------------------------------------- Magnetic buttons + cursor */
  (function pointerFx() {
    if (reduceMotion || isTouch) return;

    var cursor = $('#cursor');
    var dot = $('#cursorDot');
    var cx = 0, cy = 0, tx = 0, ty = 0;

    if (cursor && dot) {
      window.addEventListener('mousemove', function (e) {
        tx = e.clientX; ty = e.clientY;
        dot.style.transform = 'translate3d(' + tx + 'px,' + ty + 'px,0)';
        if (!cursor.classList.contains('is-on')) { cursor.classList.add('is-on'); dot.classList.add('is-on'); }
      }, { passive: true });

      (function loop() {
        cx += (tx - cx) * 0.18;
        cy += (ty - cy) * 0.18;
        cursor.style.transform = 'translate3d(' + cx + 'px,' + cy + 'px,0)';
        requestAnimationFrame(loop);
      })();

      var hotSel = 'a, button, .work, input, textarea, select, [data-magnetic]';
      document.addEventListener('mouseover', function (e) {
        if (e.target.closest(hotSel)) cursor.classList.add('is-hot');
      });
      document.addEventListener('mouseout', function (e) {
        if (e.target.closest(hotSel)) cursor.classList.remove('is-hot');
      });
      document.addEventListener('mouseleave', function () {
        cursor.classList.remove('is-on'); dot.classList.remove('is-on');
      });
    }

    $$('[data-magnetic]').forEach(function (el) {
      el.addEventListener('mousemove', function (e) {
        var r = el.getBoundingClientRect();
        var mx = e.clientX - r.left - r.width / 2;
        var my = e.clientY - r.top - r.height / 2;
        el.style.transform = 'translate(' + mx * 0.18 + 'px,' + my * 0.28 + 'px)';
      });
      el.addEventListener('mouseleave', function () { el.style.transform = ''; });
    });
  })();

  /* ------------------------------------------------------------- Lightbox
     Shared by the homepage grid (#works) and the projects archive
     (#projectsGrid). `getItems` is a callback so the viewer always reflects
     the CURRENT filter without the caller having to re-register anything. */
  function setupLightbox(getItems) {
    var lb = $('#lightbox');
    if (!lb) return null;

    var lbImg = $('#lbImage');
    var lbTitle = $('#lbTitle');
    var lbDesc = $('#lbDesc');
    var lbCount = $('#lbCount');
    var items = [];
    var current = 0;
    var lastFocus = null;

    function show(i) {
      if (!items.length) return;
      current = (i + items.length) % items.length;
      var w = items[current];
      lbImg.classList.remove('is-ready');
      var next = new Image();
      next.onload = function () {
        lbImg.src = next.src;
        lbImg.alt = w.querySelector('img') ? w.querySelector('img').alt : '';
        lbImg.classList.add('is-ready');
      };
      next.src = w.dataset.full;
      lbTitle.textContent = w.dataset.title || '';
      lbDesc.textContent = w.dataset.desc || '';
      lbCount.textContent = (current + 1) + ' / ' + items.length;
    }

    function open(w) {
      items = getItems();
      var i = items.indexOf(w);
      if (i < 0) return;
      lastFocus = document.activeElement;
      lb.hidden = false;
      document.body.classList.add('is-locked');
      if (lenis) lenis.stop();
      show(i);
      // The class goes on in a frame of its own so the opacity fade actually
      // animates from its initial value. .is-open flips visibility with a 0s
      // transition (see style.css), so the dialog is focusable immediately —
      // the reflow below just forces that style to be live before we focus.
      // Without this the dialog can open with focus stranded on the page
      // behind it and nothing trapping the Tab key.
      requestAnimationFrame(function () {
        lb.classList.add('is-open');
        void lb.offsetHeight;
        var closeBtn = lb.querySelector('[data-lb="close"]');
        if (closeBtn) closeBtn.focus();
      });
    }

    function close() {
      lb.classList.remove('is-open');
      document.body.classList.remove('is-locked');
      if (lenis) lenis.start();
      // removeAttribute, not src='' — an empty src resolves to the document URL
      // and makes the browser re-request the whole page
      setTimeout(function () { lb.hidden = true; lbImg.removeAttribute('src'); }, 400);
      if (lastFocus) lastFocus.focus();
    }

    lb.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-lb]');
      if (btn) {
        var act = btn.dataset.lb;
        if (act === 'close') close();
        if (act === 'prev') show(current - 1);
        if (act === 'next') show(current + 1);
        return;
      }
      if (e.target === lb || e.target.classList.contains('lightbox__stage')) close();
    });

    document.addEventListener('keydown', function (e) {
      if (lb.hidden) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') show(current - 1);
      if (e.key === 'ArrowRight') show(current + 1);
      if (e.key === 'Tab') {
        var focusables = $$('button', lb).filter(function (b) { return b.offsetParent !== null; });
        if (!focusables.length) return;
        var first = focusables[0], last = focusables[focusables.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });

    var sx = 0;
    lb.addEventListener('touchstart', function (e) { sx = e.changedTouches[0].clientX; }, { passive: true });
    lb.addEventListener('touchend', function (e) {
      var dx = e.changedTouches[0].clientX - sx;
      if (Math.abs(dx) > 55) show(current + (dx < 0 ? 1 : -1));
    }, { passive: true });

    return { open: open, close: close };
  }

  /* --------------------------------- Lightbox on a project detail page
     The archive binds its viewer to .work tiles. A project page has its own
     hero and gallery shots instead, so they get an instance of their own. */
  (function projectLightbox() {
    var shots = $$('[data-lightbox-open]');
    if (!shots.length) return;

    var box = setupLightbox(function () { return shots; });
    if (!box) return;

    shots.forEach(function (el) {
      el.addEventListener('click', function () { box.open(el); });
    });
  })();

  /* --------------------------------------------- Homepage portfolio filter */
  (function portfolio() {
    var grid = $('#works');
    if (!grid) return;

    var works = $$('.work', grid);
    var filters = $$('.filter');
    var empty = $('#worksEmpty');
    var visible = works.slice();

    var box = setupLightbox(function () { return visible; });

    filters.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var key = btn.dataset.filter;
        filters.forEach(function (f) { f.setAttribute('aria-pressed', String(f === btn)); });

        visible = [];
        works.forEach(function (w) {
          var match = key === 'all' || (w.dataset.cat || '').split(' ').indexOf(key) > -1;
          w.classList.toggle('is-hidden', !match);
          if (match) visible.push(w);
        });

        if (empty) empty.hidden = visible.length > 0;

        // brief re-entry animation for the new set
        if (!reduceMotion) {
          visible.forEach(function (w, i) {
            w.style.animation = 'none';
            void w.offsetWidth;
            w.style.animation = 'workIn .55s cubic-bezier(.22,1,.36,1) ' + (i * 35) + 'ms both';
          });
        }
      });
    });

    if (box) works.forEach(function (w) { w.addEventListener('click', function () { box.open(w); }); });
  })();

  /* ------------------------------------------------------- Projects archive
     Filter + progressive "load more" for /projects/. Filtering uses a FLIP
     pass so surviving cards glide to their new grid slots instead of jumping:
     outgoing cards fade first, then we measure → mutate → invert → play. */
  (function archive() {
    var grid = $('#projectsGrid');
    if (!grid) return;

    var works = $$('.work', grid);
    var filters = $$('.filter', $('#projectFilters'));
    var moreBtn = $('#projectsMore');
    var moreLabel = $('#projectsMoreLabel');
    var empty = $('#projectsEmpty');
    var status = $('#projectsStatus');
    var STEP = parseInt(grid.dataset.step, 10) || 9;

    var key = 'all';
    var limit = STEP;
    var matching = works.slice();

    var box = setupLightbox(function () { return matching; });
    if (box) works.forEach(function (w) { w.addEventListener('click', function () { box.open(w); }); });

    function matches(w, k) {
      return k === 'all' || (w.dataset.cat || '').split(' ').indexOf(k) > -1;
    }

    // Stamp real counts into the pills so the numbers can never drift from the
    // markup. Deliberately NOT [data-count] — that attribute drives the animated
    // stat counters and would try to tween these badges.
    filters.forEach(function (f) {
      var slot = f.querySelector('[data-tally]');
      if (!slot) return;
      slot.textContent = works.filter(function (w) { return matches(w, f.dataset.filter); }).length;
    });

    function flip(w, prev, i) {
      if (prev) {
        var last = w.getBoundingClientRect();
        var dx = prev.left - last.left;
        var dy = prev.top - last.top;
        if (Math.abs(dx) < 1 && Math.abs(dy) < 1) return;
        w.style.animation = 'none';
        w.style.transition = 'none';
        w.style.transform = 'translate3d(' + dx + 'px,' + dy + 'px,0)';
        requestAnimationFrame(function () {
          w.style.transition = 'transform .6s cubic-bezier(.22,1,.36,1)';
          w.style.transform = 'translate3d(0,0,0)';
        });
        setTimeout(function () { w.style.transition = ''; w.style.transform = ''; }, 680);
      } else {
        w.style.transition = '';
        w.style.transform = '';
        w.style.animation = 'none';
        void w.offsetWidth;
        w.style.animation = 'workIn .6s cubic-bezier(.22,1,.36,1) ' + Math.min(i * 45, 380) + 'ms both';
      }
    }

    function paint(animate) {
      matching = works.filter(function (w) { return matches(w, key); });
      var shown = matching.slice(0, limit);
      var leaving = works.filter(function (w) {
        return !w.classList.contains('is-hidden') && shown.indexOf(w) < 0;
      });

      function commit() {
        var first = null;
        if (animate && !reduceMotion) {
          first = new Map();
          works.forEach(function (w) {
            if (!w.classList.contains('is-hidden') && shown.indexOf(w) > -1) {
              first.set(w, w.getBoundingClientRect());
            }
          });
        }

        works.forEach(function (w) {
          w.classList.remove('is-leaving');
          w.classList.toggle('is-hidden', shown.indexOf(w) < 0);
        });

        if (empty) empty.hidden = matching.length > 0;
        if (moreBtn) {
          var left = matching.length - shown.length;
          moreBtn.hidden = left <= 0;
          if (moreLabel) moreLabel.textContent = 'Load ' + Math.min(STEP, left) + ' more';
        }
        if (status) {
          status.textContent = 'Showing ' + shown.length + ' of ' + matching.length +
            (matching.length === 1 ? ' project' : ' projects');
        }

        if (first) shown.forEach(function (w, i) { flip(w, first.get(w), i); });
      }

      if (animate && !reduceMotion && leaving.length) {
        leaving.forEach(function (w) { w.classList.add('is-leaving'); });
        setTimeout(commit, 200);
      } else {
        commit();
      }
    }

    // Narrowing the filter shortens the document, and the browser clamps the
    // scroll offset to the new bottom — which can dump a reader who was deep in
    // the grid onto the footer. If the filter bar has been pushed off screen,
    // bring it back.
    function keepGridInView() {
      var bar = $('.filter-bar');
      if (!bar) return;
      var top = bar.getBoundingClientRect().top;
      if (top >= 0 && top <= window.innerHeight * 0.5) return;
      // Measure from the grid, not the bar: the bar is sticky, so its rect
      // reports where it is *pinned*, not where it sits in the document, and
      // scrolling to that lands past the first row.
      var stickyTop = parseFloat(getComputedStyle(bar).top) || 0;
      var target = grid.getBoundingClientRect().top + window.pageYOffset -
                   stickyTop - bar.offsetHeight - 12;
      if (lenis) lenis.scrollTo(target, { duration: reduceMotion ? 0 : .8 });
      else window.scrollTo({ top: target, behavior: reduceMotion ? 'auto' : 'smooth' });
    }

    function setFilter(k, animate, push) {
      key = k;
      limit = STEP;
      filters.forEach(function (f) { f.setAttribute('aria-pressed', String(f.dataset.filter === k)); });
      paint(animate);
      if (animate) setTimeout(keepGridInView, 260);
      if (push && window.history && history.replaceState) {
        var url = location.pathname + (k === 'all' ? '' : '?c=' + encodeURIComponent(k));
        history.replaceState(null, '', url);
      }
    }

    filters.forEach(function (f) {
      f.addEventListener('click', function () { setFilter(f.dataset.filter, true, true); });
    });

    if (moreBtn) {
      moreBtn.addEventListener('click', function () {
        limit += STEP;
        paint(true);
        // keep focus sensible once the button removes itself
        if (moreBtn.hidden) {
          var last = matching[matching.length - 1];
          if (last) last.focus();
        }
      });
    }

    // deep link: /projects/?c=interior
    var wanted = (location.search.match(/[?&]c=([^&]+)/) || [])[1];
    wanted = wanted ? decodeURIComponent(wanted) : 'all';
    var known = filters.some(function (f) { return f.dataset.filter === wanted; });
    setFilter(known ? wanted : 'all', false, false);
  })();

  /* ------------------------------------------------- Organisation chart */
  (function orgChart() {
    var org = $('#org');
    if (!org) return;

    /* ---- Pan + zoom over the whole chart. The tree is plain nested markup,
       so it stays crawlable and readable with JS off; this only adds the
       viewport behaviour on top. */
    var vp = $('#omapViewport');
    var canvas = $('#omapCanvas');
    var map = $('#omap');
    var nodes = $$('.onode', org);

    if (vp && canvas) {
      var zoom = 1, panX = 0, panY = 0, fitZoom = 1;
      // The floor has to be low enough that a ~2760px chart still fits a 320px
      // phone, otherwise "Fit" cannot actually show the whole thing.
      var MIN = 0.1, MAX = 2;
      var pct = $('#omapPct');

      function apply() {
        canvas.style.transform = 'translate(' + panX + 'px,' + panY + 'px) scale(' + zoom + ')';
        if (pct) pct.textContent = Math.round(zoom * 100) + '%';
        // counter-scale the hover pop so an enlarged card is legible at any zoom
        org.style.setProperty('--pop', String(Math.max(1.06, 1 / zoom)));
      }

      function bounds() {
        return { w: canvas.scrollWidth, h: canvas.scrollHeight,
                 vw: vp.clientWidth, vh: vp.clientHeight };
      }

      function clamp() {
        var b = bounds();
        var cw = b.w * zoom, ch = b.h * zoom;
        // keep the canvas overlapping the viewport; centre it when it is smaller
        panX = cw <= b.vw ? (b.vw - cw) / 2 : Math.min(0, Math.max(b.vw - cw, panX));
        panY = ch <= b.vh ? (b.vh - ch) / 2 : Math.min(0, Math.max(b.vh - ch, panY));
      }

      function fit(animate) {
        var b = bounds();
        if (!b.w || !b.h) return;
        fitZoom = Math.min(b.vw / b.w, b.vh / b.h);
        fitZoom = Math.max(MIN, Math.min(1, fitZoom));
        zoom = fitZoom;
        canvas.style.transition = animate ? 'transform .5s cubic-bezier(.22,1,.36,1)' : 'none';
        clamp(); apply();
        if (animate) setTimeout(function () { canvas.style.transition = ''; }, 520);
        else requestAnimationFrame(function () { canvas.style.transition = ''; });
      }

      function zoomAt(next, cx, cy) {
        var b = bounds();
        cx = cx == null ? b.vw / 2 : cx;
        cy = cy == null ? b.vh / 2 : cy;
        next = Math.max(MIN, Math.min(MAX, next));
        // keep the point under the cursor fixed
        panX = cx - (cx - panX) * (next / zoom);
        panY = cy - (cy - panY) * (next / zoom);
        zoom = next;
        clamp(); apply();
      }

      $('#omapIn') && $('#omapIn').addEventListener('click', function () { zoomAt(zoom * 1.25); });
      $('#omapOut') && $('#omapOut').addEventListener('click', function () { zoomAt(zoom / 1.25); });
      $('#omapFit') && $('#omapFit').addEventListener('click', function () { fit(true); });

      // Wheel over the chart zooms and never scrolls the page. stopPropagation
      // matters as much as preventDefault here: Lenis listens for wheel on the
      // window, so without it the page kept smooth-scrolling underneath. The
      // viewport also carries data-lenis-prevent as a belt-and-braces guard.
      vp.addEventListener('wheel', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var r = vp.getBoundingClientRect();
        zoomAt(zoom * (e.deltaY > 0 ? 0.9 : 1.1), e.clientX - r.left, e.clientY - r.top);
      }, { passive: false });

      // drag to pan (mouse, pen and single-finger touch)
      var pts = new Map(), startDist = 0, startZoom = 1, moved = false;
      vp.addEventListener('pointerdown', function (e) {
        pts.set(e.pointerId, { x: e.clientX, y: e.clientY });
        moved = false;
        // NB: capture is deliberately NOT taken here. Capturing on pointerdown
        // retargets the follow-up `click` to the viewport, so a node's own
        // click handler never fires and profiles cannot be opened. We capture
        // only once the pointer has actually moved (see pointermove).
        if (pts.size === 2) {
          var p = Array.from(pts.values());
          startDist = Math.hypot(p[0].x - p[1].x, p[0].y - p[1].y);
          startZoom = zoom;
        }
      });
      vp.addEventListener('pointermove', function (e) {
        var prev = pts.get(e.pointerId);
        if (!prev) return;
        var dx = e.clientX - prev.x, dy = e.clientY - prev.y;
        pts.set(e.pointerId, { x: e.clientX, y: e.clientY });
        if (!moved && (Math.abs(dx) > 2 || Math.abs(dy) > 2)) {
          moved = true;
          map.classList.add('is-dragging');
          try { vp.setPointerCapture(e.pointerId); } catch (err) {}
        }

        if (pts.size === 2 && startDist) {
          var p = Array.from(pts.values());
          var d = Math.hypot(p[0].x - p[1].x, p[0].y - p[1].y);
          var r = vp.getBoundingClientRect();
          zoomAt(startZoom * (d / startDist),
                 (p[0].x + p[1].x) / 2 - r.left, (p[0].y + p[1].y) / 2 - r.top);
          return;
        }
        panX += dx; panY += dy;
        clamp(); apply();
      });
      function release(e) {
        pts.delete(e.pointerId);
        if (!pts.size) map.classList.remove('is-dragging');
        if (pts.size < 2) startDist = 0;
      }
      vp.addEventListener('pointerup', release);
      vp.addEventListener('pointercancel', release);
      // a drag must not also open a profile
      vp.addEventListener('click', function (e) { if (moved) { e.stopPropagation(); moved = false; } }, true);

      // keyboard: arrows pan, +/- zoom, 0 fits
      vp.addEventListener('keydown', function (e) {
        var step = 60;
        if (e.key === 'ArrowLeft')       { panX += step; }
        else if (e.key === 'ArrowRight') { panX -= step; }
        else if (e.key === 'ArrowUp')    { panY += step; }
        else if (e.key === 'ArrowDown')  { panY -= step; }
        else if (e.key === '+' || e.key === '=') { zoomAt(zoom * 1.2); return; }
        else if (e.key === '-')          { zoomAt(zoom / 1.2); return; }
        else if (e.key === '0')          { fit(true); return; }
        else return;
        e.preventDefault(); clamp(); apply();
      });

      // scroll a specific node into the middle of the viewport
      function centreOn(node, targetZoom) {
        var b = bounds();
        if (targetZoom) zoom = Math.max(MIN, Math.min(MAX, targetZoom));
        var nx = node.offsetLeft + node.offsetWidth / 2;
        var ny = node.offsetTop + node.offsetHeight / 2;
        panX = b.vw / 2 - nx * zoom;
        panY = b.vh / 2 - ny * zoom;
        canvas.style.transition = 'transform .55s cubic-bezier(.22,1,.36,1)';
        clamp(); apply();
        setTimeout(function () { canvas.style.transition = ''; }, 580);
      }
      org._centreOn = centreOn;

      // fit once the avatars have settled, and again on resize
      fit(false);
      window.addEventListener('load', function () { fit(false); });
      var rt;
      window.addEventListener('resize', function () {
        clearTimeout(rt);
        rt = setTimeout(function () { fit(false); }, 160);
      }, { passive: true });

      // stagger the entrance by depth so the chart draws itself top-down
      nodes.forEach(function (n) {
        var d = 0, el = n;
        while ((el = el.parentElement) && el !== canvas) if (el.tagName === 'UL') d++;
        n.style.animationDelay = (reduceMotion ? 0 : Math.min(d * 90, 700)) + 'ms';
      });
    }

    /* ---- highlight one function. The explorer rebuilds its cards on every
       move, so the filter is re-applied from the model rather than from a
       one-time NodeList that would go stale. */
    var chips = $$('.org-chip', org);
    var reset = $('#orgReset');
    var activeFilter = null;

    function paintFilter() {
      org.classList.toggle('is-filtered', !!activeFilter);
      $$('.onode', org).forEach(function (c) {
        c.classList.toggle('is-match', !activeFilter || c.dataset.group === activeFilter);
      });
    }
    function applyFilter(key) {
      activeFilter = key;
      paintFilter();
      chips.forEach(function (c) { c.setAttribute('aria-pressed', String(c.dataset.filter === key)); });
      if (reset) reset.hidden = !key;
    }
    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        applyFilter(chip.getAttribute('aria-pressed') === 'true' ? null : chip.dataset.filter);
      });
    });
    if (reset) reset.addEventListener('click', function () { applyFilter(null); });

    /* ---- person dialog */
    var GROUP_LABEL = {
      lead: 'Leadership Team', exec: 'Executives', biz: 'Business Team',
      dev: 'Software Development', mgr: 'Managers', team: 'Team Members'
    };
    var dlg = $('#person');
    if (!dlg) return;
    var pPic = $('#personPic'), pTag = $('#personTag'), pName = $('#personName'),
        pRole = $('#personRole'), pBlurb = $('#personBlurb'), pExtra = $('#personExtra'),
        pRep = $('#personReports'), pRepList = $('#personReportsList');
    var closeBtn = $('#personClose');
    var pCta = $('#personCta');
    var lastFocus = null;

    function open(node) {
      lastFocus = document.activeElement;
      var img = node.querySelector('.onode__pic img');
      pPic.innerHTML = img
        ? '<img src="' + img.getAttribute('src') + '" alt="" width="360" height="360">'
        : '<span class="org-card__ph" style="width:100%;height:100%;box-shadow:none"></span>';
      pTag.textContent = GROUP_LABEL[node.dataset.group] || '';
      pName.textContent = node.dataset.name;
      pRole.textContent = node.dataset.role;
      pBlurb.textContent = node.dataset.blurb || '';

      if (node.dataset.extra) { pExtra.textContent = node.dataset.extra; pExtra.hidden = false; }
      else pExtra.hidden = true;

      // direct reports live in the <ul> that is a sibling of this node
      var li = node.parentElement;
      var kidUl = li ? li.querySelector(':scope > ul') : null;
      var kids = kidUl ? $$(':scope > li > .onode', kidUl) : [];
      if (kids.length) {
        pRepList.innerHTML = kids.map(function (r) {
          return '<li><b>' + r.dataset.name + '</b><span>' + r.dataset.role + '</span></li>';
        }).join('');
        pRep.hidden = false;
      } else pRep.hidden = true;

      // not everyone in the chart is a person with a page — departments and
      // unpublished members have no data-url, so the link simply stays hidden
      if (pCta) {
        if (node.dataset.url) { pCta.href = node.dataset.url; pCta.hidden = false; }
        else pCta.hidden = true;
      }

      dlg.hidden = false;
      document.body.classList.add('is-locked');
      if (lenis) lenis.stop();
      requestAnimationFrame(function () {
        dlg.classList.add('is-open');
        void dlg.offsetHeight;
        closeBtn.focus();
      });
    }

    function close() {
      dlg.classList.remove('is-open');
      document.body.classList.remove('is-locked');
      if (lenis) lenis.start();
      setTimeout(function () { dlg.hidden = true; }, 380);
      if (lastFocus) lastFocus.focus();
    }

    nodes.forEach(function (n) { n.addEventListener('click', function () { open(n); }); });

    /* ---- Hover card. Shows a person's detail on hover (and on keyboard focus)
       without opening the dialog; the dialog is reached from its CTA. Uses
       hover-intent delays so sweeping across the chart is not a strobe. */
    var tip = $('#otip');
    if (tip && !isTouch) {
      var tPic = $('#otipPic'), tTag = $('#otipTag'), tName = $('#otipName'),
          tRole = $('#otipRole'), tBlurb = $('#otipBlurb'), tExtra = $('#otipExtra'),
          tReports = $('#otipReports'), tCta = $('#otipCta');
      var tipNode = null, showT = 0, hideT = 0, overTip = false;

      function reportsOf(node) {
        var li = node.parentElement;
        var ul = li ? li.querySelector(':scope > ul') : null;
        return ul ? $$(':scope > li > .onode', ul) : [];
      }

      function place(node) {
        var r = node.getBoundingClientRect();
        var tw = tip.offsetWidth, th = tip.offsetHeight;
        var gap = 12;
        var below = r.top - th - gap < 8;          // not enough room above
        tip.classList.toggle('otip--below', below);

        var x = r.left + r.width / 2 - tw / 2;
        x = Math.max(8, Math.min(window.innerWidth - tw - 8, x));
        var y = below ? r.bottom + gap : r.top - th - gap;

        tip.style.setProperty('--x', Math.round(x) + 'px');
        tip.style.setProperty('--y', Math.round(y) + 'px');
        // keep the arrow pointing at the node even after the clamp
        var ax = r.left + r.width / 2 - x;
        tip.style.setProperty('--ax', Math.max(14, Math.min(tw - 14, ax)) + 'px');
      }

      function fill(node) {
        var img = node.querySelector('.onode__pic img');
        tPic.innerHTML = img
          ? '<img src="' + img.getAttribute('src') + '" alt="" width="360" height="360" draggable="false">'
          : '';
        tTag.textContent = GROUP_LABEL[node.dataset.group] || '';
        tName.textContent = node.dataset.name;
        tRole.textContent = node.dataset.role;
        tBlurb.textContent = node.dataset.blurb || '';

        if (node.dataset.extra) { tExtra.textContent = node.dataset.extra; tExtra.hidden = false; }
        else tExtra.hidden = true;

        var kids = reportsOf(node);
        if (kids.length) {
          tReports.innerHTML = '<b>' + kids.length + '</b> direct report' + (kids.length === 1 ? '' : 's') +
            ' · ' + kids.slice(0, 3).map(function (k) { return k.dataset.name; }).join(', ') +
            (kids.length > 3 ? ' +' + (kids.length - 3) + ' more' : '');
          tReports.hidden = false;
        } else tReports.hidden = true;

        // the CTA leaves for the member's own page; departments carry no url
        if (node.dataset.url) { tCta.href = node.dataset.url; tCta.hidden = false; }
        else tCta.hidden = true;

        tip.style.setProperty('--ring', 'var(--g-' + node.dataset.group + ')');
      }

      function show(node) {
        tipNode = node;
        fill(node);
        tip.setAttribute('aria-hidden', 'false');
        // measure once populated, then position, then reveal
        tip.classList.add('is-measuring');
        place(node);
        tip.classList.remove('is-measuring');
        tip.classList.add('is-on');
      }

      function hide() {
        tip.classList.remove('is-on');
        tip.setAttribute('aria-hidden', 'true');
        tipNode = null;
      }

      function queueShow(node) {
        clearTimeout(hideT); clearTimeout(showT);
        if (tipNode === node) return;
        // already open on someone else: swap straight away, no re-delay
        showT = setTimeout(function () { show(node); }, tip.classList.contains('is-on') ? 40 : 150);
      }
      function queueHide() {
        clearTimeout(showT); clearTimeout(hideT);
        hideT = setTimeout(function () { if (!overTip) hide(); }, 180);
      }

      nodes.forEach(function (n) {
        n.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') queueShow(n); });
        n.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') queueHide(); });
        n.addEventListener('focus', function () { queueShow(n); });
        n.addEventListener('blur', function () { queueHide(); });
      });

      // moving the pointer into the card keeps it open so the CTA is reachable
      tip.addEventListener('pointerenter', function () { overTip = true; clearTimeout(hideT); });
      tip.addEventListener('pointerleave', function () { overTip = false; queueHide(); });

      // the CTA is a plain link now — just get the hover card out of the way
      tCta.addEventListener('click', function () { hide(); });

      // any camera movement invalidates the anchor
      ['wheel', 'pointerdown'].forEach(function (evt) {
        vp && vp.addEventListener(evt, function () { clearTimeout(showT); hide(); }, { passive: true });
      });
      window.addEventListener('scroll', function () { if (tipNode) hide(); }, { passive: true });
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && tipNode) hide(); });
    }

    closeBtn.addEventListener('click', close);
    dlg.addEventListener('click', function (e) { if (e.target === dlg) close(); });
    document.addEventListener('keydown', function (e) {
      if (dlg.hidden) return;
      if (e.key === 'Escape') { close(); return; }
      if (e.key === 'Tab') {
        var f = $$('button, a[href]', dlg).filter(function (x) { return x.offsetParent !== null; });
        if (!f.length) return;
        var first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });

    /* ---- search highlights every match and flies to the first */
    var search = $('#orgSearch');
    if (search) {
      search.addEventListener('input', function () {
        var q = search.value.trim().toLowerCase();
        var first = null;
        nodes.forEach(function (n) {
          var hit = !!q && ((n.dataset.name || '').toLowerCase().indexOf(q) > -1 ||
                            (n.dataset.role || '').toLowerCase().indexOf(q) > -1);
          n.classList.toggle('is-hit', hit);
          if (hit && !first) first = n;
        });
        if (first && org._centreOn) org._centreOn(first, 1);
      });
    }

  })();

  /* ---------------------------------------------------------- Testimonials
     Every [data-testimonials] slider on the page gets its own instance, with
     the arrows resolved inside its own section — so a second carousel on
     another page cannot steal the first one's controls. */
  (function testimonials() {
    var sliders = $$('[data-testimonials]');
    if (!sliders.length || !window.Swiper) return;

    sliders.forEach(function (node) {
      var scope = node.closest('section') || document;
      var prev = scope.querySelector('[data-tprev]');
      var next = scope.querySelector('[data-tnext]');
      var dots = node.querySelector('.swiper-pagination');

      new window.Swiper(node, {
        slidesPerView: 1,
        spaceBetween: 20,
        grabCursor: true,
        autoHeight: false,
        speed: 620,
        watchOverflow: true,          // no dragging when everything already fits
        a11y: { enabled: true },
        keyboard: { enabled: true },
        autoplay: reduceMotion ? false : { delay: 5200, disableOnInteraction: false, pauseOnMouseEnter: true },
        pagination: dots ? { el: dots, clickable: true } : false,
        navigation: (prev && next) ? { prevEl: prev, nextEl: next } : false,
        breakpoints: {
          640: { slidesPerView: 2, spaceBetween: 20 },
          1080: { slidesPerView: 3, spaceBetween: 24 }
        }
      });
    });

    /* Long reviews are clamped; this opens one without moving the slide. */
    var clamps = $$('.quote-card__more').map(function (btn) {
      var text = btn.previousElementSibling;
      if (!text) return null;

      btn.setAttribute('aria-expanded', 'false');
      btn.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = text.classList.toggle('is-open');
        btn.setAttribute('aria-expanded', String(open));
        btn.textContent = open ? 'Read less' : 'Read more';
      });
      return { btn: btn, text: text };
    }).filter(Boolean);

    /* Whether a quote overflows its clamp depends on the rendered line box, so
       this cannot be decided once at parse time — the webfont had not landed
       yet and the answer flips once it does. Re-measure when the fonts are
       ready and whenever the column width changes. */
    function measureClamps() {
      clamps.forEach(function (c) {
        if (c.text.classList.contains('is-open')) return;
        c.btn.hidden = c.text.scrollHeight <= c.text.clientHeight + 2;
      });
    }

    measureClamps();
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(measureClamps);
    }
    var reflow;
    window.addEventListener('resize', function () {
      clearTimeout(reflow);
      reflow = setTimeout(measureClamps, 180);
    }, { passive: true });
  })();

  /* ------------------------------------------------------------------- FAQ */
  (function faq() {
    var buttons = $$('.faq__q');
    buttons.forEach(function (btn) {
      var panel = document.getElementById(btn.getAttribute('aria-controls'));
      if (!panel) return;
      btn.addEventListener('click', function () {
        var open = btn.getAttribute('aria-expanded') === 'true';
        // close siblings for a cleaner read
        buttons.forEach(function (other) {
          if (other === btn) return;
          var p = document.getElementById(other.getAttribute('aria-controls'));
          other.setAttribute('aria-expanded', 'false');
          if (p) p.style.maxHeight = '0px';
        });
        btn.setAttribute('aria-expanded', String(!open));
        panel.style.maxHeight = open ? '0px' : panel.scrollHeight + 'px';
      });
    });

    window.addEventListener('resize', function () {
      buttons.forEach(function (btn) {
        if (btn.getAttribute('aria-expanded') !== 'true') return;
        var p = document.getElementById(btn.getAttribute('aria-controls'));
        if (p) p.style.maxHeight = p.scrollHeight + 'px';
      });
    });
  })();


  /* ---------------------------------------------- Inline quote estimator */
  (function quoteInline() {
    var root = $('#quoteInline');
    var form = $('#quoteInlineForm');
    if (!root || !form) return;

    var steps = $$('.qi__step', root);
    var result = $('.qi__result', root);
    var bar = $('#qiBar');
    var count = $('#qiCount'), countNow = $('#qiCountNow'), countName = $('#qiCountName');
    var back = $('#qiBack'), next = $('#qiNext'), submit = $('#qiSubmit'), done = $('#qiDone');
    var assure = $('#qiAssure');
    var status = $('#qiStatus');
    var last = steps.length;            // index of the result panel
    var step = 0;
    var estimateUrl = root.dataset.estimate;
    var submitUrl = root.dataset.submit;
    // The meta tag is the usual source; fall back to the form's own @csrf
    // field so the estimator keeps working if a layout drops the meta.
    var meta = document.querySelector('meta[name="csrf-token"]');
    var hiddenTok = form.querySelector('input[name="_token"]');
    var token = meta ? meta.content : (hiddenTok ? hiddenTok.value : '');

    /* The same rules the server applies, mirrored here so the estimator still
       produces a figure on a static build with no endpoint behind it — and so
       a dropped request never leaves the visitor staring at a blank result.
       Where an endpoint is configured the server's answer always wins. */
    var RULES = {
      services: {
        'architecture-design':        { label: 'Architecture design', hours: 60 },
        'revit-drafting':             { label: 'Revit drafting & BIM', hours: 70 },
        'construction-permit-sets':   { label: 'Construction & permit sets', hours: 55 },
        '3d-modeling-rendering':      { label: '3D modelling & rendering', hours: 25 },
        '3d-architectural-animation': { label: '3D animation', hours: 45 },
        'point-cloud-to-bim':         { label: 'Point cloud to BIM', hours: 50 },
        'interior-design':            { label: 'Interior design', hours: 40 },
        'landscape-architecture':     { label: 'Landscape architecture', hours: 30 },
      },
      size:       { small: 0.6, medium: 1, large: 1.7, xl: 2.6 },
      scope:      { concept: 0.5, design: 1, permit: 1.35, full: 1.8 },
      timeline:   { standard: 1, priority: 1.15, rush: 1.3 },
      engagement: { subscription: 11.84, hourly: 28, fixed: 22 },
      spread: 0.18,
      minimum: 250,
    };

    /* PHP's round() scrubs floating-point noise before rounding, so a value
       that lands on 80.49999999999999 still goes up. Math.round does not, and
       without the same nudge a handful of combinations fall a band low. */
    function phpRound(n) {
      return Math.round(Number(n.toPrecision(15)));
    }

    function estimateLocally(a) {
      var hours = 0, picked = [];
      (a.services || []).forEach(function (slug) {
        var s = RULES.services[slug];
        if (!s) return;
        hours += s.hours;
        picked.push(s.label);
      });
      if (!hours) hours = 40;

      var sizeF = RULES.size[a.size] || 1;
      var scopeF = RULES.scope[a.scope] || 1;
      var timeF = RULES.timeline[a.timeline] || 1;
      var engagement = a.engagement || 'subscription';
      var rate = RULES.engagement[engagement] || 11.84;

      // Disciplines on one project share context, so taper past the first.
      var count = Math.max(1, picked.length);
      var overlap = count > 1 ? 1 - Math.min(0.18, (count - 1) * 0.045) : 1;

      var total = hours * sizeF * scopeF * overlap;
      var hoursLow = phpRound(total * (1 - RULES.spread));
      var hoursHigh = phpRound(total * (1 + RULES.spread));
      var priceLow = Math.max(RULES.minimum, phpRound(hoursLow * rate * timeF / 10) * 10);
      var priceHigh = Math.max(priceLow, phpRound(hoursHigh * rate * timeF / 10) * 10);

      var plan = engagement === 'hourly' ? 'Hourly — $28/hr'
        : engagement === 'fixed' ? 'Fixed price'
        : hoursHigh > 200 ? 'Standard — $2,500/mo' : 'Basic — $1,895/mo';

      return {
        hours_low: hoursLow, hours_high: hoursHigh,
        price_low: priceLow, price_high: priceHigh,
        recommended_plan: plan,
        breakdown: {
          services: picked,
          base_hours: hours,
          size_factor: sizeF,
          scope_factor: scopeF,
          timeline_factor: timeF,
          multi_service_discount: phpRound((1 - overlap) * 100) + '%',
          rate: rate,
        },
      };
    }

    function money(n) {
      return '$' + Number(n || 0).toLocaleString('en-US');
    }

    function say(msg, state) {
      if (!status) return;
      status.textContent = msg || '';
      status.setAttribute('data-state', state || '');
    }

    function answers() {
      var data = new FormData(form);
      return {
        services: data.getAll('services[]'),
        project_type: data.get('project_type'),
        size: data.get('size'),
        scope: data.get('scope'),
        timeline: data.get('timeline'),
        engagement: data.get('engagement'),
      };
    }

    /* Kept warm in the background but never shown until the result panel: the
       figure is only wanted once, at the end. Holding a fresh one means the
       result appears instantly, and gives us something to fall back on if the
       submit request fails. Recalculated server-side so the browser and the
       stored quote can never disagree about the rules. */
    var pending = null;
    function refresh() {
      clearTimeout(pending);
      pending = setTimeout(function () {
        var a = answers();
        root._estimate = estimateLocally(a);
        if (!estimateUrl) return;

        fetch(estimateUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
          body: JSON.stringify(a),
        })
          .then(function (r) { return r.json(); })
          .then(function (e) { root._estimate = e; })
          .catch(function () { /* the local figure already covers this */ });
      }, 180);
    }

    function show(i, goingBack) {
      step = Math.max(0, Math.min(last, i));

      steps.forEach(function (s, n) {
        s.classList.toggle('is-on', n === step);
        s.classList.toggle('is-back', !!goingBack);
      });
      result.classList.toggle('is-on', step === last);
      result.classList.toggle('is-back', !!goingBack);

      if (bar) bar.style.width = Math.round(((step + 1) / (last + 1)) * 100) + '%';
      if (countNow) countNow.textContent = Math.min(step + 1, last);
      if (countName) countName.textContent = step === last ? '' : (steps[step].dataset.name || '');
      if (count) count.hidden = step === last;

      back.hidden = step === 0 || step === last;
      next.hidden = step >= last - 1;
      submit.hidden = step !== last - 1;
      done.hidden = step !== last;
      if (assure) assure.hidden = step === last;
    }

    /* show() only paints; go() is the version that reacts to a person, so it
       also moves focus and drags the card back into view. Keeping them apart
       means the first paint on page load cannot steal focus or scroll. */
    function go(i, goingBack) {
      show(i, goingBack);

      var panel = step === last ? result : steps[step];
      var first = panel.querySelector('input:not([type="hidden"]), textarea, a[href], button');
      if (first) first.focus({ preventScroll: true });

      var header = $('#siteHeader');
      var minTop = (header ? header.offsetHeight : 0) + 8;
      if (root.getBoundingClientRect().top < minTop) scrollToTarget(root);
    }

    function validateContact() {
      var name = $('#qiName'), email = $('#qiEmail');
      name.classList.remove('is-bad');
      email.classList.remove('is-bad');

      if (!name.value.trim()) {
        name.classList.add('is-bad');
        name.focus();
        say('Please add your name so we know who to reply to.', 'err');
        return false;
      }
      if (!email.checkValidity()) {
        email.classList.add('is-bad');
        email.focus();
        say('That email address does not look right.', 'err');
        return false;
      }
      return true;
    }

    next.addEventListener('click', function () {
      // An estimate built on nothing is a number we would have to walk back.
      if (step === 0 && !form.querySelector('input[name="services[]"]:checked')) {
        say('Pick at least one service so the estimate means something.', 'err');
        return;
      }
      say('');
      go(step + 1);
    });

    back.addEventListener('click', function () { say(''); go(step - 1, true); });

    submit.addEventListener('click', function () {
      say('');
      if (!validateContact()) return;

      var data = new FormData(form);
      var label = submit.innerHTML;

      if (!submitUrl) {
        // No backend wired up — show the figure and hand the answers to the
        // visitor's mail client so the enquiry is not lost.
        paint(root._estimate || estimateLocally(answers()));
        go(last);
        var lines = [];
        data.forEach(function (v, k) {
          if (k !== 'company_url' && k !== '_token' && v) lines.push(k + ': ' + v);
        });
        var e = root._estimate;
        if (e) lines.push('estimate: ' + money(e.price_low) + ' - ' + money(e.price_high));
        say('Here is your estimate. Send the details over and an architect confirms within 24 hours.', 'ok');
        if (done) {
          done.href = 'mailto:info@archilance.net?subject=' +
            encodeURIComponent('Quote request from ' + (data.get('name') || 'website')) +
            '&body=' + encodeURIComponent(lines.join('\n'));
        }
        return;
      }

      submit.disabled = true;
      submit.innerHTML = 'Working it out…';

      fetch(submitUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, Accept: 'application/json' },
        body: data,
      })
        .then(function (r) { return r.json(); })
        .then(function (res) {
          if (!res.ok) throw new Error('rejected');
          paint(res.estimate);
          go(last);
          say('Sent — an architect confirms the scope within 24 hours.', 'ok');
        })
        .catch(function () {
          // Still show the figure; only the delivery failed.
          paint(root._estimate || estimateLocally(answers()));
          go(last);
          say('We could not send it just now. Email info@archilance.net and we will pick it up.', 'err');
        })
        .finally(function () { submit.disabled = false; submit.innerHTML = label; });
    });

    function paint(e) {
      if (!e) return;
      $('#qiPriceLow').textContent = money(e.price_low);
      $('#qiPriceHigh').textContent = money(e.price_high);
      $('#qiHours').textContent = 'Roughly ' + e.hours_low + '–' + e.hours_high + ' hours of studio time';
      $('#qiPlan').textContent = 'Best fit: ' + e.recommended_plan;

      var sum = $('#qiSummary');
      sum.innerHTML = '';
      (e.breakdown && e.breakdown.services ? e.breakdown.services : []).forEach(function (s) {
        var li = document.createElement('li');
        li.textContent = s;
        sum.appendChild(li);
      });
      if (e.breakdown && e.breakdown.multi_service_discount !== '0%') {
        var li = document.createElement('li');
        li.textContent = e.breakdown.multi_service_discount + ' multi-service saving';
        sum.appendChild(li);
      }
    }

    form.addEventListener('change', function (e) {
      refresh();
      if (e.target.classList) e.target.classList.remove('is-bad');
    });

    /* Steps that ask a single either/or move on by themselves — the delay is
       long enough to watch the tick land, short enough to feel immediate.

       Driven by the click and not by 'change' for two reasons: every one of
       those steps opens with a sensible default, and choosing the option that
       is already selected fires no change event at all — the form would just
       sit there. It also leaves keyboard users free to arrow through the
       options without the step running away from them; Enter still advances. */
    var autoT = null;
    form.addEventListener('click', function (e) {
      var panel = steps[step];
      if (!panel || !panel.hasAttribute('data-auto')) return;

      // climb to the option wrapper — the target may be the label, the tick or
      // the input itself, and an SVG target rules out a plain closest() call
      var node = e.target;
      while (node && node !== form && !(node.classList && node.classList.contains('qi__opt'))) {
        node = node.parentNode;
      }
      if (!node || node === form) return;

      if (reduceMotion) { if (step < last - 1) go(step + 1); return; }
      clearTimeout(autoT);
      autoT = setTimeout(function () { if (step < last - 1) go(step + 1); }, 420);
    });

    // No action attribute — a stray Enter must never reload the page.
    form.addEventListener('submit', function (e) { e.preventDefault(); });

    root.addEventListener('keydown', function (e) {
      if (e.key !== 'Enter') return;
      var t = e.target;
      if (t.tagName === 'TEXTAREA' || t.tagName === 'BUTTON' || t.tagName === 'A') return;
      e.preventDefault();
      if (step === last - 1) submit.click();
      else if (step < last - 1) next.click();
    });

    show(0);
    refresh();
  })();

  /* ------------------------------------------------------- FAQ hub filter */
  (function faqHub() {
    var nav = $('#faqNav');
    var groups = $$('.faq-group');
    if (!nav || !groups.length) return;

    var btns = $$('.faq-nav__btn', nav);
    var items = $$('.faq__item');
    var search = $('#faqSearch');
    var count = $('#faqCount');
    var empty = $('#faqEmpty');
    var cat = 'all';

    function apply() {
      var q = (search && search.value.trim().toLowerCase()) || '';
      var shown = 0;

      items.forEach(function (it) {
        var inCat = cat === 'all' || it.closest('.faq-group').dataset.cat === cat;
        var hit = !q || (it.dataset.q || '').indexOf(q) > -1;
        var on = inCat && hit;
        it.classList.toggle('is-hidden', !on);
        it.classList.toggle('is-hit', !!q && on);
        if (on) shown++;
      });

      // a group with nothing left in it should not leave a stray heading
      groups.forEach(function (g) {
        var any = $$('.faq__item', g).some(function (i) { return !i.classList.contains('is-hidden'); });
        g.classList.toggle('is-hidden', !any);
      });

      if (empty) empty.hidden = shown > 0;
      if (count) {
        count.textContent = q || cat !== 'all'
          ? shown + (shown === 1 ? ' answer' : ' answers') + (q ? ' matching “' + search.value.trim() + '”' : '')
          : '';
      }
    }

    btns.forEach(function (b) {
      b.addEventListener('click', function () {
        cat = b.dataset.cat;
        btns.forEach(function (x) { x.setAttribute('aria-pressed', String(x === b)); });
        apply();
      });
    });

    if (search) {
      var t;
      search.addEventListener('input', function () {
        clearTimeout(t);
        t = setTimeout(apply, 120);          // debounce so long lists do not thrash
      });
      // Escape clears rather than trapping the visitor in a filtered view
      search.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && search.value) { search.value = ''; apply(); }
      });
    }

    // deep link: /faqs/?q=revit or #pricing
    var qs = (location.search.match(/[?&]q=([^&]+)/) || [])[1];
    if (qs && search) { search.value = decodeURIComponent(qs.replace(/\+/g, ' ')); }
    var hash = location.hash.slice(1);
    if (hash && btns.some(function (b) { return b.dataset.cat === hash; })) {
      cat = hash;
      btns.forEach(function (x) { x.setAttribute('aria-pressed', String(x.dataset.cat === hash)); });
    }
    apply();
  })();

  /* ------------------------------------------------------------- Lead form */
  (function leadForm() {
    var form = $('#leadForm');
    var status = $('#formStatus');
    if (!form) return;

    function say(msg, state) {
      if (!status) return;
      status.textContent = msg;
      status.setAttribute('data-state', state || '');
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      say('', '');

      // honeypot — silently accept and drop
      if (form.company_url && form.company_url.value) { say('Thank you — your enquiry has been sent.', 'ok'); form.reset(); return; }

      var invalid = null;
      $$('[required]', form).forEach(function (f) {
        if (!f.checkValidity() && !invalid) invalid = f;
      });
      if (invalid) {
        invalid.focus();
        say('Please complete the highlighted fields so we can get back to you.', 'err');
        return;
      }

      var data = new FormData(form);
      var endpoint = form.dataset.endpoint;
      var btn = form.querySelector('button[type="submit"]');
      var label = btn ? btn.innerHTML : '';

      if (!endpoint) {
        // No backend wired yet — hand off to the visitor's mail client so no lead is lost.
        var lines = [];
        data.forEach(function (v, k) { if (k !== 'company_url' && v) lines.push(k + ': ' + v); });
        var subject = 'Project enquiry from ' + (data.get('name') || 'website');
        window.location.href = 'mailto:info@archilance.net?subject=' + encodeURIComponent(subject) +
          '&body=' + encodeURIComponent(lines.join('\n'));
        say('Opening your email app — press send and we will reply within 24 hours.', 'ok');
        return;
      }

      if (btn) { btn.disabled = true; btn.innerHTML = 'Sending…'; }

      fetch(endpoint, { method: 'POST', body: data, headers: { Accept: 'application/json' } })
        .then(function (res) {
          if (!res.ok) throw new Error('Request failed');
          form.reset();
          say('Thank you — your enquiry is with the design team. We reply within 24 hours.', 'ok');
        })
        .catch(function () {
          say('Something went wrong. Please email info@archilance.net and we will pick it up right away.', 'err');
        })
        .finally(function () {
          if (btn) { btn.disabled = false; btn.innerHTML = label; }
        });
    });
  })();

  /* ------------------------------------------------------------- Marquee fill */
  (function marquee() {
    $$('[data-marquee]').forEach(function (wrap) {
      var track = $('.marquee__track', wrap);
      if (!track) return;
      // duplicate until the track comfortably exceeds the viewport, then clone once more
      var guard = 0;
      while (track.scrollWidth < wrap.offsetWidth * 2 && guard < 6) {
        Array.prototype.forEach.call(track.children, function (li) {
          track.appendChild(li.cloneNode(true));
        });
        guard++;
      }
      var clone = track.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      wrap.appendChild(clone);
    });
  })();

  /* ------------------------------------------------------- Active nav + year */
  (function misc() {
    var year = $('#year');
    if (year) year.textContent = String(new Date().getFullYear());

    var links = $$('.nav__link[href^="#"]');
    var sections = links
      .map(function (l) { return document.getElementById(l.getAttribute('href').slice(1)); })
      .filter(Boolean);
    if (!sections.length || !('IntersectionObserver' in window)) return;

    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        links.forEach(function (l) {
          var on = l.getAttribute('href') === '#' + entry.target.id;
          if (on) l.setAttribute('aria-current', 'page');
          else l.removeAttribute('aria-current');
        });
      });
    }, { rootMargin: '-45% 0px -50% 0px' });

    sections.forEach(function (s) { spy.observe(s); });
  })();
})();
