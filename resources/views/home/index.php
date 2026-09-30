<?php

/**
 * @var string $title
 * @var string $appUrl
 */
?>

<section class="home-hero">

    <div class="home-hero-content">

        <p class="home-eyebrow">
            Welcome to my portfolio
        </p>

        <h1>
            I'm a Software Developer.
        </h1>

        <p class="home-intro">
            I build modern, responsive and user-friendly
            web applications using clean and maintainable code.
        </p>

        <div class="home-actions">

            <a
                href="<?= htmlspecialchars($appUrl) ?>/portfolio"
                class="home-button">
                View My Work
            </a>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/profile"
                class="home-button secondary">
                About Me
            </a>

        </div>

    </div>

</section>

<section class="home-featured">

    <div class="section-heading">

        <p class="section-eyebrow">
            Selected Work
        </p>

        <h2>
            Featured Projects
        </h2>

        <p>
            A few projects from my portfolio.
        </p>

    </div>

    <div class="featured-grid">

        <article class="featured-card">

            <div class="featured-card-image">
                Project 01
            </div>

            <div class="featured-card-content">

                <h3>
                    Project One
                </h3>

                <p>
                    A short description of the project
                    will appear here.
                </p>

                <a href="<?= htmlspecialchars($appUrl) ?>/portfolio">
                    View Project
                </a>

            </div>

        </article>

        <article class="featured-card">

            <div class="featured-card-image">
                Project 02
            </div>

            <div class="featured-card-content">

                <h3>
                    Project Two
                </h3>

                <p>
                    A short description of the project
                    will appear here.
                </p>

                <a href="<?= htmlspecialchars($appUrl) ?>/portfolio">
                    View Project
                </a>

            </div>

        </article>

        <article class="featured-card">

            <div class="featured-card-image">
                Project 03
            </div>

            <div class="featured-card-content">

                <h3>
                    Project Three
                </h3>

                <p>
                    A short description of the project
                    will appear here.
                </p>

                <a href="<?= htmlspecialchars($appUrl) ?>/portfolio">
                    View Project
                </a>

            </div>

        </article>

    </div>

</section>