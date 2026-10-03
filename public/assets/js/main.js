document.addEventListener("DOMContentLoaded", function () {
  const themeToggle = document.getElementById("theme-toggle");

  if (themeToggle) {
    themeToggle.addEventListener("click", function () {
      const html = document.documentElement;

      const isDark = html.classList.toggle("dark-theme");

      localStorage.setItem("theme", isDark ? "dark" : "light");
    });
  }

  const carousel = document.querySelector(".featured-carousel");

  if (!carousel) {
    return;
  }

  const viewport = carousel.querySelector(".featured-carousel-viewport");

  const track = carousel.querySelector(".featured-carousel-track");

  const originalCards = Array.from(track.querySelectorAll(".featured-card"));

  const previousButton = carousel.querySelector(
    ".featured-carousel-button.previous",
  );

  const nextButton = carousel.querySelector(".featured-carousel-button.next");

  const dotsContainer = document.querySelector(".featured-carousel-dots");

  if (!viewport || !track || originalCards.length === 0) {
    return;
  }

  let currentIndex = 0;

  let autoScrollTimer = null;

  let isPaused = false;

  let isResetting = false;

  const autoScrollDelay = 2500;

  const animationDuration = 700;

  function getVisibleCards() {
    if (window.innerWidth <= 768) {
      return 1;
    }

    return 3;
  }

  function getStepSize() {
    if (originalCards.length < 2) {
      return originalCards[0].getBoundingClientRect().width;
    }

    const first = originalCards[0].getBoundingClientRect();

    const second = originalCards[1].getBoundingClientRect();

    return second.left - first.left;
  }

  function getDotIndex() {
    return currentIndex % originalCards.length;
  }

  function updateDots() {
    if (!dotsContainer) {
      return;
    }

    const dots = dotsContainer.querySelectorAll(".featured-carousel-dot");

    const activeIndex = getDotIndex();

    dots.forEach(function (dot, index) {
      dot.classList.toggle("active", index === activeIndex);
    });
  }

  function createDots() {
    if (!dotsContainer) {
      return;
    }

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

  function moveCarousel(animate = true) {
    const stepSize = getStepSize();

    track.style.transition = animate
      ? `transform ${animationDuration}ms ease`
      : "none";

    track.style.transform = `translateX(-${currentIndex * stepSize}px)`;

    updateDots();
  }

  function createLoopCopies() {
    track
      .querySelectorAll(".featured-carousel-clone")
      .forEach(function (clone) {
        clone.remove();
      });

    originalCards.forEach(function (card) {
      const clone = card.cloneNode(true);

      clone.classList.add("featured-carousel-clone");

      track.appendChild(clone);
    });
  }

  function next() {
    if (isResetting) {
      return;
    }

    currentIndex++;

    moveCarousel(true);
  }

  function previous() {
    if (isResetting) {
      return;
    }

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
    if (isResetting) {
      return;
    }

    if (currentIndex < originalCards.length) {
      return;
    }

    isResetting = true;

    track.style.transition = "none";

    currentIndex = 0;

    track.style.transform = "translateX(0)";

    updateDots();

    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        track.style.transition = `transform ${animationDuration}ms ease`;

        isResetting = false;
      });
    });
  }

  track.addEventListener("transitionend", function (event) {
    if (event.propertyName !== "transform") {
      return;
    }

    handleLoopReset();
  });

  function startAutoScroll() {
    stopAutoScroll();

    autoScrollTimer = setInterval(function () {
      if (!isPaused) {
        next();
      }
    }, autoScrollDelay);
  }

  function stopAutoScroll() {
    if (autoScrollTimer) {
      clearInterval(autoScrollTimer);

      autoScrollTimer = null;
    }
  }

  function pauseAutoScroll() {
    isPaused = true;
  }

  function resumeAutoScroll() {
    isPaused = false;
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

  carousel.addEventListener("mouseenter", pauseAutoScroll);

  carousel.addEventListener("mouseleave", resumeAutoScroll);

  carousel.addEventListener("focusin", pauseAutoScroll);

  carousel.addEventListener("focusout", resumeAutoScroll);

  window.addEventListener("resize", function () {
    track.style.transition = "none";

    moveCarousel(false);

    requestAnimationFrame(function () {
      track.style.transition = `transform ${animationDuration}ms ease`;
    });
  });

  createLoopCopies();

  createDots();

  moveCarousel(false);

  startAutoScroll();
});
