/* Master DIF — interactions */
(function () {
  "use strict";

  /* ---- Theme toggle (persisted) ---- */
  var root = document.documentElement;
  try {
    var saved = localStorage.getItem("dif-theme");
    if (saved) root.setAttribute("data-theme", saved);
  } catch (e) {}

  document.addEventListener("click", function (e) {
    var t = e.target.closest("[data-theme-toggle]");
    if (!t) return;
    var cur = root.getAttribute("data-theme");
    if (!cur) {
      cur = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }
    var next = cur === "dark" ? "light" : "dark";
    root.setAttribute("data-theme", next);
    try { localStorage.setItem("dif-theme", next); } catch (e2) {}
  });

  /* ---- Mobile nav ---- */
  document.addEventListener("click", function (e) {
    if (e.target.closest("[data-burger]")) {
      document.body.classList.toggle("nav-open");
    } else if (e.target.closest(".mobile-nav a")) {
      document.body.classList.remove("nav-open");
    }
  });

  /* ---- Scroll reveal ---- */
  var reveals = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add("in"); io.unobserve(en.target); }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -8% 0px" });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add("in"); });
  }

  /* ---- Count-up for [data-count] ---- */
  function animateCount(el) {
    var target = parseFloat(el.getAttribute("data-count"));
    var suffix = el.getAttribute("data-suffix") || "";
    var prefix = el.getAttribute("data-prefix") || "";
    var dur = 1400, start = null;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      var val = Math.round(target * eased);
      el.textContent = prefix + val.toLocaleString("fr-FR") + suffix;
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  var counters = document.querySelectorAll("[data-count]");
  if ("IntersectionObserver" in window && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    var io2 = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { animateCount(en.target); io2.unobserve(en.target); }
      });
    }, { threshold: 0.6 });
    counters.forEach(function (el) { io2.observe(el); });
  } else {
    counters.forEach(function (el) {
      el.textContent = (el.getAttribute("data-prefix") || "") + el.getAttribute("data-count") + (el.getAttribute("data-suffix") || "");
    });
  }

  /* ---- Tabs (panels are resolved by id, wherever they live in the DOM) ---- */
  document.querySelectorAll("[data-tabs]").forEach(function (group) {
    var btns = group.querySelectorAll("[role=tab]");
    btns.forEach(function (btn) {
      btn.addEventListener("click", function () {
        btns.forEach(function (b) {
          var selected = b === btn;
          b.setAttribute("aria-selected", selected ? "true" : "false");
          var panel = document.getElementById(b.getAttribute("aria-controls"));
          if (panel) panel.hidden = !selected;
        });
      });
    });
  });

  /* ---- Carousel ---- */
  document.querySelectorAll("[data-carousel]").forEach(function (root) {
    var track = root.querySelector(".car-track");
    if (!track) return;
    var prev = root.querySelector("[data-car-prev]");
    var next = root.querySelector("[data-car-next]");
    var bar = root.querySelector(".car-progress i");
    var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    function step() {
      var card = track.firstElementChild;
      if (!card) return 300;
      var gap = parseFloat(getComputedStyle(track).gap) || 16;
      return card.getBoundingClientRect().width + gap;
    }
    function update() {
      var max = track.scrollWidth - track.clientWidth;
      if (prev) prev.disabled = track.scrollLeft <= 8;
      if (next) next.disabled = track.scrollLeft >= max - 8;
      if (bar) {
        var visible = track.scrollWidth > 0 ? track.clientWidth / track.scrollWidth : 1;
        var frac = max > 0 ? track.scrollLeft / max : 0;
        bar.style.width = (visible * 100) + "%";
        bar.style.transform = "translateX(" + (frac * (100 / visible - 100)) + "%)";
      }
    }
    function go(dir) {
      track.scrollBy({ left: dir * step(), behavior: reduced ? "auto" : "smooth" });
    }
    if (prev) prev.addEventListener("click", function () { go(-1); });
    if (next) next.addEventListener("click", function () { go(1); });

    var ticking = false;
    track.addEventListener("scroll", function () {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(function () { update(); ticking = false; });
    });
    window.addEventListener("resize", update);
    update();
  });

  /* ---- Marquee infini (trombinoscopes) ---- */
  document.querySelectorAll("[data-marquee]").forEach(function (root) {
    var track = root.querySelector(".mq-track");
    if (!track || track.children.length === 0) return;
    var dir = parseFloat(root.getAttribute("data-dir") || "1");
    var reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var originals = Array.prototype.slice.call(track.children);

    function addSet() {
      originals.forEach(function (el) {
        var c = el.cloneNode(true);
        c.setAttribute("aria-hidden", "true");
        track.appendChild(c);
      });
    }
    addSet();
    var setWidth = track.children[originals.length].offsetLeft - track.children[0].offsetLeft;
    var guard = 0;
    while (setWidth > 0 && track.scrollWidth - setWidth < track.clientWidth + 200 && guard < 6) {
      addSet(); guard++;
    }
    if (dir < 0) track.scrollLeft = setWidth;

    function wrap() {
      if (setWidth <= 0) return;
      if (track.scrollLeft >= setWidth) track.scrollLeft -= setWidth;
      else if (track.scrollLeft <= 0 && dir < 0) track.scrollLeft += setWidth;
      else if (track.scrollLeft < 0) track.scrollLeft += setWidth;
    }

    var paused = false, idleTimer = null, last = null;
    function hold(ms) {
      paused = true;
      clearTimeout(idleTimer);
      idleTimer = setTimeout(function () { paused = false; }, ms || 2500);
    }
    root.addEventListener("pointerenter", function () { paused = true; clearTimeout(idleTimer); });
    root.addEventListener("pointerleave", function () { paused = false; });
    root.addEventListener("focusin", function () { paused = true; });
    root.addEventListener("focusout", function () { paused = false; });
    track.addEventListener("wheel", function () { hold(2500); }, { passive: true });
    track.addEventListener("touchstart", function () { hold(3000); }, { passive: true });

    function step() {
      var c = track.firstElementChild;
      return c ? (c.getBoundingClientRect().width + (parseFloat(getComputedStyle(track).gap) || 16)) * 3 : 500;
    }
    var prev = root.querySelector("[data-car-prev]");
    var next = root.querySelector("[data-car-next]");
    if (prev) prev.addEventListener("click", function () { hold(3000); track.scrollBy({ left: -step(), behavior: reduced ? "auto" : "smooth" }); });
    if (next) next.addEventListener("click", function () { hold(3000); track.scrollBy({ left: step(), behavior: reduced ? "auto" : "smooth" }); });

    /* Accumulateur flottant : scrollLeft arrondit les petits incréments,
       on maintient donc la position exacte à part. */
    var pos = track.scrollLeft;
    function tick(ts) {
      if (last === null) last = ts;
      var dt = (ts - last) / 1000;
      last = ts;
      if (!paused && dt < 0.2) {
        if (Math.abs(track.scrollLeft - pos) > 1.5) pos = track.scrollLeft; // resync après scroll manuel
        pos += dir * 24 * dt;
        if (setWidth > 0) {
          if (pos >= setWidth) pos -= setWidth;
          if (pos < 0) pos += setWidth;
        }
        track.scrollLeft = pos;
      } else {
        pos = track.scrollLeft;
        wrap();
      }
      requestAnimationFrame(tick);
    }
    if (!reduced) {
      requestAnimationFrame(tick);
    } else {
      track.addEventListener("scroll", wrap, { passive: true });
    }
  });

  /* ---- Accordion ---- */
  document.querySelectorAll("[data-acc] > .acc-item > .acc-head").forEach(function (head) {
    head.addEventListener("click", function () {
      var item = head.parentElement;
      var open = item.classList.toggle("open");
      head.setAttribute("aria-expanded", open ? "true" : "false");
    });
  });
})();
