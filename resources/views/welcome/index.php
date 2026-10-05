<?php

/**
 * @var string $title
 * @var string $appUrl
 */
?>

<section class="welcome-page page-transition">

    <div class="welcome-orb welcome-orb-one" aria-hidden="true"></div>
    <div class="welcome-orb welcome-orb-two" aria-hidden="true"></div>
    <div class="welcome-grid" aria-hidden="true"></div>

    <div class="welcome-content" data-reveal>

        <p class="welcome-kicker"><span></span> Independent developer · Digital craft</p>

        <p class="welcome-greeting">Hello, I'm</p>

        <h1 class="welcome-title">
            Alex <span>Purification</span>
        </h1>

        <p class="welcome-subtitle">
            Welcome to a portfolio shaped by curiosity, clean engineering and memorable digital experiences.
        </p>

        <div class="welcome-actions">
            <a
                href="<?= htmlspecialchars($appUrl) ?>/home"
                class="welcome-button magnetic-button">
                <span>Let's Take a Ride</span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h13m-6-6 6 6-6 6" /></svg>
            </a>
        </div>

        <div class="welcome-scroll-hint" aria-hidden="true">
            <span class="welcome-scroll-line"></span>
            Scroll to explore
        </div>

    </div>

</section>
