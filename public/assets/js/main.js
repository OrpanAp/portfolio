(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    const root = document.documentElement;
    const body = document.body;
    const header = document.querySelector(".site-header");
    const navbar = document.querySelector(".site-navbar");
    const themeToggle = document.getElementById("theme-toggle");
    const menuToggle = document.querySelector("[data-menu-toggle]");
    const menu = document.querySelector("[data-menu]");
    const menuClose = document.querySelector("[data-menu-close]");
    const backToTop = document.querySelector("[data-back-to-top]");
    const progress = document.querySelector(".scroll-progress-bar");

    function syncThemeButton() {
      if (!themeToggle) return;
      const isDark = root.classList.contains("dark-theme");
      themeToggle.setAttribute("aria-pressed", String(isDark));
      themeToggle.setAttribute("aria-label", isDark ? "Switch to light theme" : "Switch to dark theme");
    }

    syncThemeButton();

    if (themeToggle) {
      themeToggle.addEventListener("click", function () {
        const isDark = root.classList.toggle("dark-theme");
        root.dataset.theme = isDark ? "dark" : "light";
        localStorage.setItem("theme", isDark ? "dark" : "light");
        syncThemeButton();
      });
    }

    if (window.matchMedia) {
      const media = window.matchMedia("(prefers-color-scheme: dark)");
      const onSystemThemeChange = function (event) {
        if (!localStorage.getItem("theme")) {
          root.classList.toggle("dark-theme", event.matches);
          root.dataset.theme = event.matches ? "dark" : "light";
          syncThemeButton();
        }
      };
      if (media.addEventListener) media.addEventListener("change", onSystemThemeChange);
    }

    function closeMenu() {
      if (!header || !menuToggle) return;
      header.classList.remove("menu-open");
      body.classList.remove("menu-open");
      menuToggle.setAttribute("aria-expanded", "false");
      menuToggle.setAttribute("aria-label", "Open navigation menu");
    }

    function openMenu() {
      if (!header || !menuToggle) return;
      header.classList.add("menu-open");
      body.classList.add("menu-open");
      menuToggle.setAttribute("aria-expanded", "true");
      menuToggle.setAttribute("aria-label", "Close navigation menu");
      const firstLink = menu ? menu.querySelector("a") : null;
      if (firstLink) firstLink.focus();
    }

    if (menuToggle) {
      menuToggle.addEventListener("click", function () {
        const open = menuToggle.getAttribute("aria-expanded") === "true";
        open ? closeMenu() : openMenu();
      });
    }
    if (menuClose) menuClose.addEventListener("click", closeMenu);
    if (menu) menu.querySelectorAll("a").forEach(function (link) { link.addEventListener("click", closeMenu); });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") closeMenu();
      if (event.key !== "Tab" || !header || !header.classList.contains("menu-open") || !menu) return;
      const focusable = menu.querySelectorAll("a, button");
      if (!focusable.length) return;
      const first = focusable[0];
      const last = focusable[focusable.length - 1];
      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    });

    let ticking = false;
    function updateScrollUI() {
      const scrollTop = window.scrollY;
      const max = document.documentElement.scrollHeight - window.innerHeight;
      const ratio = max > 0 ? scrollTop / max : 0;
      if (navbar) navbar.classList.toggle("is-scrolled", scrollTop > 12);
      if (progress) progress.style.transform = `scaleX(${ratio})`;
      if (backToTop) backToTop.classList.toggle("is-visible", scrollTop > window.innerHeight * 0.55);
      ticking = false;
    }

    window.addEventListener("scroll", function () {
      if (!ticking) {
        window.requestAnimationFrame(updateScrollUI);
        ticking = true;
      }
    }, { passive: true });
    updateScrollUI();

    if (backToTop) {
      backToTop.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
    }

    const revealItems = document.querySelectorAll("[data-reveal]");
    if ("IntersectionObserver" in window && revealItems.length) {
      const observer = new IntersectionObserver(function (entries, instance) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;
          entry.target.classList.add("is-visible");
          instance.unobserve(entry.target);
        });
      }, { threshold: 0.08, rootMargin: "0px 0px -35px" });
      revealItems.forEach(function (item, index) {
        item.style.transitionDelay = `${Math.min(index * 35, 180)}ms`;
        observer.observe(item);
      });
    } else {
      revealItems.forEach(function (item) { item.classList.add("is-visible"); });
    }

    document.querySelectorAll("img").forEach(function (image) {
      if (image.complete) {
        image.classList.add("is-loaded");
      } else {
        image.addEventListener("load", function () { image.classList.add("is-loaded"); }, { once: true });
        image.addEventListener("error", function () { image.classList.add("is-loaded"); }, { once: true });
      }
    });

    document.querySelectorAll(".portfolio-card-media, .featured-card-image, .profile-image-wrapper, .portfolio-detail-thumbnail").forEach(function (media) {
      const image = media.querySelector("img");
      if (!image) media.classList.add("is-loaded");
      else if (image.complete) media.classList.add("is-loaded");
      else image.addEventListener("load", function () { media.classList.add("is-loaded"); }, { once: true });
    });

    document.querySelectorAll(".portfolio-live-preview-frame").forEach(function (frame) {
      const iframe = frame.querySelector("iframe");
      if (!iframe) return;
      let settled = false;
      const settle = function () {
        if (settled) return;
        settled = true;
        frame.classList.add("is-loaded");
      };
      iframe.addEventListener("load", settle, { once: true });
      window.setTimeout(settle, 6500);
    });

    document.querySelectorAll("[data-iframe-fullscreen]").forEach(function (button) {
      button.addEventListener("click", function () {
        const frame = button.closest(".portfolio-live-preview-frame");
        if (!frame) return;
        if (document.fullscreenElement) {
          document.exitFullscreen().catch(function () {});
        } else if (frame.requestFullscreen) {
          frame.requestFullscreen().catch(function () {});
        }
      });
    });

    const carousel = document.querySelector(".featured-carousel");
    if (carousel) initCarousel(carousel);

    initMagneticButtons();
  });

  function initMagneticButtons() {
    if (window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    if (window.matchMedia && window.matchMedia("(pointer: coarse)").matches) return;
    document.querySelectorAll(".magnetic-button").forEach(function (button) {
      button.addEventListener("pointermove", function (event) {
        const rect = button.getBoundingClientRect();
        const x = (event.clientX - rect.left - rect.width / 2) * 0.12;
        const y = (event.clientY - rect.top - rect.height / 2) * 0.12;
        button.style.transform = `translate(${x}px, ${y}px)`;
      });
      button.addEventListener("pointerleave", function () {
        button.style.transform = "";
      });
    });
  }

  function initCarousel(carousel) {
    const viewport = carousel.querySelector(".featured-carousel-viewport");
    const track = carousel.querySelector(".featured-carousel-track");
    const originalCards = track ? Array.from(track.querySelectorAll(".featured-card")) : [];
    const previousButton = carousel.querySelector(".featured-carousel-button.previous");
    const nextButton = carousel.querySelector(".featured-carousel-button.next");
    const dotsContainer = carousel.parentElement.querySelector(".featured-carousel-dots");

    if (!viewport || !track || !originalCards.length) return;

    let currentIndex = 0;
    let autoScrollTimer = null;
    let isPaused = false;
    let isResetting = false;
    let touchStartX = 0;
    let touchDeltaX = 0;
    const autoScrollDelay = 2500;
    const animationDuration = 700;

    function getStepSize() {
      if (originalCards.length < 2) return originalCards[0].getBoundingClientRect().width;
      return originalCards[1].getBoundingClientRect().left - originalCards[0].getBoundingClientRect().left;
    }

    function getDotIndex() { return ((currentIndex % originalCards.length) + originalCards.length) % originalCards.length; }

    function updateDots() {
      if (!dotsContainer) return;
      dotsContainer.querySelectorAll(".featured-carousel-dot").forEach(function (dot, index) {
        dot.classList.toggle("active", index === getDotIndex());
        dot.setAttribute("aria-current", index === getDotIndex() ? "true" : "false");
      });
    }

    function createDots() {
      if (!dotsContainer) return;
      dotsContainer.innerHTML = "";
      originalCards.forEach(function (_, index) {
        const dot = document.createElement("button");
        dot.type = "button";
        dot.className = "featured-carousel-dot";
        dot.setAttribute("aria-label", `Show featured project ${index + 1}`);
        dot.addEventListener("click", function () {
          stopAutoScroll();
          currentIndex = index;
          moveCarousel(true);
          startAutoScroll();
        });
        dotsContainer.appendChild(dot);
      });
      updateDots();
    }

    function moveCarousel(animate) {
      track.style.transition = animate ? `transform ${animationDuration}ms var(--ease-standard)` : "none";
      track.style.transform = `translate3d(-${currentIndex * getStepSize()}px, 0, 0)`;
      updateDots();
    }

    function createLoopCopies() {
      track.querySelectorAll(".featured-carousel-clone").forEach(function (clone) { clone.remove(); });
      originalCards.forEach(function (card) {
        const clone = card.cloneNode(true);
        clone.classList.add("featured-carousel-clone");
        clone.setAttribute("aria-hidden", "true");
        track.appendChild(clone);
      });
    }

    function next() {
      if (isResetting) return;
      currentIndex += 1;
      moveCarousel(true);
    }

    function previous() {
      if (isResetting) return;
      if (currentIndex > 0) {
        currentIndex -= 1;
        moveCarousel(true);
        return;
      }
      currentIndex = originalCards.length;
      moveCarousel(false);
      window.requestAnimationFrame(function () {
        currentIndex -= 1;
        moveCarousel(true);
      });
    }

    function handleLoopReset() {
      if (isResetting || currentIndex < originalCards.length) return;
      isResetting = true;
      track.style.transition = "none";
      currentIndex = 0;
      track.style.transform = "translate3d(0, 0, 0)";
      updateDots();
      window.requestAnimationFrame(function () {
        window.requestAnimationFrame(function () {
          track.style.transition = `transform ${animationDuration}ms var(--ease-standard)`;
          isResetting = false;
        });
      });
    }

    function startAutoScroll() {
      stopAutoScroll();
      if (window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
      autoScrollTimer = window.setInterval(function () { if (!isPaused) next(); }, autoScrollDelay);
    }

    function stopAutoScroll() {
      if (autoScrollTimer) window.clearInterval(autoScrollTimer);
      autoScrollTimer = null;
    }

    track.addEventListener("transitionend", function (event) {
      if (event.propertyName === "transform") handleLoopReset();
    });

    previousButton?.addEventListener("click", function () { stopAutoScroll(); previous(); startAutoScroll(); });
    nextButton?.addEventListener("click", function () { stopAutoScroll(); next(); startAutoScroll(); });
    carousel.addEventListener("mouseenter", function () { isPaused = true; });
    carousel.addEventListener("mouseleave", function () { isPaused = false; });
    carousel.addEventListener("focusin", function () { isPaused = true; });
    carousel.addEventListener("focusout", function () { isPaused = false; });
    viewport.addEventListener("touchstart", function (event) { touchStartX = event.changedTouches[0].clientX; touchDeltaX = 0; stopAutoScroll(); }, { passive: true });
    viewport.addEventListener("touchmove", function (event) { touchDeltaX = event.changedTouches[0].clientX - touchStartX; }, { passive: true });
    viewport.addEventListener("touchend", function () { if (Math.abs(touchDeltaX) > 45) touchDeltaX < 0 ? next() : previous(); startAutoScroll(); }, { passive: true });

    let resizeTimer;
    window.addEventListener("resize", function () {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(function () { moveCarousel(false); }, 100);
    }, { passive: true });

    createLoopCopies();
    createDots();
    moveCarousel(false);
    startAutoScroll();
  }
})();
