document.addEventListener("DOMContentLoaded", function () {
  const themeToggle = document.getElementById("theme-toggle");

  if (!themeToggle) {
    return;
  }

  themeToggle.addEventListener("click", function () {
    const html = document.documentElement;

    const isDark = html.classList.toggle("dark-theme");

    localStorage.setItem("theme", isDark ? "dark" : "light");
  });
});
