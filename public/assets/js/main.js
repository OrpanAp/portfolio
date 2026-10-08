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
    const adminLayout = document.querySelector(".admin-layout");
    const adminMenuToggle = document.querySelector("[data-admin-menu-toggle]");
    const adminMenuClose = document.querySelector("[data-admin-menu-close]");

    function syncThemeButton() {
      if (!themeToggle) return;
      const isDark = root.classList.contains("dark-theme");
      themeToggle.setAttribute("aria-pressed", String(isDark));
      themeToggle.setAttribute(
        "aria-label",
        isDark ? "Switch to light theme" : "Switch to dark theme",
      );
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
      if (media.addEventListener)
        media.addEventListener("change", onSystemThemeChange);
    }
    function closeMenu() {
      if (!header || !menuToggle) return;
<<<<<<< HEAD

      header.classList.remove("menu-open");
      body.classList.remove("menu-open");
      root.classList.remove("menu-open");

=======
      const wasOpen = header.classList.contains("menu-open");
      header.classList.remove("menu-open");
      body.classList.remove("menu-open");
      root.classList.remove("menu-lock");
>>>>>>> 9029ca2 (Responsive view fix)
      menuToggle.setAttribute("aria-expanded", "false");
      menuToggle.setAttribute("aria-label", "Open navigation menu");

      // Returning focus without allowing the browser to scroll prevents the
      // sticky header from jumping when a drawer item closes the menu.
      if (wasOpen && document.activeElement !== menuToggle) {
        menuToggle.focus({ preventScroll: true });
      }
    }
    function openMenu() {
      if (!header || !menuToggle) return;

      header.classList.add("menu-open");
      body.classList.add("menu-open");
<<<<<<< HEAD
      root.classList.add("menu-open");

=======
      root.classList.add("menu-lock");
>>>>>>> 9029ca2 (Responsive view fix)
      menuToggle.setAttribute("aria-expanded", "true");
      menuToggle.setAttribute("aria-label", "Close navigation menu");
      const firstLink = menu ? menu.querySelector("a") : null;
      if (firstLink) firstLink.focus({ preventScroll: true });
    }
    if (menuToggle) {
      menuToggle.addEventListener("click", function () {
        const open = menuToggle.getAttribute("aria-expanded") === "true";
        open ? closeMenu() : openMenu();
      });
    }
    if (menuClose) menuClose.addEventListener("click", closeMenu);
    if (menu)
      menu.querySelectorAll("a").forEach(function (link) {
        link.addEventListener("click", closeMenu);
      });

    const mobileMenuMedia = window.matchMedia ? window.matchMedia("(max-width: 820px)") : null;
    function syncMobileMenuViewport() {
      if (mobileMenuMedia && !mobileMenuMedia.matches) closeMenu();
    }
    window.addEventListener("resize", syncMobileMenuViewport, { passive: true });
    if (mobileMenuMedia) {
      if (mobileMenuMedia.addEventListener) mobileMenuMedia.addEventListener("change", syncMobileMenuViewport);
      else if (mobileMenuMedia.addListener) mobileMenuMedia.addListener(syncMobileMenuViewport);
    }

    function closeAdminMenu() {
      if (!adminLayout || !adminMenuToggle) return;
      adminLayout.classList.remove("admin-menu-open");
      body.classList.remove("admin-page-open");
      adminMenuToggle.setAttribute("aria-expanded", "false");
      adminMenuToggle.setAttribute("aria-label", "Open admin navigation");
    }

    function openAdminMenu() {
      if (!adminLayout || !adminMenuToggle) return;
      adminLayout.classList.add("admin-menu-open");
      body.classList.add("admin-page-open");
      adminMenuToggle.setAttribute("aria-expanded", "true");
      adminMenuToggle.setAttribute("aria-label", "Close admin navigation");
    }

    if (adminMenuToggle) {
      adminMenuToggle.addEventListener("click", function () {
        const open = adminMenuToggle.getAttribute("aria-expanded") === "true";
        open ? closeAdminMenu() : openAdminMenu();
      });
    }
    if (adminMenuClose) adminMenuClose.addEventListener("click", closeAdminMenu);
    if (adminLayout) {
      adminLayout.querySelectorAll(".admin-nav-link").forEach(function (link) {
        link.addEventListener("click", closeAdminMenu);
      });
    }

    document.addEventListener("keydown", function (event) {
<<<<<<< HEAD
      if (event.key === "Escape") closeMenu();
      if (
        event.key !== "Tab" ||
        !header ||
        !header.classList.contains("menu-open") ||
        !menu
      )
        return;
=======
      if (event.key === "Escape") {
        closeMenu();
        closeAdminMenu();
      }
      if (event.key !== "Tab" || !header || !header.classList.contains("menu-open") || !menu) return;
>>>>>>> 9029ca2 (Responsive view fix)
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
      if (backToTop)
        backToTop.classList.toggle(
          "is-visible",
          scrollTop > window.innerHeight * 0.55,
        );
      ticking = false;
    }

    window.addEventListener(
      "scroll",
      function () {
        if (!ticking) {
          window.requestAnimationFrame(updateScrollUI);
          ticking = true;
        }
      },
      { passive: true },
    );
    updateScrollUI();

    if (backToTop) {
      backToTop.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
    }

    const revealItems = document.querySelectorAll("[data-reveal]");
    if ("IntersectionObserver" in window && revealItems.length) {
      const observer = new IntersectionObserver(
        function (entries, instance) {
          entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.classList.add("is-visible");
            instance.unobserve(entry.target);
          });
        },
        { threshold: 0.08, rootMargin: "0px 0px -35px" },
      );
      revealItems.forEach(function (item, index) {
        item.style.transitionDelay = `${Math.min(index * 35, 180)}ms`;
        observer.observe(item);
      });
    } else {
      revealItems.forEach(function (item) {
        item.classList.add("is-visible");
      });
    }

    document.querySelectorAll("img").forEach(function (image) {
      if (image.complete) {
        image.classList.add("is-loaded");
      } else {
        image.addEventListener(
          "load",
          function () {
            image.classList.add("is-loaded");
          },
          { once: true },
        );
        image.addEventListener(
          "error",
          function () {
            image.classList.add("is-loaded");
          },
          { once: true },
        );
      }
    });

    document
      .querySelectorAll(
        ".portfolio-card-media, .featured-card-image, .profile-image-wrapper, .portfolio-detail-thumbnail",
      )
      .forEach(function (media) {
        const image = media.querySelector("img");
        if (!image) media.classList.add("is-loaded");
        else if (image.complete) media.classList.add("is-loaded");
        else
          image.addEventListener(
            "load",
            function () {
              media.classList.add("is-loaded");
            },
            { once: true },
          );
      });

    document
      .querySelectorAll(".portfolio-live-preview-frame")
      .forEach(function (frame) {
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

    document
      .querySelectorAll("[data-iframe-fullscreen]")
      .forEach(function (button) {
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
    if (
      window.matchMedia &&
      window.matchMedia("(prefers-reduced-motion: reduce)").matches
    )
      return;
    if (window.matchMedia && window.matchMedia("(pointer: coarse)").matches)
      return;
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
    const originalCards = track
      ? Array.from(track.querySelectorAll(".featured-card"))
      : [];
    const previousButton = carousel.querySelector(
      ".featured-carousel-button.previous",
    );
    const nextButton = carousel.querySelector(".featured-carousel-button.next");
    const dotsContainer = carousel.parentElement.querySelector(
      ".featured-carousel-dots",
    );

    if (!viewport || !track || !originalCards.length) return;

    let currentIndex = 0;
    let autoScrollTimer = null;
    let isPaused = false;
    let isResetting = false;
    let touchStartX = 0;
    let touchDeltaX = 0;
    let resizeFrame = 0;

<<<<<<< HEAD
    function getStepSize() {
      if (originalCards.length < 2)
        return originalCards[0].getBoundingClientRect().width;
      return (
        originalCards[1].getBoundingClientRect().left -
        originalCards[0].getBoundingClientRect().left
      );
    }

=======
    const autoScrollDelay = 3500;
    const animationDuration = 650;

    function getVisibleCount() {
      const width = viewport.getBoundingClientRect().width;

      // Three cards on desktop, two on tablet, one complete card on phones.
      if (width <= 767) return 1;
      if (width <= 1023) return 2;
      return 3;
    }

    function updateCardSizing() {
      const visibleCount = Math.min(
        getVisibleCount(),
        Math.max(originalCards.length, 1)
      );

      const styles = window.getComputedStyle(track);
      const gap = parseFloat(styles.columnGap || styles.gap || "16") || 16;
      const viewportWidth = Math.max(0, viewport.getBoundingClientRect().width);
      const available = Math.max(
        0,
        viewportWidth - (gap * (visibleCount - 1))
      );
      const cardWidth = available / visibleCount;

      track.style.setProperty("--featured-visible-count", String(visibleCount));
      track.style.setProperty("--featured-card-gap", `${gap}px`);
      track.style.setProperty("--featured-card-width", `${cardWidth}px`);

      return { cardWidth, gap, step: cardWidth + gap };
    }

    function getStepSize() {
      return updateCardSizing().step;
    }

>>>>>>> 9029ca2 (Responsive view fix)
    function getDotIndex() {
      return (
        ((currentIndex % originalCards.length) + originalCards.length) %
        originalCards.length
      );
    }

    function updateDots() {
      if (!dotsContainer) return;
<<<<<<< HEAD
      dotsContainer
        .querySelectorAll(".featured-carousel-dot")
        .forEach(function (dot, index) {
          dot.classList.toggle("active", index === getDotIndex());
          dot.setAttribute(
            "aria-current",
            index === getDotIndex() ? "true" : "false",
          );
        });
=======

      const activeIndex = getDotIndex();

      dotsContainer.querySelectorAll(".featured-carousel-dot").forEach(function (dot, index) {
        const active = index === activeIndex;
        dot.classList.toggle("active", active);
        dot.setAttribute("aria-current", active ? "true" : "false");
      });
>>>>>>> 9029ca2 (Responsive view fix)
    }

    function createDots() {
      if (!dotsContainer) return;

      dotsContainer.innerHTML = "";

      originalCards.forEach(function (_, index) {
        const dot = document.createElement("button");
        dot.type = "button";
        dot.className = "featured-carousel-dot";
        dot.setAttribute(
          "aria-label",
          `Show featured project ${index + 1}`
        );

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
<<<<<<< HEAD
      track.style.transition = animate
        ? `transform ${animationDuration}ms var(--ease-standard)`
        : "none";
      track.style.transform = `translate3d(-${currentIndex * getStepSize()}px, 0, 0)`;
=======
      const { step } = updateCardSizing();

      track.style.transition = animate
        ? `transform ${animationDuration}ms var(--ease-standard)`
        : "none";

      track.style.transform =
        `translate3d(-${currentIndex * step}px, 0, 0)`;

>>>>>>> 9029ca2 (Responsive view fix)
      updateDots();
    }

    function createLoopCopies() {
      track
        .querySelectorAll(".featured-carousel-clone")
        .forEach(function (clone) {
          clone.remove();
        });
<<<<<<< HEAD
=======

>>>>>>> 9029ca2 (Responsive view fix)
      originalCards.forEach(function (card) {
        const clone = card.cloneNode(true);
        clone.classList.add("featured-carousel-clone");
        clone.setAttribute("aria-hidden", "true");

        // Clones are visual loop buffers, not independently loaded content.
        // Remove their skeleton overlays so a loaded original never flashes
        // back to a placeholder when it enters the viewport.
        clone.querySelectorAll(".skeleton").forEach(function (skeleton) {
          skeleton.remove();
        });

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

      // Jump to the clone set without animation, then animate one card back.
      currentIndex = originalCards.length;
      moveCarousel(false);

      window.requestAnimationFrame(function () {
        window.requestAnimationFrame(function () {
          currentIndex -= 1;
          moveCarousel(true);
        });
      });
    }

    function handleLoopReset() {
      if (isResetting || currentIndex < originalCards.length) return;

      isResetting = true;
      track.style.transition = "none";
      currentIndex = 0;

      const { step } = updateCardSizing();
      track.style.transform = `translate3d(0, 0, 0)`;
      updateDots();

      window.requestAnimationFrame(function () {
        window.requestAnimationFrame(function () {
          // Re-read the viewport after the reset so orientation/resizing
          // cannot leave the next animation using stale card geometry.
          updateCardSizing();
          track.style.transition =
            `transform ${animationDuration}ms var(--ease-standard)`;
          isResetting = false;
        });
      });
    }

    function startAutoScroll() {
      stopAutoScroll();
<<<<<<< HEAD
      if (
        window.matchMedia &&
        window.matchMedia("(prefers-reduced-motion: reduce)").matches
      )
        return;
      autoScrollTimer = window.setInterval(function () {
        if (!isPaused) next();
=======

      if (
        window.matchMedia &&
        window.matchMedia("(prefers-reduced-motion: reduce)").matches
      ) {
        return;
      }

      autoScrollTimer = window.setInterval(function () {
        if (!isPaused && !document.hidden) next();
>>>>>>> 9029ca2 (Responsive view fix)
      }, autoScrollDelay);
    }

    function stopAutoScroll() {
      if (autoScrollTimer) {
        window.clearInterval(autoScrollTimer);
      }
      autoScrollTimer = null;
    }

    track.addEventListener("transitionend", function (event) {
      if (event.propertyName === "transform") {
        handleLoopReset();
      }
    });

    previousButton?.addEventListener("click", function () {
      stopAutoScroll();
      previous();
      startAutoScroll();
    });
<<<<<<< HEAD
    nextButton?.addEventListener("click", function () {
      stopAutoScroll();
      next();
      startAutoScroll();
    });
    carousel.addEventListener("mouseenter", function () {
      isPaused = true;
    });
    carousel.addEventListener("mouseleave", function () {
      isPaused = false;
    });
    carousel.addEventListener("focusin", function () {
      isPaused = true;
    });
    carousel.addEventListener("focusout", function () {
      isPaused = false;
    });
    viewport.addEventListener(
      "touchstart",
      function (event) {
        touchStartX = event.changedTouches[0].clientX;
        touchDeltaX = 0;
        stopAutoScroll();
      },
      { passive: true },
    );
    viewport.addEventListener(
      "touchmove",
      function (event) {
        touchDeltaX = event.changedTouches[0].clientX - touchStartX;
      },
      { passive: true },
    );
    viewport.addEventListener(
      "touchend",
      function () {
        if (Math.abs(touchDeltaX) > 45) touchDeltaX < 0 ? next() : previous();
        startAutoScroll();
      },
      { passive: true },
    );

    let resizeTimer;
    window.addEventListener(
      "resize",
      function () {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(function () {
          moveCarousel(false);
        }, 100);
      },
      { passive: true },
    );
=======

    nextButton?.addEventListener("click", function () {
      stopAutoScroll();
      next();
      startAutoScroll();
    });
>>>>>>> 9029ca2 (Responsive view fix)

    carousel.addEventListener("mouseenter", function () {
      isPaused = true;
    });

    carousel.addEventListener("mouseleave", function () {
      isPaused = false;
    });

    carousel.addEventListener("focusin", function () {
      isPaused = true;
    });

    carousel.addEventListener("focusout", function () {
      isPaused = false;
    });

    viewport.addEventListener(
      "touchstart",
      function (event) {
        touchStartX = event.changedTouches[0].clientX;
        touchDeltaX = 0;
        stopAutoScroll();
      },
      { passive: true }
    );

    viewport.addEventListener(
      "touchmove",
      function (event) {
        touchDeltaX = event.changedTouches[0].clientX - touchStartX;
      },
      { passive: true }
    );

    viewport.addEventListener(
      "touchend",
      function () {
        if (Math.abs(touchDeltaX) > 45) {
          if (touchDeltaX < 0) {
            next();
          } else {
            previous();
          }
        }

        touchStartX = 0;
        touchDeltaX = 0;
        startAutoScroll();
      },
      { passive: true }
    );

    function handleResize() {
      window.cancelAnimationFrame(resizeFrame);
      resizeFrame = window.requestAnimationFrame(function () {
        // Preserve the active project while changing the number of
        // visible cards at a breakpoint.
        const activeDot = getDotIndex();
        currentIndex = activeDot;
        moveCarousel(false);
      });
    }

    window.addEventListener("resize", handleResize, { passive: true });
    window.addEventListener("orientationchange", handleResize, { passive: true });

    if ("ResizeObserver" in window) {
      const observer = new ResizeObserver(function () {
        handleResize();
      });
      observer.observe(viewport);
    }

    document.addEventListener("visibilitychange", function () {
      if (document.hidden) {
        stopAutoScroll();
      } else {
        startAutoScroll();
      }
    });

    // Measure before revealing the track so the first painted carousel frame
    // never shows the desktop fallback geometry on a smaller viewport.
    createLoopCopies();
    createDots();
    updateCardSizing();
    moveCarousel(false);
    carousel.classList.add("is-ready");
    startAutoScroll();
  }
})();
