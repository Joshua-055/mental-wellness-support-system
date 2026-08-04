<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Mental Wellness System' ?>
    </title>

    <script>
        (() => {
            try {
                const savedTheme = localStorage.getItem('mindful-theme');
                document.documentElement.dataset.theme = savedTheme === 'dark' ? 'dark' : 'light';
            } catch (error) {
                document.documentElement.dataset.theme = 'light';
            }
        })();
    </script>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base.css">
    <?php foreach (($pageStyles ?? []) as $pageStyle): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= htmlspecialchars($pageStyle) ?>.css">
    <?php endforeach; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/theme.css">
</head>
<body>
