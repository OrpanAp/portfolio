<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array|null $profile
 */
?>

<section class="profile-page">

    <?php if ($profile === null): ?>

        <div class="profile-empty">

            <h1>
                Profile Not Available
            </h1>

            <p>
                Profile information has not been configured yet.
            </p>

        </div>

    <?php else: ?>

        <div class="profile-header">

            <?php if (!empty($profile['profile_image'])): ?>

                <div class="profile-image-wrapper">

                    <img
                        src="<?= htmlspecialchars($profile['profile_image']) ?>"
                        alt="<?= htmlspecialchars($profile['full_name']) ?>"
                        class="profile-image">

                </div>

            <?php endif; ?>

            <div class="profile-header-content">

                <p class="profile-eyebrow">
                    Profile
                </p>

                <h1>
                    <?= htmlspecialchars($profile['full_name']) ?>
                </h1>

                <?php if (!empty($profile['headline'])): ?>

                    <p class="profile-headline">
                        <?= htmlspecialchars($profile['headline']) ?>
                    </p>

                <?php endif; ?>

            </div>

        </div>

        <?php if (!empty($profile['bio'])): ?>

            <section class="profile-section">

                <h2>
                    About Me
                </h2>

                <p>
                    <?= nl2br(htmlspecialchars($profile['bio'])) ?>
                </p>

            </section>

        <?php endif; ?>

        <?php if (!empty($profile['skills'])): ?>

            <section class="profile-section">

                <h2>
                    Skills
                </h2>

                <p>
                    <?= nl2br(htmlspecialchars($profile['skills'])) ?>
                </p>

            </section>

        <?php endif; ?>

        <?php if (!empty($profile['experience'])): ?>

            <section class="profile-section">

                <h2>
                    Experience
                </h2>

                <p>
                    <?= nl2br(htmlspecialchars($profile['experience'])) ?>
                </p>

            </section>

        <?php endif; ?>

        <?php if (!empty($profile['education'])): ?>

            <section class="profile-section">

                <h2>
                    Education
                </h2>

                <p>
                    <?= nl2br(htmlspecialchars($profile['education'])) ?>
                </p>

            </section>

        <?php endif; ?>

    <?php endif; ?>

</section>