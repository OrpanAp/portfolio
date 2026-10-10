<?php

/**
 * @var string $appUrl
 * @var string $siteTitle
 * @var string $siteDescription
 * @var array<string, string> $socialLinks
 */

$footerTitle = trim((string) ($siteTitle ?? '')) ?: 'My Portfolio';
$footerDescription = trim((string) ($siteDescription ?? ''));
$footerSocials = $socialLinks ?? [];
$footerEsc = static fn (string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
?>
<footer class="site-footer">

    <div class="footer-container">

        <div class="footer-brand">
            <a href="<?= htmlspecialchars($appUrl) ?>/" class="footer-logo"><?= $footerEsc($footerTitle) ?></a>
            <?php if ($footerDescription !== ''): ?>
                <p><?= $footerEsc($footerDescription) ?></p>
            <?php endif; ?>
        </div>

        <div class="footer-nav">
            <p class="footer-heading">Explore</p>
            <a href="<?= htmlspecialchars($appUrl) ?>/">Home</a>
            <a href="<?= htmlspecialchars($appUrl) ?>/profile">Profile</a>
            <a href="<?= htmlspecialchars($appUrl) ?>/portfolio">Portfolio</a>
            <a href="<?= htmlspecialchars($appUrl) ?>/cv">CV</a>
        </div>

        <div class="footer-meta">
            <p class="footer-heading">Available for meaningful work</p>
            <p>Built with care, clarity and a preference for simple things done exceptionally well.</p>

            <?php if ($footerSocials !== []): ?>
                <p class="footer-heading footer-social-heading">Connect with me</p>
                <?php
                $socialLinks = $footerSocials;
                $socialVariant = 'footer';
                require __DIR__ . '/social-links.php';
                ?>
            <?php endif; ?>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= $footerEsc($footerTitle) ?>. All rights reserved.</p>
            <span>Designed &amp; built with intention.</span>
        </div>

    </div>

</footer>