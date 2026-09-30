<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var string|null $cvPath
 */
?>

<section class="cv-page">

    <div class="cv-header">

        <div>
            <h1>My CV</h1>

            <p>
                View my CV below or download a copy.
            </p>
        </div>

        <?php if ($cvPath !== null && $cvPath !== ''): ?>

            <div class="cv-actions">

                <a
                    href="<?= htmlspecialchars(
                                $appUrl . '/cv/download'
                            ) ?>"
                    class="cv-download-button">
                    Download CV
                </a>

                <a
                    href="<?= htmlspecialchars(
                                $appUrl . '/cv/preview'
                            ) ?>"
                    class="cv-open-button"
                    target="_blank"
                    rel="noopener noreferrer">
                    Open PDF in New Tab
                </a>

            </div>

        <?php endif; ?>

    </div>

    <?php if ($cvPath !== null && $cvPath !== ''): ?>

        <div class="cv-preview">

            <iframe
                src="<?= htmlspecialchars(
                            $appUrl . '/cv/preview'
                        ) ?>"
                title="CV Preview"
                loading="lazy"></iframe>

        </div>

    <?php else: ?>

        <div class="cv-empty">

            <h2>CV Not Available</h2>

            <p>
                The CV has not been uploaded yet.
            </p>

        </div>

    <?php endif; ?>

</section>