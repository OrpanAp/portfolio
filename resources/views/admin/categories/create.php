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

<section class="admin-category-form-page">

    <div class="admin-page-header">

        <div>

            <p class="admin-page-eyebrow">
                Categories
            </p>

            <h2>
                Add Category
            </h2>

            <p>
                Create a new category for your portfolio projects.
            </p>

        </div>

        <a
            href="<?= htmlspecialchars($appUrl) ?>/admin/categories"
            class="admin-card-button">
            Back to Categories
        </a>

    </div>


    <?php if (!empty($error)): ?>

        <div class="admin-form-error">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>


    <div class="admin-form-card">

        <form
            method="POST"
            action="<?= htmlspecialchars($appUrl) ?>/admin/categories">

            <?= $csrfField ?>


            <div class="admin-form-group">

                <label for="name">
                    Category Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($name) ?>"
                    placeholder="Example: Web Development"
                    required
                    maxlength="100"
                    autocomplete="off">

                <small>
                    The name visitors and administrators will see.
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
                    placeholder="Example: web-development"
                    maxlength="120"
                    autocomplete="off">

                <small>
                    Leave this empty to generate the slug automatically.
                </small>

            </div>


            <div class="admin-form-actions">

                <button
                    type="submit"
                    class="admin-card-button">
                    Create Category
                </button>

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin/categories"
                    class="admin-cancel-button">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</section>