<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array $portfolios
 * @var string $csrfField
 */
?>

<div class="admin-page-header">
    <div>
        <h2>Portfolio</h2>

        <p>
            Manage your portfolio projects.
        </p>
    </div>

    <a
        href="<?= htmlspecialchars($appUrl) ?>/admin/portfolio/create"
        class="admin-card-button">
        Add Portfolio
    </a>
</div>

<?php if (empty($portfolios)): ?>

    <div class="admin-empty-state">

        <h3>No Portfolio Projects Found</h3>

        <p>
            You haven't added any portfolio projects yet.
        </p>

        <a
            href="<?= htmlspecialchars($appUrl) ?>/admin/portfolio/create"
            class="admin-card-button">
            Add Your First Project
        </a>

    </div>

<?php else: ?>

    <div class="admin-table-wrapper">

        <table class="admin-table">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Project</th>
                    <th>Category</th>
                    <th>Technologies</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($portfolios as $portfolio): ?>

                    <tr>

                        <td>
                            <?= (int) $portfolio['id'] ?>
                        </td>

                        <td>

                            <strong>
                                <?= htmlspecialchars(
                                    $portfolio['title']
                                ) ?>
                            </strong>

                            <br>

                            <small>
                                <?= htmlspecialchars(
                                    $portfolio['slug']
                                ) ?>
                            </small>

                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $portfolio['category_name']
                            ) ?>
                        </td>

                        <td>

                            <?php if (
                                !empty($portfolio['technologies'])
                            ): ?>

                                <div class="admin-tech-list">

                                    <?php foreach (
                                        $portfolio['technologies']
                                        as $technology
                                    ): ?>

                                        <span class="admin-tech-tag">
                                            <?= htmlspecialchars(
                                                $technology['name']
                                            ) ?>
                                        </span>

                                    <?php endforeach; ?>

                                </div>

                            <?php else: ?>

                                <span class="admin-muted">
                                    None
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php if (
                                $portfolio['project_type']
                                === 'upload'
                            ): ?>

                                Upload

                            <?php else: ?>

                                External URL

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php if (
                                (int) $portfolio['is_published']
                                === 1
                            ): ?>

                                <span class="admin-status admin-status-published">
                                    Published
                                </span>

                            <?php else: ?>

                                <span class="admin-status admin-status-draft">
                                    Hidden
                                </span>

                            <?php endif; ?>

                            <?php if (
                                (int) $portfolio['is_featured']
                                === 1
                            ): ?>

                                <span class="admin-status admin-status-featured">
                                    Featured
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <div class="admin-table-actions">

                                <a
                                    href="<?= htmlspecialchars($appUrl) ?>/admin/portfolio/<?= (int) $portfolio['id'] ?>/edit"
                                    class="admin-action-button">
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="<?= htmlspecialchars($appUrl) ?>/admin/portfolio/<?= (int) $portfolio['id'] ?>/delete"
                                    onsubmit="return confirm('Are you sure you want to delete this portfolio project?');">

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