<?php
$displayName = trim($_SESSION['name'] ?? '') ?: 'Investor';
$accountLabels = ['PENSION' => 'Pension', 'NON_REG' => 'Non-registered'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <title><?= htmlspecialchars($pageTitle) ?> | FeeXRay</title>
    <script>
        // Read the dashboard theme before the page is painted.
        (() => {
            let theme = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            try {
                const saved = localStorage.getItem('feexray-theme');
                if (saved === 'dark' || saved === 'light') theme = saved;
            } catch {}
            document.documentElement.dataset.theme = theme;
        })();
    </script>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/portfolios.css">
    <script defer src="assets/js/portfolios.js"></script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    <!-- Shared navigation for the portfolio and holding pages. -->
    <header class="portfolio-header glass">
        <a class="brand" href="/dashboard.php">
            <span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
            <span>Fee<span class="brand-accent">X</span>Ray</span>
        </a>
        <nav class="portfolio-nav" aria-label="Main navigation">
            <a href="/dashboard.php">Dashboard</a>
            <a href="/portfolios.php" aria-current="<?= !empty($isPortfolioList) ? 'page' : 'true' ?>">Portfolios</a>
            <a href="/settings.php">Settings</a>
            <a href="/logout.php">Log out</a>
        </nav>
        <div class="portfolio-user">
            <button type="button" class="theme-toggle glass" id="portfolio-theme" aria-label="Dark mode" aria-pressed="false" title="Toggle dark mode" hidden>
                <span aria-hidden="true">☾</span>
            </button>
            <span class="portfolio-username"><?= htmlspecialchars($displayName) ?></span>
        </div>
    </header>
