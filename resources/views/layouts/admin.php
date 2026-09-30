<?php

/**
 * @var string $title
 * @var string $content
 * @var string $appUrl
 * @var array|null $user
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($title) ?>
    </title>

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars($appUrl) ?>/assets/css/admin.css">

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars($appUrl) ?>/assets/css/responsive.css">

</head>

<body>

    <div class="admin-layout">

        <aside class="admin-sidebar">

            <div class="admin-sidebar-header">

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin"
                    class="admin-logo">
                    Admin Panel
                </a>

            </div>

            <nav class="admin-navigation">

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin"
                    class="admin-nav-link">
                    Dashboard
                </a>

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin/portfolio"
                    class="admin-nav-link">
                    Portfolio
                </a>

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin/categories"
                    class="admin-nav-link">
                    Categories
                </a>

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin/technologies"
                    class="admin-nav-link">
                    Technologies
                </a>

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin/profile"
                    class="admin-nav-link">
                    Profile
                </a>

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin/cv"
                    class="admin-nav-link">
                    CV
                </a>

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin/settings"
                    class="admin-nav-link">
                    Settings
                </a>

            </nav>

            <div class="admin-sidebar-footer">

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/"
                    class="admin-nav-link">
                    View Website
                </a>

                <a
                    href="<?= htmlspecialchars($appUrl) ?>/admin/logout"
                    class="admin-nav-link admin-logout-link">
                    Logout
                </a>

            </div>

        </aside>

        <div class="admin-main">

            <header class="admin-topbar">

                <div>

                    <h1>
                        <?= htmlspecialchars($title) ?>
                    </h1>

                </div>

                <?php if (($user ?? null) !== null): ?>

                    <div class="admin-user">

                        <?= htmlspecialchars($user['username']) ?>

                    </div>

                <?php endif; ?>

            </header>

            <main class="admin-content">

                <?= $content ?>

            </main>

        </div>

    </div>

</body>

</html>