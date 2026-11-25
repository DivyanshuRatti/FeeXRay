<?php
$accountLabels = [
    'TFSA' => 'Tax-Free Savings Account',
    'RRSP' => 'Registered Retirement Savings Plan',
    'RESP' => 'Registered Education Savings Plan',
    'PENSION' => 'Pension',
    'NON_REG' => 'Non-registered',
];
$accountTotals = array_fill_keys(array_keys($accountLabels), ['amount' => 0, 'fees' => 0]);
$totalInvested = 0;
$totalFees = 0;

foreach ($holdings as $holding) {
    $fee = $holding['amount'] * $holding['mer'];
    $accountTotals[$holding['account']]['amount'] += $holding['amount'];
    $accountTotals[$holding['account']]['fees'] += $fee;
    $totalInvested += $holding['amount'];
    $totalFees += $fee;
}

$name = trim($_SESSION['name'] ?? '') ?: 'Investor';
$firstName = explode(' ', $name, 2)[0];
$money = static fn ($amount) => '$' . number_format($amount, 2);
$percent = $totalInvested > 0 ? number_format(100 * $totalFees / $totalInvested, 2) : '0.00';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <title>Dashboard | FeeXRay</title>
    <script>
        let initialTheme = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        try {
            const savedTheme = localStorage.getItem('feexray-theme');
            if (savedTheme === 'light' || savedTheme === 'dark') initialTheme = savedTheme;
        } catch {}
        document.documentElement.dataset.theme = initialTheme;
    </script>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
    <script defer src="assets/js/dashboard.js"></script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to dashboard</a>
    <aside class="sidebar glass" aria-label="Sidebar">
        <a class="brand" href="dashboard.php">
            <span class="brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
            <span>Fee<span class="brand-accent">X</span>Ray</span>
        </a>
        <nav class="navigation" aria-label="Main navigation">
            <a href="dashboard.php" aria-current="page"><span class="nav-icon" aria-hidden="true">⌂</span> Home</a>
            <a href="portfolios.php"><span class="nav-icon" aria-hidden="true">▤</span> Portfolio</a>
            <a href="settings.php"><span class="nav-icon" aria-hidden="true">⚙</span> Settings</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="profile">
                <span class="avatar" aria-hidden="true">●</span>
                <span class="profile-name"><?= htmlspecialchars($name) ?></span>
            </div>
            <a class="logout" href="logout.php"><span class="nav-icon" aria-hidden="true">⇥</span> Logout</a>
        </div>
    </aside>

    <main id="main" class="workspace">
        <header class="page-header">
            <div>
                <p class="overline">Portfolio overview</p>
                <h1>Welcome, <?= htmlspecialchars($firstName) ?>.</h1>
            </div>
            <div class="header-actions">
                <button class="theme-toggle glass" id="theme-toggle" type="button" aria-pressed="false">
                    <span id="theme-icon" aria-hidden="true">☾</span> Dark mode
                </button>
                <a class="button" href="portfolio-add.php">+ Add portfolio</a>
            </div>
        </header>

        <section class="overview" aria-labelledby="overview-title">
            <div class="overview-art" id="overview-art" aria-hidden="true"><span></span></div>
            <div class="overview-content">
                <h2 id="overview-title">Total invested</h2>
                <p class="hero-value"><?= $money($totalInvested) ?></p>
                <dl class="overview-metrics">
                    <div><dt>Estimated annual MER fees</dt><dd><?= $money($totalFees) ?></dd></div>
                    <div><dt>Weighted MER</dt><dd><?= $percent ?>%</dd></div>
                </dl>
            </div>
        </section>

        <section class="accounts" aria-labelledby="accounts-title">
            <div class="section-heading">
                <h2 id="accounts-title">Your accounts</h2>
                <div class="account-control">
                    <label for="account-select">View</label>
                    <select id="account-select">
                        <option value="all">All accounts</option>
                        <option value="TFSA">TFSA</option>
                        <option value="RRSP">RRSP</option>
                        <option value="RESP">RESP</option>
                        <option value="PENSION">Pension</option>
                        <option value="NON_REG">Non-registered</option>
                    </select>
                </div>
            </div>
            <div class="account-grid">
                <?php foreach (['TFSA', 'RRSP', 'RESP'] as $type): ?>
                    <button class="account-card glass" type="button" data-account="<?= $type ?>" aria-pressed="false">
                        <span class="account-tag"><?= $type ?></span>
                        <span class="account-name"><?= $accountLabels[$type] ?></span>
                        <strong><?= $money($accountTotals[$type]['amount']) ?></strong>
                        <span class="account-fee"><b><?= $money($accountTotals[$type]['fees']) ?></b> estimated MER / year</span>
                    </button>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="analytics">
            <section class="panel glass trend" aria-labelledby="trend-title">
                <div class="panel-heading">
                    <div>
                        <h2 id="trend-title">MER over time</h2>
                        <p>Projected cumulative fund fees</p>
                    </div>
                    <span class="line-key"><i></i> MER paid</span>
                </div>
                <div class="chart-frame line-frame">
                    <canvas id="fee-chart" role="img" aria-label="Projected cumulative MER fees by year"></canvas>
                    <p id="fee-empty" class="chart-empty" hidden>Add holdings to see projected fees.</p>
                </div>
                <p id="chart-status" class="chart-status" hidden>Charts are unavailable. The holdings and fee estimates are still shown below.</p>
            </section>

            <section class="panel glass allocation" aria-labelledby="allocation-title">
                <div class="panel-heading">
                    <div>
                        <h2 id="allocation-title">Wealth allocation</h2>
                        <p>Share of invested value by holding</p>
                    </div>
                </div>
                <div class="chart-frame donut-frame" id="donut-frame">
                    <canvas id="allocation-chart" role="img" aria-label="Allocation by holding"></canvas>
                    <div class="donut-center" aria-hidden="true"><small>Total invested</small><strong id="allocation-total"></strong></div>
                </div>
                <ul id="allocation-legend" class="allocation-legend" aria-label="Allocation breakdown"></ul>
                <p id="allocation-empty" class="allocation-empty" hidden>Add holdings to see your allocation.</p>
            </section>

            <section class="panel glass holdings" aria-labelledby="holdings-title">
                <div class="panel-heading">
                    <div>
                        <h2 id="holdings-title">Holdings</h2>
                        <p id="holdings-scope">All accounts</p>
                    </div>
                    <a class="text-link" href="portfolios.php">Manage portfolios</a>
                </div>
                <div class="table-wrap" id="holdings-table" hidden>
                    <table>
                        <thead><tr><th scope="col">Holding</th><th scope="col">Value</th><th scope="col">MER</th><th scope="col">Annual fee</th></tr></thead>
                        <tbody id="holdings-body"></tbody>
                    </table>
                </div>
                <p class="holdings-empty" id="holdings-empty" hidden>Add a holding from your portfolio to see it here.</p>
            </section>

            <section class="panel glass horizon" aria-labelledby="horizon-title">
                <div class="panel-heading">
                    <div>
                        <h2 id="horizon-title">Time horizon</h2>
                        <p>See how MER fees add up</p>
                    </div>
                </div>
                <div class="projection">
                    <span>Estimated fees paid</span>
                    <strong id="projected-cost">$0.00</strong>
                </div>
                <div class="slider-label">
                    <label for="horizon-years">Investment period</label>
                    <output id="year-label" for="horizon-years">20 years</output>
                </div>
                <input id="horizon-years" type="range" min="1" max="50" value="20">
                <div class="range-labels" aria-hidden="true"><span>1 year</span><span>50 years</span></div>
                <p class="assumptions">Assumes a 5% annual return before fees, constant MERs, and no deposits or withdrawals. Each year’s fee is estimated from the opening balance.</p>
            </section>
        </div>
        <section class="panel glass savings" aria-labelledby="savings-title">
            <div class="panel-heading">
                <div>
                    <h2 id="savings-title">Lower-cost options</h2>
                    <p id="savings-period">Estimated fees over 20 years</p>
                </div>
            </div>
            <ul id="savings-list" class="savings-list"></ul>
            <p id="savings-total" class="savings-total"></p>
            <p id="savings-empty" class="holdings-empty" hidden></p>
            <p class="assumptions">Compares each fund with the lowest-cost fund in the same category on our list. Funds in the same category can still differ in holdings, risk and strategy. Estimate only, not financial advice.</p>
        </section>
        <noscript><p class="glass noscript">Enable JavaScript to view charts, holdings, and projections.</p></noscript>
    </main>

    <script type="application/json" id="dashboard-data"><?= json_encode($holdings, JSON_HEX_TAG | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR) ?></script>
</body>
</html>