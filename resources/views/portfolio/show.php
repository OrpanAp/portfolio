<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array|null $portfolio
 */
?>

<section class="portfolio-detail">

    <?php if ($portfolio === null): ?>

        <div class="portfolio-detail-not-found">

            <h1>
                Project Not Found
            </h1>

            <p>
                The project you are looking for does not exist
                or is no longer published.
            </p>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/portfolio"
                class="portfolio-view-button">
                Back to Portfolio
            </a>

        </div>

    <?php else: ?>

        <a
            href="<?= htmlspecialchars($appUrl) ?>/portfolio"
            class="portfolio-back">
            ← Back to Portfolio
        </a>

        <header class="portfolio-detail-header">

            <?php if (!empty($portfolio['is_featured'])): ?>

                <span class="portfolio-featured-badge">
                    Featured
                </span>

            <?php endif; ?>

            <p class="portfolio-category">
                <?= htmlspecialchars($portfolio['category_name']) ?>
            </p>

            <h1>
                <?= htmlspecialchars($portfolio['title']) ?>
            </h1>

            <p>
                <?= htmlspecialchars($portfolio['description']) ?>
            </p>

        </header>

        <?php if (!empty($portfolio['thumbnail'])): ?>

            <div class="portfolio-detail-thumbnail">

                <img
                    src="<?= htmlspecialchars($portfolio['thumbnail']) ?>"
                    alt="<?= htmlspecialchars($portfolio['title']) ?>">

            </div>

        <?php endif; ?>

        <div class="portfolio-detail-info">

            <div>

                <strong>
                    Project Type
                </strong>

                <span>
                    <?= htmlspecialchars($portfolio['project_type']) ?>
                </span>

            </div>

            <?php if (!empty($portfolio['technologies'])): ?>

                <div>

                    <strong>
                        Technologies
                    </strong>

                    <div class="portfolio-technologies">

                        <?php foreach (
                            $portfolio['technologies']
                            as $technology
                        ): ?>

                            <span class="technology-tag">
                                <?= htmlspecialchars($technology['name']) ?>
                            </span>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php endif; ?>

        </div>

        <?php if (!empty($previewUrl)): ?>

            <section class="portfolio-live-preview">

                <div class="portfolio-live-preview-header">

                    <h2>
                        Live Preview
                    </h2>

                    <a
                        href="<?= htmlspecialchars($previewUrl) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="portfolio-preview-open">
                        Open in New Tab
                    </a>

                </div>

                <div class="portfolio-live-preview-frame">

                    <iframe
                        src="<?= htmlspecialchars($previewUrl) ?>"
                        title="<?= htmlspecialchars($portfolio['title']) ?> Live Preview"
                        loading="lazy"
                        sandbox="allow-scripts allow-same-origin allow-forms allow-popups">
                    </iframe>

                </div>

            </section>

        <?php endif; ?>

    <?php endif; ?>

</section>