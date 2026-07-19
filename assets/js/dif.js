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

  /* ---- Accordion ---- */
  document.querySelectorAll("[data-acc] > .acc-item > .acc-head").forEach(function (head) {
    head.addEventListener("click", function () {
      var item = head.parentElement;
      var open = item.classList.toggle("open");
      head.setAttribute("aria-expanded", open ? "true" : "false");
    });
  });
})();
