<?php

/**
 * @var string $title
 * @var string $appUrl
 */
?>

<div class="admin-page-header">
    <div>
        <h2>Technology Not Found</h2>

        <p>
            The technology you are looking for does not exist.
        </p>
    </div>
</div>

<div class="admin-empty-state">

    <h3>Technology Not Found</h3>

    <p>
        This technology may have been deleted or the requested
        ID may be incorrect.
    </p>

    <a
        href="<?= htmlspecialchars($appUrl) ?>/admin/technologies"
        class="admin-card-button">
        Back to Technologies
    </a>

</div>