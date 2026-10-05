<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var string|null $cvPath
 */
?>

<section class="cv-page page-transition">

    <header class="cv-header" data-reveal>
        <div>
            <p class="cv-eyebrow">Curriculum vitae</p>
            <h1>My CV</h1>
            <p>View my CV below or download a copy.</p>
        </div>

        <?php if ($cvPath !== null && $cvPath !== ''): ?>
            <div class="cv-actions">
                <a href="<?= htmlspecialchars($appUrl . '/cv/download') ?>" class="cv-download-button magnetic-button">
                    <span>Download CV</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12m0 0 5-5m-5 5-5-5M5 21h14" /></svg>
                </a>
                <a href="<?= htmlspecialchars($appUrl . '/cv/preview') ?>" class="cv-open-button" target="_blank" rel="noopener noreferrer">
                    Open PDF in New Tab
                </a>
            </div>
        <?php endif; ?>
    </header>

    <?php if ($cvPath !== null && $cvPath !== ''): ?>
        <div class="cv-preview" data-reveal>
            <div class="preview-chrome" aria-hidden="true">
                <span></span><span></span><span></span>
                <small>CV preview</small>
            </div>
            <iframe src="<?= htmlspecialchars($appUrl . '/cv/preview') ?>" title="CV Preview" loading="lazy"></iframe>
        </div>
    <?php else: ?>
        <div class="cv-empty empty-state" data-reveal>
            <span class="empty-state-icon" aria-hidden="true">CV</span>
            <h2>CV Not Available</h2>
            <p>The CV has not been uploaded yet.</p>
        </div>
    <?php endif; ?>

</section>
