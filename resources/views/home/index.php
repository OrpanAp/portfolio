<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array|null $profile
 * @var string $siteTitle
 * @var string $siteDescription
 * @var array $featuredPortfolios
 */

$headline = trim((string) ($profile['headline'] ?? ''));
$intro = trim((string) ($profile['bio'] ?? ''));

if ($headline === '') {
    $headline = 'Software Developer';
}

if ($intro === '') {
    $intro = $siteDescription !== ''
        ? $siteDescription
        : 'I build modern, responsive and user-friendly web applications using clean and maintainable code.';
}

$escape = static function (mixed $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
?>

<section class="home-page page-transition">

    <section class="home-hero">
        <div class="home-hero-glow home-hero-glow-one" aria-hidden="true"></div>
        <div class="home-hero-glow home-hero-glow-two" aria-hidden="true"></div>

        <div class="home-hero-content" data-reveal>
            <p class="home-eyebrow"><span class="eyebrow-dot"></span> Welcome to my portfolio</p>

            <h1>
                <?= $escape($headline) ?>
                <span class="home-title-accent">with a sharper edge.</span>
            </h1>

            <p class="home-intro">
                <?= $escape($intro) ?>
            </p>

            <div class="home-actions">
                <a href="<?= $escape($appUrl) ?>/portfolio" class="home-button magnetic-button">
                    <span>View My Work</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h13m-6-6 6 6-6 6" /></svg>
                </a>
                <a href="<?= $escape($appUrl) ?>/profile" class="home-button secondary">
                    About Me
                </a>
            </div>

            <div class="home-trust-row">
                <span>Clean architecture</span>
                <span>Responsive by default</span>
                <span>Details matter</span>
            </div>
        </div>
    </section>

    <section class="home-featured" aria-labelledby="featured-heading">
        <div class="section-heading" data-reveal>
            <div>
                <p class="section-eyebrow">Selected work</p>
                <h2 id="featured-heading">Featured Projects</h2>
            </div>
            <p>A few projects from my portfolio.</p>
        </div>

        <?php if (!empty($featuredPortfolios)): ?>
            <div class="featured-carousel" data-reveal>
                <button type="button" class="featured-carousel-button previous" aria-label="Previous featured project">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6" /></svg>
                </button>

                <div class="featured-carousel-viewport">
                    <div class="featured-carousel-track">
                        <?php foreach ($featuredPortfolios as $portfolio): ?>
                            <article class="featured-card">
                                <div class="featured-card-image">
                                    <div class="skeleton skeleton-media" aria-hidden="true"></div>
                                    <?php if (!empty($portfolio['thumbnail'])): ?>
                                        <img
                                            src="<?= $escape($appUrl . '/' . ltrim((string) $portfolio['thumbnail'], '/')) ?>"
                                            alt="<?= $escape($portfolio['title']) ?>"
                                            loading="lazy"
                                            decoding="async">
                                    <?php else: ?>
                                        <span class="featured-placeholder-text"><?= $escape($portfolio['title']) ?></span>
                                    <?php endif; ?>
                                    <span class="featured-image-shade" aria-hidden="true"></span>
                                </div>

                                <div class="featured-card-content">
                                    <span class="card-index">Selected project</span>
                                    <h3><?= $escape($portfolio['title']) ?></h3>
                                    <p><?= $escape($portfolio['description']) ?></p>
                                    <a href="<?= $escape($appUrl . '/portfolio/' . ltrim((string) $portfolio['slug'], '/')) ?>" class="text-link">
                                        <span>View Project</span>
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h13m-6-6 6 6-6 6" /></svg>
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="button" class="featured-carousel-button next" aria-label="Next featured project">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg>
                </button>
            </div>

            <div class="featured-carousel-dots" aria-label="Featured project navigation"></div>
        <?php else: ?>
            <p class="featured-empty" data-reveal>
                No featured projects are available yet.
            </p>
        <?php endif; ?>
    </section>

</section>
