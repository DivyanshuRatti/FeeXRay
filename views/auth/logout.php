<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <!-- Backup redirect after 5 seconds, in case JavaScript is off -->
    <meta http-equiv="refresh" content="5;url=/">
    <title>Logged out | FeeXRay</title>
    <script>
        // Use the same browser theme as the dashboard.
        (() => {
            let theme = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            try {
                const saved = localStorage.getItem('feexray-theme');
                if (saved === 'light' || saved === 'dark') theme = saved;
            } catch {}
            document.documentElement.dataset.theme = theme;
        })();
    </script>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/logout.css">
    <script defer src="assets/js/logout.js"></script>
</head>
<body class="logout-page">
    <div class="logout-scene" id="logout-scene" aria-hidden="true">
        <span class="logout-coin logout-coin-left">$</span>
        <span class="logout-coin logout-coin-right">$</span>
        <span class="logout-coin logout-coin-small">$</span>
    </div>
    <main class="logout-card glass" aria-labelledby="logout-heading">
        <div class="brand logout-brand">
            <span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
            <span>Fee<span class="brand-accent">X</span>Ray</span>
        </div>
        <h1 id="logout-heading">You’re logged out</h1>
        <p class="logout-message">Redirecting to the homepage in
            <strong><span id="countdown">5</span> <span id="countdown-unit">seconds</span></strong>.
        </p>
        <progress class="logout-progress" id="logout-progress" max="5" value="5" aria-hidden="true"></progress>
        <a class="button logout-home" id="logout-home" href="/">Go to homepage</a>
    </main>
</body>
</html>
