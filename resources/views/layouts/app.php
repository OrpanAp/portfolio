<?php

/**
 * @var string $title
 * @var string $content
 * @var string $appUrl
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title) ?></title>

    <script src="<?= htmlspecialchars($appUrl) ?>/assets/js/theme.js"></script>

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars($appUrl) ?>/assets/css/main.css">

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars($appUrl) ?>/assets/css/responsive.css">

</head>

<body>

    <?php require __DIR__ . '/../components/navbar.php'; ?>

    <main>

        <?= $content ?>

    </main>

    <?php require __DIR__ . '/../components/footer.php'; ?>

    <script src="<?= htmlspecialchars($appUrl) ?>/assets/js/main.js"></script>
</body>

</html>