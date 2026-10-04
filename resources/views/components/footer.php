<?php
/** @var string $appUrl */
?>
<footer class="site-footer py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <p class="mb-0">&copy; <?= date('Y') ?> My Portfolio. All rights reserved.</p>
        <a
            href="<?= htmlspecialchars($appUrl, ENT_QUOTES, 'UTF-8') ?>/portfolio"
            class="text-decoration-none fw-semibold">
            Explore projects →
        </a>
    </div>
</footer>
