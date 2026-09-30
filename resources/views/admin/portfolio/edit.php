<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var string $csrfField
 * @var string|null $error
 * @var array $portfolio
 * @var array $categories
 * @var array $technologies
 * @var array $selectedTechnologyIds
 */
?>

<div class="admin-page-header">
    <div>
        <h2>Edit Portfolio</h2>

        <p>
            Update this portfolio project.
        </p>
    </div>

    <a
        href="<?= htmlspecialchars($appUrl) ?>/admin/portfolio"
        class="admin-card-button">
        Back to Portfolio
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
        action="<?= htmlspecialchars($appUrl) ?>/admin/portfolio/<?= (int) $portfolio['id'] ?>"
        enctype="multipart/form-data">

        <?= $csrfField ?>

        <div class="admin-form-group">

            <label for="title">
                Project Title
            </label>

            <input
                type="text"
                id="title"
                name="title"
                value="<?= htmlspecialchars(
                            $portfolio['title']
                        ) ?>"
                required>

        </div>

        <div class="admin-form-group">

            <label for="slug">
                Slug
            </label>

            <input
                type="text"
                id="slug"
                name="slug"
                value="<?= htmlspecialchars(
                            $portfolio['slug']
                        ) ?>"
                placeholder="Example: personal-portfolio">

            <small>
                Leave blank to generate the slug automatically.
            </small>

        </div>

        <div class="admin-form-group">

            <label for="category_id">
                Category
            </label>

            <select
                id="category_id"
                name="category_id"
                required>

                <option value="">
                    Select a category
                </option>

                <?php foreach ($categories as $category): ?>

                    <option
                        value="<?= (int) $category['id'] ?>"
                        <?= (int) $portfolio['category_id']
                            === (int) $category['id']
                            ? 'selected'
                            : '' ?>>
                        <?= htmlspecialchars(
                            $category['name']
                        ) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="admin-form-group">

            <label for="description">
                Description
            </label>

            <textarea
                id="description"
                name="description"
                rows="7"
                required><?= htmlspecialchars(
                                $portfolio['description']
                            ) ?></textarea>

        </div>

        <div class="admin-form-group">

            <label>
                Technologies
            </label>

            <div class="admin-checkbox-grid">

                <?php foreach ($technologies as $technology): ?>

                    <label class="admin-checkbox-item">

                        <input
                            type="checkbox"
                            name="technology_ids[]"
                            value="<?= (int) $technology['id'] ?>"
                            <?= in_array(
                                (int) $technology['id'],
                                $selectedTechnologyIds,
                                true
                            )
                                ? 'checked'
                                : '' ?>>

                        <span>
                            <?= htmlspecialchars(
                                $technology['name']
                            ) ?>
                        </span>

                    </label>

                <?php endforeach; ?>

            </div>

            <?php if (empty($technologies)): ?>

                <small>
                    No technologies are available.
                    Add technologies from the Technologies section first.
                </small>

            <?php endif; ?>

        </div>

        <div class="admin-form-group">

            <label for="project_type">
                Project Type
            </label>

            <select
                id="project_type"
                name="project_type"
                required>

                <option
                    value="url"
                    <?= $portfolio['project_type'] === 'url'
                        ? 'selected'
                        : '' ?>>
                    External URL
                </option>

                <option
                    value="upload"
                    <?= $portfolio['project_type'] === 'upload'
                        ? 'selected'
                        : '' ?>>
                    Uploaded Project
                </option>

            </select>

        </div>

        <div class="admin-form-group">

            <label for="external_url">
                External Project URL
            </label>

            <input
                type="url"
                id="external_url"
                name="external_url"
                value="<?= htmlspecialchars(
                            $portfolio['external_url'] ?? ''
                        ) ?>"
                placeholder="https://example.com">

            <small>
                Use this when Project Type is External URL.
            </small>

        </div>

        <div class="admin-form-group">

            <label for="entry_path">
                Project Entry Path
            </label>

            <input
                type="text"
                id="entry_path"
                name="entry_path"
                value="<?= htmlspecialchars(
                            $portfolio['entry_path'] ?? ''
                        ) ?>"
                placeholder="index.html">

            <small>
                Used for uploaded projects.
                Example: index.html
            </small>

        </div>

        <div class="admin-form-group">

            <label for="project_zip">
                Replace Project ZIP
            </label>

            <input
                type="file"
                id="project_zip"
                name="project_zip"
                accept=".zip">

            <small>
                Leave this empty to keep the existing uploaded project.
                Maximum size: 50 MB.
            </small>

        </div>

        <div class="admin-form-group">

            <label for="sort_order">
                Sort Order
            </label>

            <input
                type="number"
                id="sort_order"
                name="sort_order"
                value="<?= (int) $portfolio['sort_order'] ?>"
                min="0">

            <small>
                Lower numbers appear first.
            </small>

        </div>

        <div class="admin-form-checkbox-row">

            <label class="admin-checkbox-item">

                <input
                    type="checkbox"
                    name="is_featured"
                    value="1"
                    <?= (int) $portfolio['is_featured'] === 1
                        ? 'checked'
                        : '' ?>>

                <span>
                    Featured project
                </span>

            </label>

        </div>

        <div class="admin-form-checkbox-row">

            <label class="admin-checkbox-item">

                <input
                    type="checkbox"
                    name="is_published"
                    value="1"
                    <?= (int) $portfolio['is_published'] === 1
                        ? 'checked'
                        : '' ?>>

                <span>
                    Published
                </span>

            </label>

        </div>

        <div class="admin-form-actions">

            <button
                type="submit"
                class="admin-card-button">
                Update Portfolio
            </button>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/admin/portfolio"
                class="admin-cancel-button">
                Cancel
            </a>

        </div>

    </form>

</div>