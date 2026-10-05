(function () {
  const root = document.documentElement;
  const savedTheme = localStorage.getItem("theme");

  if (savedTheme === "dark" || savedTheme === "light") {
    root.classList.toggle("dark-theme", savedTheme === "dark");
    root.dataset.theme = savedTheme;
    return;
  }

  const prefersDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
  root.classList.toggle("dark-theme", prefersDark);
  root.dataset.theme = prefersDark ? "dark" : "light";
})();
