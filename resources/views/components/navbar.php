<?php

/**
 * @var string $appUrl
 */
?>

<nav class="site-navbar" aria-label="Primary navigation">
    <div class="navbar-container">
        <a
            href="<?= htmlspecialchars($appUrl) ?>/"
            class="navbar-logo"
            aria-label="My Portfolio home">
            <span class="navbar-logo-mark" aria-hidden="true">M</span>
            <span>My Portfolio</span>
        </a>

        <button
            type="button"
            class="navbar-menu-toggle"
            id="navbar-menu-toggle"
            aria-expanded="false"
            aria-controls="primary-navigation"
            aria-label="Open navigation menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="navbar-links" id="primary-navigation">
            <a href="<?= htmlspecialchars($appUrl) ?>/">Home</a>
            <a href="<?= htmlspecialchars($appUrl) ?>/profile">Profile</a>
            <a href="<?= htmlspecialchars($appUrl) ?>/portfolio">Portfolio</a>
            <a href="<?= htmlspecialchars($appUrl) ?>/cv">CV</a>

            <button
                type="button"
                id="theme-toggle"
                class="theme-toggle"
                aria-label="Switch to dark theme"
                title="Toggle theme">
                <span class="theme-toggle-icon" aria-hidden="true">☼</span>
                <span class="theme-toggle-label">Theme</span>
            </button>
        </div>
    </div>
</nav>