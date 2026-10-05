<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var array|null $profile
 */
?>

<section class="profile-page page-transition">

    <?php if ($profile === null): ?>
        <div class="profile-empty empty-state" data-reveal>
            <span class="empty-state-icon" aria-hidden="true">?</span>
            <h1>Profile Not Available</h1>
            <p>Profile information has not been configured yet.</p>
        </div>
    <?php else: ?>

        <div class="profile-header" data-reveal>
            <?php if (!empty($profile['profile_image'])): ?>
                <div class="profile-image-wrapper">
                    <span class="profile-image-ring" aria-hidden="true"></span>
                    <div class="skeleton skeleton-avatar" aria-hidden="true"></div>
                    <img
                        src="<?= htmlspecialchars($profile['profile_image']) ?>"
                        alt="<?= htmlspecialchars($profile['full_name']) ?>"
                        class="profile-image"
                        loading="eager"
                        decoding="async">
                </div>
            <?php endif; ?>

            <div class="profile-header-content">
                <p class="profile-eyebrow"><span></span> Profile</p>
                <h1><?= htmlspecialchars($profile['full_name']) ?></h1>
                <?php if (!empty($profile['headline'])): ?>
                    <p class="profile-headline"><?= htmlspecialchars($profile['headline']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="profile-sections">
            <?php if (!empty($profile['bio'])): ?>
                <section class="profile-section" data-reveal>
                    <p class="section-number">01</p>
                    <div><h2>About Me</h2><p><?= nl2br(htmlspecialchars($profile['bio'])) ?></p></div>
                </section>
            <?php endif; ?>

            <?php if (!empty($profile['skills'])): ?>
                <section class="profile-section" data-reveal>
                    <p class="section-number">02</p>
                    <div><h2>Skills</h2><p><?= nl2br(htmlspecialchars($profile['skills'])) ?></p></div>
                </section>
            <?php endif; ?>

            <?php if (!empty($profile['experience'])): ?>
                <section class="profile-section" data-reveal>
                    <p class="section-number">03</p>
                    <div><h2>Experience</h2><p><?= nl2br(htmlspecialchars($profile['experience'])) ?></p></div>
                </section>
            <?php endif; ?>

            <?php if (!empty($profile['education'])): ?>
                <section class="profile-section" data-reveal>
                    <p class="section-number">04</p>
                    <div><h2>Education</h2><p><?= nl2br(htmlspecialchars($profile['education'])) ?></p></div>
                </section>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</section>
