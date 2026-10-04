document.addEventListener("DOMContentLoaded", function () {
  const html = document.documentElement;
  const themeToggle = document.getElementById("theme-toggle");
  const navbar = document.querySelector(".site-navbar");
  const menuToggle = document.getElementById("navbar-menu-toggle");
  const navigation = document.getElementById("primary-navigation");

  function updateThemeControl() {
    if (!themeToggle) return;

    const isDark = html.classList.contains("dark-theme");
    themeToggle.setAttribute(
      "aria-label",
      isDark ? "Switch to light theme" : "Switch to dark theme"
    );

    const icon = themeToggle.querySelector(".theme-toggle-icon");
    if (icon) icon.textContent = isDark ? "☾" : "☼";
  }

  if (themeToggle) {
    themeToggle.addEventListener("click", function () {
      const isDark = html.classList.toggle("dark-theme");
      localStorage.setItem("theme", isDark ? "dark" : "light");
      updateThemeControl();
    });
    updateThemeControl();
  }

  function closeMenu() {
    if (!navbar || !menuToggle) return;
    navbar.classList.remove("menu-open");
    menuToggle.setAttribute("aria-expanded", "false");
    menuToggle.setAttribute("aria-label", "Open navigation menu");
  }

  if (menuToggle && navbar && navigation) {
    menuToggle.addEventListener("click", function () {
      const isOpen = navbar.classList.toggle("menu-open");
      menuToggle.setAttribute("aria-expanded", String(isOpen));
      menuToggle.setAttribute(
        "aria-label",
        isOpen ? "Close navigation menu" : "Open navigation menu"
      );
    });

    navigation.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", closeMenu);
    });

    document.addEventListener("click", function (event) {
      if (!navbar.contains(event.target)) closeMenu();
    });

    window.addEventListener("resize", function () {
      if (window.innerWidth > 768) closeMenu();
    });
  }

  const carousel = document.querySelector(".featured-carousel");
  if (!carousel) return;

  const viewport = carousel.querySelector(".featured-carousel-viewport");
  const track = carousel.querySelector(".featured-carousel-track");
  const originalCards = Array.from(track.querySelectorAll(".featured-card"));
  const previousButton = carousel.querySelector(".featured-carousel-button.previous");
  const nextButton = carousel.querySelector(".featured-carousel-button.next");
  const dotsContainer = document.querySelector(".featured-carousel-dots");

  if (!viewport || !track || originalCards.length === 0) return;

  let currentIndex = 0;
  let autoScrollTimer = null;
  let isPaused = false;
  let isResetting = false;
  const autoScrollDelay = 2500;
  const animationDuration = 700;

  function getStepSize() {
    if (originalCards.length < 2) return originalCards[0].getBoundingClientRect().width;
    const first = originalCards[0].getBoundingClientRect();
    const second = originalCards[1].getBoundingClientRect();
    return second.left - first.left;
  }

  function getDotIndex() {
    return currentIndex % originalCards.length;
  }

  function updateDots() {
    if (!dotsContainer) return;
    dotsContainer.querySelectorAll(".featured-carousel-dot").forEach(function (dot, index) {
      dot.classList.toggle("active", index === getDotIndex());
    });
  }

  function createDots() {
    if (!dotsContainer) return;
    dotsContainer.innerHTML = "";
    originalCards.forEach(function (_, index) {
      const dot = document.createElement("button");
      dot.type = "button";
      dot.className = "featured-carousel-dot";
      dot.setAttribute("aria-label", "Show featured project " + (index + 1));
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
    const stepSize = getStepSize();
    track.style.transition = animate
      ? "transform " + animationDuration + "ms ease"
      : "none";
    track.style.transform = "translateX(-" + currentIndex * stepSize + "px)";
    updateDots();
  }

  function createLoopCopies() {
    track.querySelectorAll(".featured-carousel-clone").forEach(function (clone) {
      clone.remove();
    });

    originalCards.forEach(function (card) {
      const clone = card.cloneNode(true);
      clone.classList.add("featured-carousel-clone");
      track.appendChild(clone);
    });
  }

  function next() {
    if (isResetting) return;
    currentIndex++;
    moveCarousel(true);
  }

  function previous() {
    if (isResetting) return;

    if (currentIndex > 0) {
      currentIndex--;
      moveCarousel(true);
      return;
    }

    currentIndex = originalCards.length;
    moveCarousel(false);

    requestAnimationFrame(function () {
      currentIndex--;
      moveCarousel(true);
    });
  }

  function handleLoopReset() {
    if (isResetting || currentIndex < originalCards.length) return;

    isResetting = true;
    track.style.transition = "none";
    currentIndex = 0;
    track.style.transform = "translateX(0)";
    updateDots();

    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        track.style.transition = "transform " + animationDuration + "ms ease";
        isResetting = false;
      });
    });
  }

  track.addEventListener("transitionend", function (event) {
    if (event.propertyName === "transform") handleLoopReset();
  });

  function startAutoScroll() {
    stopAutoScroll();
    autoScrollTimer = setInterval(function () {
      if (!isPaused) next();
    }, autoScrollDelay);
  }

  function stopAutoScroll() {
    if (autoScrollTimer) {
      clearInterval(autoScrollTimer);
      autoScrollTimer = null;
    }
  }

  if (previousButton) {
    previousButton.addEventListener("click", function () {
      stopAutoScroll();
      previous();
      startAutoScroll();
    });
  }

  if (nextButton) {
    nextButton.addEventListener("click", function () {
      stopAutoScroll();
      next();
      startAutoScroll();
    });
  }

  carousel.addEventListener("mouseenter", function () { isPaused = true; });
  carousel.addEventListener("mouseleave", function () { isPaused = false; });
  carousel.addEventListener("focusin", function () { isPaused = true; });
  carousel.addEventListener("focusout", function () { isPaused = false; });

  window.addEventListener("resize", function () {
    track.style.transition = "none";
    moveCarousel(false);
    requestAnimationFrame(function () {
      track.style.transition = "transform " + animationDuration + "ms ease";
    });
  });

  createLoopCopies();
  createDots();
  moveCarousel(false);
  startAutoScroll();
});