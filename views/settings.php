<?php
if (!defined('FEEXRAY_SETTINGS_VIEW')) {
    http_response_code(404);
    exit;
}

$escape = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$accountLabels = ['PENSION' => 'Pension', 'NON_REG' => 'Non-registered'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <title>Settings | FeeXRay</title>
    <script>
        let theme = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        try {
            const savedTheme = localStorage.getItem('feexray-theme');
            if (savedTheme === 'light' || savedTheme === 'dark') theme = savedTheme;
        } catch {}
        document.documentElement.dataset.theme = theme;
    </script>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            align-items: start;
        }
        .settings-grid .panel { height: auto; padding: 21px; }
        .settings-wide { grid-column: 1 / -1; }
        .settings-form { display: grid; gap: 12px; }
        .settings-field { display: grid; gap: 5px; min-width: 0; font-size: .9rem; font-weight: 600; }
        .settings-field input, .settings-field select {
            width: 100%;
            min-width: 0;
            padding: 9px 11px;
            color: var(--ink);
            background: var(--surface);
            border: 1px solid var(--edge);
            border-radius: 10px;
        }
        .settings-form .button { justify-self: start; }
        .settings-help { color: var(--muted); font-size: .875rem; overflow-wrap: anywhere; }
        .settings-message {
            margin: 0 0 16px;
            padding: 11px 15px;
            border: 1px solid var(--edge);
            border-radius: 12px;
            background: var(--selected);
            color: var(--green);
        }
        .settings-message.error { color: #a52840; background: #fff0f1; border-color: #e8b7bf; }
        [data-theme="dark"] .settings-message.error { color: #ffc2ce; background: #442534; border-color: #764151; }
        .portfolio-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 10px;
            align-items: end;
            padding: 12px 0;
        }
        .portfolio-row + .portfolio-row { border-top: 1px solid var(--edge); }
        .portfolio-row:last-child { padding-bottom: 0; }
        .portfolio-row .button { min-height: 42px; }
        @media (max-width: 660px) {
            .settings-grid { grid-template-columns: minmax(0, 1fr); }
        }
        @media (max-width: 360px) {
            .portfolio-row { grid-template-columns: minmax(0, 1fr); }
            .portfolio-row .button { justify-self: start; }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#main">Skip to settings</a>
    <aside class="sidebar glass" aria-label="Sidebar">
        <a class="brand" href="dashboard.php">
            <span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
            <span>Fee<span class="brand-accent">X</span>Ray</span>
        </a>
        <nav class="navigation" aria-label="Main navigation">
            <a href="dashboard.php"><span class="nav-icon" aria-hidden="true">⌂</span> Home</a>
            <a href="portfolios.php"><span class="nav-icon" aria-hidden="true">▤</span> Portfolio</a>
            <a href="settings.php" aria-current="page"><span class="nav-icon" aria-hidden="true">⚙</span> Settings</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="profile">
                <span class="avatar" aria-hidden="true">●</span>
                <span class="profile-name"><?= $escape($user['name']) ?></span>
            </div>
            <a class="logout" href="logout.php"><span class="nav-icon" aria-hidden="true">⇥</span> Logout</a>
        </div>
    </aside>

    <main id="main" class="workspace">
        <header class="page-header"><h1>Settings</h1></header>
        <?php if ($notice): ?>
            <p class="settings-message" role="status"><?= $escape($notice) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="settings-message error" role="alert"><?= $escape($error) ?></p>
        <?php endif; ?>

        <div class="settings-grid">
            <section class="panel glass" aria-labelledby="profile-title">
                <div class="panel-heading"><h2 id="profile-title">Profile</h2></div>
                <form method="post" action="settings.php" class="settings-form">
                    <input type="hidden" name="csrf_token" value="<?= $escape($csrfToken) ?>">
                    <input type="hidden" name="action" value="profile">
                    <label class="settings-field">
                        Display name
                        <input name="name" value="<?= $escape($user['name']) ?>" maxlength="100" autocomplete="name" required>
                    </label>
                    <p class="settings-help"><?= $escape($user['email']) ?></p>
                    <button class="button">Save name</button>
                </form>
            </section>

            <section class="panel glass" aria-labelledby="password-title">
                <div class="panel-heading"><h2 id="password-title">Password</h2></div>
                <form method="post" action="settings.php" class="settings-form">
                    <input type="hidden" name="csrf_token" value="<?= $escape($csrfToken) ?>">
                    <input type="hidden" name="action" value="password">
                    <label class="settings-field">
                        Current password
                        <input type="password" name="current_password" autocomplete="current-password" required>
                    </label>
                    <label class="settings-field">
                        New password
                        <input type="password" name="new_password" autocomplete="new-password" minlength="8" maxlength="72" aria-describedby="password-rules" required>
                    </label>
                    <p class="settings-help" id="password-rules">Use at least 8 characters, including an uppercase letter, a lowercase letter, and a number. The maximum is 72 bytes.</p>
                    <label class="settings-field">
                        Confirm new password
                        <input type="password" name="confirm_password" autocomplete="new-password" minlength="8" maxlength="72" required>
                    </label>
                    <button class="button">Change password</button>
                </form>
            </section>

            <section class="panel glass settings-wide" aria-labelledby="portfolio-title">
                <div class="panel-heading"><h2 id="portfolio-title">Portfolio names</h2></div>
                <?php if (!$portfolios): ?>
                    <p class="settings-help">You can rename your accounts here after you add a portfolio.</p>
                <?php endif; ?>
                <?php foreach ($portfolios as $portfolio): ?>
                    <form method="post" action="settings.php" class="portfolio-row">
                        <input type="hidden" name="csrf_token" value="<?= $escape($csrfToken) ?>">
                        <input type="hidden" name="action" value="portfolio">
                        <input type="hidden" name="portfolio_id" value="<?= $escape($portfolio['id']) ?>">
                        <label class="settings-field">
                            <?= $escape($accountLabels[$portfolio['account_type']] ?? $portfolio['account_type']) ?> portfolio name
                            <input name="name" value="<?= $escape($portfolio['name']) ?>" maxlength="100" required>
                        </label>
                        <button class="button">Save portfolio name</button>
                    </form>
                <?php endforeach; ?>
            </section>

            <section class="panel glass" aria-labelledby="appearance-title">
                <div class="panel-heading"><h2 id="appearance-title">Appearance</h2></div>
                <label class="settings-field">
                    Theme
                    <select id="settings-theme">
                        <option value="light">Light</option>
                        <option value="dark">Dark</option>
                    </select>
                </label>
            </section>
        </div>
    </main>

    <script>
        const themeSelect = document.getElementById('settings-theme');
        themeSelect.value = document.documentElement.dataset.theme;
        themeSelect.addEventListener('change', () => {
            document.documentElement.dataset.theme = themeSelect.value;
            try {
                localStorage.setItem('feexray-theme', themeSelect.value);
            } catch {}
        });
    </script>
</body>
</html>
