<?php

/**
 * @var string $title
 * @var string $appUrl
 */
?>

<div class="admin-page-header">
    <div>
        <h2>Portfolio Not Found</h2>

        <p>
            The portfolio project you are looking for does not exist.
        </p>
    </div>

    <a
        href="<?= htmlspecialchars($appUrl) ?>/admin/portfolio"
        class="admin-button">
        Back to Portfolio
    </a>
</div>

<div class="admin-empty-state">

    <h3>Project Not Found</h3>

    <p>
        The requested portfolio project could not be found.
    </p>

    <a
        href="<?= htmlspecialchars($appUrl) ?>/admin/portfolio"
        class="admin-card-button">
        Return to Portfolio
    </a>

</div>