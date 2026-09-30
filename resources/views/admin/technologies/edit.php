<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var string $csrfField
 * @var string|null $error
 * @var array $technology
 */
?>

<div class="admin-page-header">
    <div>
        <h2>Edit Technology</h2>

        <p>
            Update the technology name or slug.
        </p>
    </div>

    <a
        href="<?= htmlspecialchars($appUrl) ?>/admin/technologies"
        class="admin-card-button">
        Back to Technologies
    </a>
</div>

<div class="admin-form-card">

    <?php if ($error !== null): ?>

        <div class="admin-form-error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form
        method="POST"
        action="<?= htmlspecialchars($appUrl) ?>/admin/technologies/<?= (int) $technology['id'] ?>">

        <?= $csrfField ?>

        <div class="admin-form-group">

            <label for="name">
                Technology Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($technology['name']) ?>"
                required>

            <small>
                The name displayed on your portfolio.
            </small>

        </div>

        <div class="admin-form-group">

            <label for="slug">
                Slug
            </label>

            <input
                type="text"
                id="slug"
                name="slug"
                value="<?= htmlspecialchars($technology['slug']) ?>"
                placeholder="Example: react">

            <small>
                Leave blank to generate the slug automatically.
            </small>

        </div>

        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-card-button">
                Update Technology
            </button>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/admin/technologies"
                class="admin-cancel-button">
                Cancel
            </a>

        </div>

    </form>

</div>