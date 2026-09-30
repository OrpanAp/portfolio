<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var string $csrfField
 * @var string|null $error
 * @var string $name
 * @var string $slug
 */
?>

<div class="admin-page-header">
    <div>
        <h2>Add Technology</h2>

        <p>
            Add a technology that can be assigned to portfolio projects.
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
        action="<?= htmlspecialchars($appUrl) ?>/admin/technologies">

        <?= $csrfField ?>

        <div class="admin-form-group">

            <label for="name">
                Technology Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($name) ?>"
                placeholder="Example: React"
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
                value="<?= htmlspecialchars($slug) ?>"
                placeholder="Example: react">

            <small>
                Leave blank to generate the slug automatically.
            </small>

        </div>

        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-card-button">
                Save Technology
            </button>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/admin/technologies"
                class="admin-cancel-button">
                Cancel
            </a>

        </div>

    </form>

</div>