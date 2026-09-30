<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var string|null $cvPath
 * @var string $csrfField
 */
?>

<div class="admin-page">

    <div class="admin-page-header">
        <div>
            <h2>CV Management</h2>
            <p>Upload and manage the CV displayed on your portfolio.</p>
        </div>
    </div>

    <div class="admin-card">

        <h2>Current CV</h2>

        <?php if ($cvPath !== null && $cvPath !== ''): ?>

            <p>
                Current CV:
                <strong>
                    <?= htmlspecialchars($cvPath) ?>
                </strong>
            </p>

            <div
                style="
                    width: 100%;
                    height: 700px;
                    margin-top: 20px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    overflow: hidden;
                ">

                <iframe
                    src="<?= htmlspecialchars(
                                $appUrl . '/cv/preview'
                            ) ?>"
                    title="CV Preview"
                    style="
                        width: 100%;
                        height: 100%;
                        border: 0;
                    "></iframe>

            </div>

            <p style="margin-top: 15px;">

                <a
                    href="<?= htmlspecialchars(
                                $appUrl . '/cv/preview'
                            ) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="admin-card-button">
                    Open CV in New Tab
                </a>

            </p>

            <p>

                <a
                    href="<?= htmlspecialchars(
                                $appUrl . '/cv/download'
                            ) ?>"
                    class="admin-card-button">
                    Download CV
                </a>

            </p>

        <?php else: ?>

            <p>
                No CV has been uploaded yet.
            </p>

        <?php endif; ?>

    </div>

    <div class="admin-card">

        <h2>
            <?= $cvPath ? 'Replace CV' : 'Upload CV' ?>
        </h2>

        <form
            action="<?= htmlspecialchars(
                        $appUrl . '/admin/cv'
                    ) ?>"
            method="POST"
            enctype="multipart/form-data">

            <?= $csrfField ?>

            <div class="form-group">

                <label for="cv">
                    CV PDF
                </label>

                <input
                    type="file"
                    id="cv"
                    name="cv"
                    accept=".pdf,application/pdf"
                    required>

                <small>
                    PDF only. Maximum size: 10 MB.
                </small>

            </div>

            <button
                type="submit"
                class="admin-card-button">
                <?= $cvPath ? 'Replace CV' : 'Upload CV' ?>
            </button>

        </form>

    </div>

</div>