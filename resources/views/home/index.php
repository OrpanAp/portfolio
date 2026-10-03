<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array|null $profile
 * @var string $siteTitle
 * @var string $siteDescription
 * @var array $featuredPortfolios
 */

$headline =
    trim(
        (string) (
            $profile['headline'] ?? ''
        )
    );

$intro =
    trim(
        (string) (
            $profile['bio'] ?? ''
        )
    );

if ($headline === '') {
    $headline = 'Software Developer';
}

if ($intro === '') {
    $intro = $siteDescription !== ''
        ? $siteDescription
        : 'I build modern, responsive and user-friendly web applications using clean and maintainable code.';
}

$escape = static function (
    mixed $value
): string {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
};
?>

<section class="home-hero">

    <div class="home-hero-content">

        <p class="home-eyebrow">
            Welcome to my portfolio
        </p>

        <h1>
            <?= $escape($headline) ?>
        </h1>

        <p class="home-intro">
            <?= $escape($intro) ?>
        </p>

        <div class="home-actions">

            <a
                href="<?= $escape($appUrl) ?>/portfolio"
                class="home-button">
                View My Work
            </a>

            <a
                href="<?= $escape($appUrl) ?>/profile"
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

    <?php if (
        !empty($featuredPortfolios)
    ): ?>

        <div class="featured-carousel">

            <button
                type="button"
                class="featured-carousel-button previous"
                aria-label="Previous featured project">
                &#10094;
            </button>

            <div class="featured-carousel-viewport">

                <div class="featured-carousel-track">

                    <?php foreach (
                        $featuredPortfolios
                        as $portfolio
                    ): ?>

                        <article class="featured-card">

                            <div class="featured-card-image">

                                <?php if (
                                    !empty($portfolio['thumbnail'])
                                ): ?>

                                    <img
                                        src="<?= $escape(
                                                    $appUrl . '/' .
                                                        ltrim(
                                                            (string) $portfolio['thumbnail'],
                                                            '/'
                                                        )
                                                ) ?>"
                                        alt="<?= $escape(
                                                    $portfolio['title']
                                                ) ?>">

                                <?php else: ?>

                                    <?= $escape(
                                        $portfolio['title']
                                    ) ?>

                                <?php endif; ?>

                            </div>

                            <div class="featured-card-content">

                                <h3>
                                    <?= $escape(
                                        $portfolio['title']
                                    ) ?>
                                </h3>

                                <p>
                                    <?= $escape(
                                        $portfolio['description']
                                    ) ?>
                                </p>

                                <a
                                    href="<?= $escape(
                                                $appUrl .
                                                    '/portfolio/' .
                                                    ltrim(
                                                        (string) $portfolio['slug'],
                                                        '/'
                                                    )
                                            ) ?>">
                                    View Project
                                </a>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            </div>

            <button
                type="button"
                class="featured-carousel-button next"
                aria-label="Next featured project">
                &#10095;
            </button>

        </div>

        <div
            class="featured-carousel-dots"
            aria-label="Featured project navigation">
        </div>

    <?php else: ?>

        <p class="featured-empty">
            No featured projects are available yet.
        </p>

    <?php endif; ?>

</section>