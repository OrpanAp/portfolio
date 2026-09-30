<?php

/**
 * @var string $title
 * @var string $appUrl
 * @var string|null $error
 * @var string $csrfField
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

    <script src="<?= htmlspecialchars($appUrl) ?>/assets/js/theme.js"></script>

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars($appUrl) ?>/assets/css/admin.css">

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars($appUrl) ?>/assets/css/responsive.css">

</head>

<body>

    <section class="admin-login-page">

        <div class="admin-login-box">

            <p class="admin-login-eyebrow">
                Administration
            </p>

            <h1>
                Admin Login
            </h1>

            <?php if (!empty($error)): ?>

                <div class="admin-login-error">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>

            <form
                method="POST"
                action="<?= htmlspecialchars($appUrl) ?>/admin/login">

                <?= $csrfField ?>

                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        autocomplete="email">

                </div>

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password">

                </div>

                <button
                    type="submit"
                    class="admin-login-button">
                    Login
                </button>

            </form>

        </div>

    </section>

</body>

</html>