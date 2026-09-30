<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array $technologies
 * @var string $csrfField
 */
?>

<div class="admin-page-header">
    <div>
        <h2>Technologies</h2>
        <p>Manage the technologies used by your portfolio projects.</p>
    </div>

    <a
        href="<?= htmlspecialchars($appUrl) ?>/admin/technologies/create"
        class="admin-card-button">
        Add Technology
    </a>
</div>

<?php if (empty($technologies)): ?>

    <div class="admin-empty-state">
        <h3>No Technologies Found</h3>

        <p>
            You haven't added any technologies yet.
        </p>

        <a
            href="<?= htmlspecialchars($appUrl) ?>/admin/technologies/create"
            class="admin-card-button">
            Add Your First Technology
        </a>
    </div>

<?php else: ?>

    <div class="admin-table-wrapper">
        <table class="admin-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($technologies as $technology): ?>

                    <tr>

                        <td>
                            <?= (int) $technology['id'] ?>
                        </td>

                        <td>
                            <strong>
                                <?= htmlspecialchars(
                                    $technology['name']
                                ) ?>
                            </strong>
                        </td>

                        <td>
                            <code>
                                <?= htmlspecialchars(
                                    $technology['slug']
                                ) ?>
                            </code>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $technology['created_at']
                            ) ?>
                        </td>

                        <td>

                            <div class="admin-table-actions">

                                <a
                                    href="<?= htmlspecialchars($appUrl) ?>/admin/technologies/<?= (int) $technology['id'] ?>/edit"
                                    class="admin-action-button">
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="<?= htmlspecialchars($appUrl) ?>/admin/technologies/<?= (int) $technology['id'] ?>/delete"
                                    onsubmit="return confirm('Are you sure you want to delete this technology?');">

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