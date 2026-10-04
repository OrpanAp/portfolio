<?php
/** @var string $appUrl */
?>
<nav class="site-navbar navbar navbar-expand-lg">
    <div class="container py-2">
        <a
            href="<?= htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') ?>/home"
            class="navbar-brand d-flex align-items-center gap-2 fw-bold">
            <span class="navbar-brand-mark" aria-hidden="true">P</span>
            <span>My Portfolio</span>
        </a>

        <button
            class="navbar-toggler border-0 shadow-none"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#site-navigation"
            aria-controls="site-navigation"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="site-navigation">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <a href="<?= htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') ?>/home" class="nav-link site-nav-link">Home</a>
                <a href="<?= htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') ?>/profile" class="nav-link site-nav-link">Profile</a>
                <a href="<?= htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') ?>/portfolio" class="nav-link site-nav-link">Portfolio</a>
                <a href="<?= htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') ?>/cv" class="nav-link site-nav-link">CV</a>

                <button
                    type="button"
                    id="theme-toggle"
                    class="theme-toggle ms-lg-2 mt-2 mt-lg-0"
                    aria-pressed="false"
                    aria-label="Switch to dark mode">
                    ☾
                </button>
            </div>
        </div>
    </div>
</nav>
