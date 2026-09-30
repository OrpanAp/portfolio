<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array|null $user
 */
?>

<section class="admin-dashboard">

    <div class="admin-page-header">

        <p class="admin-page-eyebrow">
            Administration
        </p>

        <h2>
            Dashboard
        </h2>

        <p>
            Manage your portfolio website from this panel.
        </p>

    </div>

    <div class="admin-dashboard-grid">

        <div class="admin-dashboard-card">

            <h3>
                Portfolio
            </h3>

            <p>
                Add, edit and manage your projects.
            </p>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/admin/portfolio"
                class="admin-card-button">
                Manage Portfolio
            </a>

        </div>

        <div class="admin-dashboard-card">

            <h3>
                Profile
            </h3>

            <p>
                Update your personal information, skills,
                experience and education.
            </p>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/admin/profile"
                class="admin-card-button">
                Edit Profile
            </a>

        </div>

        <div class="admin-dashboard-card">

            <h3>
                CV
            </h3>

            <p>
                Manage the CV available for visitors to download.
            </p>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/admin/cv"
                class="admin-card-button">
                Manage CV
            </a>

        </div>

        <div class="admin-dashboard-card">

            <h3>
                Settings
            </h3>

            <p>
                Manage website settings and social media links.
            </p>

            <a
                href="<?= htmlspecialchars($appUrl) ?>/admin/settings"
                class="admin-card-button">
                Manage Settings
            </a>

        </div>

    </div>

</section>