<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array $portfolios
 * @var array $categories
 * @var array $technologies
 * @var string|null $search
 * @var int|null $selectedCategory
 * @var int|null $selectedTechnology
 * @var int $page
 * @var int $perPage
 * @var int $total
 * @var int $totalPages
 * @var int $sort
 */

$categories =
    $categories ?? [];

$technologies =
    $technologies ?? [];

$search =
    $search ?? '';

$sort =
    $sort ?? 'featured';

$selectedCategory =
    $selectedCategory ?? null;

$selectedTechnology =
    $selectedTechnology ?? null;

$page =
    $page ?? 1;

$totalPages =
    $totalPages ?? 1;

$total =
    $total ?? 0;
?>

<section class="portfolio-page page-transition">

    <header class="portfolio-header" data-reveal>

        <p class="portfolio-eyebrow">
            My Work
        </p>

        <h1>
            Portfolio
        </h1>

        <p>
            A collection of projects I have built.
        </p>

        <?php if ($total === 1): ?>

            <p class="portfolio-header-count">
                1 project
            </p>

        <?php else: ?>

            <p class="portfolio-header-count">
                <?= htmlspecialchars((string) $total) ?> projects
            </p>

        <?php endif; ?>

    </header>

    <!--
|--------------------------------------------------------------------------
| Portfolio Filters
|--------------------------------------------------------------------------
-->

    <form
        method="GET"
        action="<?= htmlspecialchars($appUrl) ?>/portfolio"
        class="portfolio-filters" data-reveal>

        <div class="portfolio-filter-group">

            <label for="portfolio-search">
                Search
            </label>

            <input
                type="search"
                id="portfolio-search"
                name="search"
                value="<?= htmlspecialchars($search) ?>"
                placeholder="Search projects...">

        </div>

        <div class="portfolio-filter-group">

            <label for="portfolio-category">
                Category
            </label>

            <select
                id="portfolio-category"
                name="category">

                <option value="">
                    All Categories
                </option>

                <?php foreach ($categories as $category): ?>

                    <option
                        value="<?= (int) $category['id'] ?>"
                        <?= (string) $selectedCategory ===
                            (string) $category['id']
                            ? 'selected'
                            : '' ?>>

                        <?= htmlspecialchars(
                            $category['name']
                        ) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="portfolio-filter-group">

            <label for="portfolio-technology">
                Technology
            </label>

            <select
                id="portfolio-technology"
                name="technology">

                <option value="">
                    All Technologies
                </option>

                <?php foreach ($technologies as $technology): ?>

                    <option
                        value="<?= (int) $technology['id'] ?>"
                        <?= (string) $selectedTechnology ===
                            (string) $technology['id']
                            ? 'selected'
                            : '' ?>>

                        <?= htmlspecialchars(
                            $technology['name']
                        ) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="portfolio-filter-group">

            <label for="portfolio-sort">
                Sort By
            </label>

            <select
                id="portfolio-sort"
                name="sort">

                <option
                    value="featured"
                    <?= $sort === 'featured' ? 'selected' : '' ?>>
                    Featured
                </option>

                <option
                    value="newest"
                    <?= $sort === 'newest' ? 'selected' : '' ?>>
                    Newest
                </option>

                <option
                    value="oldest"
                    <?= $sort === 'oldest' ? 'selected' : '' ?>>
                    Oldest
                </option>

                <option
                    value="name_asc"
                    <?= $sort === 'name_asc' ? 'selected' : '' ?>>
                    Name A–Z
                </option>

                <option
                    value="name_desc"
                    <?= $sort === 'name_desc' ? 'selected' : '' ?>>
                    Name Z–A
                </option>

            </select>

        </div>

        <div class="portfolio-filter-actions">

            <button
                type="submit"
                class="portfolio-filter-button">

                Search

            </button>

            <?php if (
                $search !== '' ||
                $selectedCategory !== null ||
                $selectedTechnology !== null ||
                $sort !== 'featured'
            ): ?>

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/portfolio"
                    class="portfolio-filter-reset">
                    Reset
                </a>

            <?php endif; ?>

        </div>

    </form>

    <div class="portfolio-result-count" data-reveal>

        <?php if ($total === 1): ?>

            1 project found

        <?php else: ?>

            <?= htmlspecialchars((string) $total) ?> projects found

        <?php endif; ?>

    </div>

    <?php if (empty($portfolios)): ?>

        <div class="portfolio-empty empty-state" data-reveal>

            <h2>
                No Projects Found
            </h2>

            <p>
                Try changing your search or filters.
            </p>

        </div>

    <?php else: ?>

        <div class="portfolio-grid">

            <?php foreach ($portfolios as $portfolio): ?>

                <article class="portfolio-card" data-reveal>

                    <?php if (!empty($portfolio['is_featured'])): ?>

                        <span class="portfolio-featured-badge">
                            Featured
                        </span>

                    <?php endif; ?>

                    <?php if (!empty($portfolio['thumbnail'])): ?>

                        <div class="portfolio-card-media"><div class="skeleton skeleton-media" aria-hidden="true"></div>
                        <img
                            src="<?= htmlspecialchars($portfolio['thumbnail']) ?>"
                            alt="<?= htmlspecialchars($portfolio['title']) ?>"
                            class="portfolio-thumbnail" loading="lazy" decoding="async">
                        </div>

                    <?php else: ?>

                        <div class="portfolio-card-media">
                            <div class="portfolio-thumbnail-placeholder">
                                <?= htmlspecialchars($portfolio['title']) ?>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="portfolio-card-content">

                        <p class="portfolio-category">
                            <?= htmlspecialchars(
                                $portfolio['category_name']
                            ) ?>
                        </p>

                        <h2>
                            <?= htmlspecialchars(
                                $portfolio['title']
                            ) ?>
                        </h2>

                        <p>
                            <?= htmlspecialchars(
                                $portfolio['description']
                            ) ?>
                        </p>

                        <?php if (!empty($portfolio['technologies'])): ?>

                            <div class="portfolio-technologies">

                                <?php foreach ($portfolio['technologies'] as $technology): ?>

                                    <span class="portfolio-technology">
                                        <?= htmlspecialchars($technology['name']) ?>
                                    </span>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                        <a
                            href="<?= htmlspecialchars($appUrl) ?>/portfolio/<?= urlencode($portfolio['slug']) ?>"
                            class="portfolio-view-button">

                            View Project

                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

        <!--
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    -->

        <?php if ($totalPages > 1): ?>

            <nav
                class="portfolio-pagination"
                data-reveal
                aria-label="Portfolio pagination">

                <?php if ($page > 1): ?>

                    <a
                        href="?<?= http_build_query([
                                    'search' =>
                                    $search !== ''
                                        ? $search
                                        : null,

                                    'category' =>
                                    $selectedCategory,

                                    'technology' =>
                                    $selectedTechnology,

                                    'sort' =>
                                    $sort,

                                    'page' =>
                                    $page - 1,
                                ]) ?>">

                        Previous

                    </a>

                <?php endif; ?>

                <?php for (
                    $pageNumber = 1;
                    $pageNumber <= $totalPages;
                    $pageNumber++
                ): ?>

                    <a
                        href="?<?= http_build_query([
                                    'search' =>
                                    $search !== ''
                                        ? $search
                                        : null,

                                    'category' =>
                                    $selectedCategory,

                                    'technology' =>
                                    $selectedTechnology,

                                    'sort' =>
                                    $sort,

                                    'page' =>
                                    $pageNumber,
                                ]) ?>"
                        <?= $pageNumber === $page
                            ? 'aria-current="page"'
                            : '' ?>>

                        <?= $pageNumber ?>

                    </a>

                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>

                    <a
                        href="?<?= http_build_query([
                                    'search' =>
                                    $search !== ''
                                        ? $search
                                        : null,

                                    'category' =>
                                    $selectedCategory,

                                    'technology' =>
                                    $selectedTechnology,

                                    'sort' =>
                                    $sort,

                                    'page' =>
                                    $page + 1,
                                ]) ?>">

                        Next

                    </a>

                <?php endif; ?>

            </nav>

        <?php endif; ?>

    <?php endif; ?>

</section>