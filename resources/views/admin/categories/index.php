<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array $categories
 * @var string $csrfField
 */
?>

<section class="admin-category-page">

    <div class="admin-page-header">

        <div>

            <p class="admin-page-eyebrow">
                Administration
            </p>

            <h2>
                Categories
            </h2>

            <p>
                Manage the categories used by your portfolio projects.
            </p>

        </div>

        <div>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/admin/categories/create"
                class="admin-card-button">
                + Add Category
            </a>

        </div>

    </div>


    <?php if (empty($categories)): ?>

        <div class="admin-empty-state">

            <h3>
                No Categories
            </h3>

            <p>
                You haven't created any categories yet.
            </p>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/admin/categories/create"
                class="admin-card-button">
                Add Your First Category
            </a>

        </div>

    <?php else: ?>

        <div class="admin-table-wrapper">

            <table class="admin-table">

                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Slug
                        </th>

                        <th>
                            Created
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($categories as $category): ?>

                        <tr>

                            <td>
                                <?= (int) $category['id'] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($category['name']) ?>
                            </td>

                            <td>
                                <code>
                                    <?= htmlspecialchars($category['slug']) ?>
                                </code>
                            </td>

                            <td>
                                <?= htmlspecialchars($category['created_at']) ?>
                            </td>

                            <td>

                                <div class="admin-table-actions">

                                    <a
                                        href="<?= htmlspecialchars($appUrl) ?>/admin/categories/<?= (int) $category['id'] ?>/edit"
                                        class="admin-action-button">
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="<?= htmlspecialchars($appUrl) ?>/admin/categories/<?= (int) $category['id'] ?>/delete"
                                        onsubmit="return confirm('Are you sure you want to delete this category?');">

                                        <?= $csrfField ?>

                                        <button
                                            type="submit"
                                            class="admin-action-button admin-delete-button">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</section>