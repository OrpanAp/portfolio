<?php

/**
 * @var string $title
 * @var string $content
 * @var string $appUrl
 * @var string $siteTitle
 * @var string $siteDescription
 */

$siteTitle = trim((string) ($siteTitle ?? '')) ?: 'My Portfolio';
$siteDescription = trim((string) ($siteDescription ?? ''));
$pageTitle = trim((string) $title);

$fullTitle = ($pageTitle === '' || $pageTitle === $siteTitle)
    ? $siteTitle
    : $pageTitle . ' - ' . $siteTitle;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#0b0d12">

    <title><?= htmlspecialchars($fullTitle) ?></title>

    <?php if ($siteDescription !== ''): ?>
        <meta name="description" content="<?= htmlspecialchars($siteDescription, ENT_QUOTES, 'UTF-8') ?>">
        <meta property="og:description" content="<?= htmlspecialchars($siteDescription, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <meta property="og:title" content="<?= htmlspecialchars($fullTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8') ?>">

    <!-- Runs before first paint so the persisted/system theme does not flash. -->
    <script src="<?= htmlspecialchars($appUrl) ?>/assets/js/theme.js"></script>
    <script>document.documentElement.classList.add("js");</script>

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars($appUrl) ?>/assets/css/main.css">

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars($appUrl) ?>/assets/css/responsive.css">

</head>

<body>

    <a class="skip-link" href="#main-content">Skip to content</a>

    <div class="scroll-progress" aria-hidden="true">
        <span class="scroll-progress-bar"></span>
    </div>

    <?php require __DIR__ . '/../components/navbar.php'; ?>

    <main id="main-content" tabindex="-1">

        <?= $content ?>

    </main>

    <?php require __DIR__ . '/../components/footer.php'; ?>

    <button
        type="button"
        class="back-to-top"
        data-back-to-top
        aria-label="Back to top">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 19V5m0 0-6 6m6-6 6 6" />
        </svg>
    </button>

    <script src="<?= htmlspecialchars($appUrl) ?>/assets/js/main.js"></script>
</body>

</html>
