<?php

/**
 * @var string $title
 * @var string $appUrl
 */
?>

<section class="admin-empty-state">

    <h2>
        Category Not Found
    </h2>

    <p>
        The category you are looking for does not exist.
    </p>

    <a
        href="<?= htmlspecialchars($appUrl) ?>/admin/categories"
        class="admin-card-button">
        Back to Categories
    </a>

</section>