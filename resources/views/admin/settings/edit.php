<?php

declare(strict_types=1);

$appUrl = $appUrl ?? '';
$csrfField = $csrfField ?? '';

$settings = $settings ?? [];
$error = $error ?? null;

$siteTitle =
    $settings['site_title'] ?? '';

$siteDescription =
    $settings['site_description'] ?? '';

$githubUrl =
    $settings['github_url'] ?? '';

$linkedinUrl =
    $settings['linkedin_url'] ?? '';

$facebookUrl =
    $settings['facebook_url'] ?? '';

$instagramUrl =
    $settings['instagram_url'] ?? '';

$twitterUrl =
    $settings['twitter_url'] ?? '';
?>

<div class="admin-page-header">
    <div>
        <h1>Website Settings</h1>
        <p>
            Manage your website information and social media links.
        </p>
    </div>
</div>

<?php if ($error !== null): ?>
    <div class="admin-form-error">
        <?= htmlspecialchars(
            $error,
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </div>
<?php endif; ?>

<div class="admin-form-card">
    <form
        method="POST"
        action="<?= htmlspecialchars(
                    $appUrl . '/admin/settings',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>">
        <?= $csrfField ?>

        <div class="admin-form-group">
            <label for="site_title">
                Site Title
            </label>

            <input
                type="text"
                id="site_title"
                name="site_title"
                value="<?= htmlspecialchars(
                            $siteTitle,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                maxlength="150"
                required>

            <small>
                The main name/title of your website.
            </small>
        </div>

        <div class="admin-form-group">
            <label for="site_description">
                Site Description
            </label>

            <textarea
                id="site_description"
                name="site_description"
                rows="4"
                maxlength="500"><?= htmlspecialchars(
                                    $siteDescription,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?></textarea>

            <small>
                A short description of your portfolio website.
            </small>
        </div>

        <div class="admin-form-group">
            <label for="github_url">
                GitHub URL
            </label>

            <input
                type="url"
                id="github_url"
                name="github_url"
                value="<?= htmlspecialchars(
                            $githubUrl,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                placeholder="https://github.com/username">
        </div>

        <div class="admin-form-group">
            <label for="linkedin_url">
                LinkedIn URL
            </label>

            <input
                type="url"
                id="linkedin_url"
                name="linkedin_url"
                value="<?= htmlspecialchars(
                            $linkedinUrl,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                placeholder="https://www.linkedin.com/in/username">
        </div>

        <div class="admin-form-group">
            <label for="facebook_url">
                Facebook URL
            </label>

            <input
                type="url"
                id="facebook_url"
                name="facebook_url"
                value="<?= htmlspecialchars(
                            $facebookUrl,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                placeholder="https://www.facebook.com/username">
        </div>

        <div class="admin-form-group">
            <label for="instagram_url">
                Instagram URL
            </label>

            <input
                type="url"
                id="instagram_url"
                name="instagram_url"
                value="<?= htmlspecialchars(
                            $instagramUrl,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                placeholder="https://www.instagram.com/username">
        </div>

        <div class="admin-form-group">
            <label for="twitter_url">
                Twitter / X URL
            </label>

            <input
                type="url"
                id="twitter_url"
                name="twitter_url"
                value="<?= htmlspecialchars(
                            $twitterUrl,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                placeholder="https://x.com/username">
        </div>

        <div class="admin-form-actions">
            <button
                type="submit"
                class="admin-card-button">
                Save Settings
            </button>

            <a
                href="<?= htmlspecialchars(
                            $appUrl . '/admin',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                class="admin-cancel-button">
                Cancel
            </a>
        </div>
    </form>
</div>
