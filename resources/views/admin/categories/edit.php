<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var string $csrfField
 * @var string|null $error
 * @var array $category
 */
?>

<section class="admin-category-form-page">

    <div class="admin-page-header">

        <div>

            <p class="admin-page-eyebrow">
                Categories
            </p>

            <h2>
                Edit Category
            </h2>

            <p>
                Update the category information below.
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
            action="<?= htmlspecialchars($appUrl) ?>/admin/categories/<?= (int) $category['id'] ?>">

            <?= $csrfField ?>


            <div class="admin-form-group">

                <label for="name">
                    Category Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($category['name']) ?>"
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
                    value="<?= htmlspecialchars($category['slug']) ?>"
                    maxlength="120"
                    autocomplete="off">

                <small>
                    Use lowercase letters and hyphens.
                </small>

            </div>


            <div class="admin-form-actions">

                <button
                    type="submit"
                    class="admin-card-button">
                    Save Changes
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