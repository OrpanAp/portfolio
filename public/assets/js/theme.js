(() => {
    const storageKey = "portfolio-theme";

    const getSystemTheme = () =>
        window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";

    const getTheme = () => localStorage.getItem(storageKey) || getSystemTheme();

    const applyTheme = (theme) => {
        const normalized = theme === "dark" ? "dark" : "light";
        document.documentElement.dataset.theme = normalized;

        const toggle = document.getElementById("theme-toggle");
        if (toggle) {
            const isDark = normalized === "dark";
            toggle.setAttribute("aria-pressed", String(isDark));
            toggle.setAttribute(
                "aria-label",
                isDark ? "Switch to light mode" : "Switch to dark mode"
            );
            toggle.textContent = isDark ? "☀" : "☾";
        }
    };

    applyTheme(getTheme());

    window.PortfolioTheme = {
        get: getTheme,
        set(theme) {
            localStorage.setItem(storageKey, theme);
            applyTheme(theme);
        },
        toggle() {
            this.set(getTheme() === "dark" ? "light" : "dark");
        }
    };
})();
