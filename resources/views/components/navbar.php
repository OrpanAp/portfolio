<?php

/**
 * @var string $appUrl
 */
?>

<nav class="site-navbar">

    <div class="navbar-container">

        <a
            href="<?= htmlspecialchars($appUrl) ?>/"
            class="navbar-logo">
            My Portfolio
        </a>

        <div class="navbar-links">

            <a href="<?= htmlspecialchars($appUrl) ?>/">
                Home
            </a>

            <a href="<?= htmlspecialchars($appUrl) ?>/profile">
                Profile
            </a>

            <a href="<?= htmlspecialchars($appUrl) ?>/portfolio">
                Portfolio
            </a>

            <a href="<?= htmlspecialchars($appUrl) ?>/cv">
                Download CV
            </a>

            <button
                type="button"
                id="theme-toggle"
                aria-label="Toggle dark and light theme">
                Theme
            </button>

        </div>

    </div>

</nav>