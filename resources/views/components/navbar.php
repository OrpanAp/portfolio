<?php

/**
 * @var string $appUrl
 */
?>

<header class="site-header">

    <nav class="site-navbar" aria-label="Primary navigation">

        <div class="navbar-container">

            <a
                href="<?= htmlspecialchars($appUrl) ?>/"
                class="navbar-logo"
                aria-label="My Portfolio home">
                <span class="navbar-logo-mark" aria-hidden="true">M</span>
                <span class="navbar-logo-text">My Portfolio</span>
            </a>

            <button
                type="button"
                class="navbar-menu-toggle"
                data-menu-toggle
                aria-label="Open navigation menu"
                aria-controls="primary-navigation"
                aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="navbar-backdrop" data-menu-close></div>

            <div class="navbar-links" id="primary-navigation" data-menu>

                <a href="<?= htmlspecialchars($appUrl) ?>/">Home</a>

                <a href="<?= htmlspecialchars($appUrl) ?>/profile">Profile</a>

                <a href="<?= htmlspecialchars($appUrl) ?>/portfolio">Portfolio</a>

                <a href="<?= htmlspecialchars($appUrl) ?>/cv">Download CV</a>

                <button
                    type="button"
                    id="theme-toggle"
                    class="theme-toggle"
                    aria-label="Toggle dark and light theme"
                    aria-pressed="false">
                    <span class="theme-toggle-icon theme-icon-sun" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32 1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41M17.25 6.34l1.41-1.41" />
                        </svg>
                    </span>
                    <span class="theme-toggle-icon theme-icon-moon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M20.5 14.2A8.5 8.5 0 0 1 9.8 3.5 8.6 8.6 0 1 0 20.5 14.2Z" />
                        </svg>
                    </span>
                    <span class="theme-toggle-label">Theme</span>
                </button>

            </div>

        </div>

    </nav>

</header>
