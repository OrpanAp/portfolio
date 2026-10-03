<?php

declare(strict_types=1);

$appUrl = $appUrl ?? '';
$csrfField = $csrfField ?? '';

$profile = $profile ?? [];
$error = $error ?? null;

$fullName =
    $profile['full_name'] ?? '';

$headline =
    $profile['headline'] ?? '';

$bio =
    $profile['bio'] ?? '';

$skills =
    $profile['skills'] ?? '';

$experience =
    $profile['experience'] ?? '';

$education =
    $profile['education'] ?? '';

$profileImage =
    $profile['profile_image'] ?? null;
?>

<div class="admin-page-header">
    <div>
        <h1>Edit Profile</h1>
        <p>
            Update your personal information,
            skills, experience and education.
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
        action="<?= htmlspecialchars($appUrl . '/admin/profile', ENT_QUOTES, 'UTF-8') ?>"
        enctype="multipart/form-data">
        <?= $csrfField ?>

        <div class="admin-form-group">
            <label for="full_name">
                Full Name
            </label>

            <input
                type="text"
                id="full_name"
                name="full_name"
                value="<?= htmlspecialchars(
                            $fullName,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                maxlength="150"
                required>
        </div>

        <div class="admin-form-group">
            <label for="headline">
                Headline
            </label>

            <input
                type="text"
                id="headline"
                name="headline"
                value="<?= htmlspecialchars(
                            $headline,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                maxlength="255"
                placeholder="Software Developer">
        </div>

        <div class="admin-form-group">
            <label for="bio">
                Bio
            </label>

            <textarea
                id="bio"
                name="bio"
                rows="7"><?= htmlspecialchars(
                                $bio,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?></textarea>
        </div>

        <div class="admin-form-group">
            <label for="skills">
                Skills
            </label>

            <textarea
                id="skills"
                name="skills"
                rows="7"
                placeholder="C#, PHP, MySQL, Unity..."><?= htmlspecialchars(
                                                            $skills,
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?></textarea>

            <small>
                Enter your skills in the format
                you want them stored/displayed.
            </small>
        </div>

        <div class="admin-form-group">
            <label for="experience">
                Experience
            </label>

            <textarea
                id="experience"
                name="experience"
                rows="8"
                placeholder="Describe your professional experience..."><?= htmlspecialchars(
                                                                            $experience,
                                                                            ENT_QUOTES,
                                                                            'UTF-8'
                                                                        ) ?></textarea>
        </div>

        <div class="admin-form-group">
            <label for="education">
                Education
            </label>

            <textarea
                id="education"
                name="education"
                rows="8"
                placeholder="Describe your education..."><?= htmlspecialchars(
                                                                $education,
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ) ?></textarea>
        </div>

        <?php if ($profileImage !== null && $profileImage !== ''): ?>
            <div class="admin-form-group">
                <label>Current Profile Image</label>
                <p>
                    <?= htmlspecialchars($profileImage, ENT_QUOTES, 'UTF-8') ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="admin-form-group">
            <label for="profile_image">Profile Image</label>
            <input
                type="file"
                id="profile_image"
                name="profile_image"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            <small>
                Optional. JPG, JPEG, PNG, or WebP.
                Maximum size: 5 MB.
            </small>
        </div>

        <div class="admin-form-actions">
            <button
                type="submit"
                class="admin-card-button">
                Save Profile
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
