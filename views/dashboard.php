<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | FeeXRay</title>
    <style>/*for reference*/
    body { display: flex; margin: 0; }
    aside { width: 200px; min-height: 100vh; padding: 20px; border-right: 1px solid #ccc; display: flex; flex-direction: column; gap: 20px; }
    aside nav { display: flex; flex-direction: column; gap: 10px; }
    main { flex: 1; padding: 20px; }
</style>
</head>
<body>

    <!-- Sidebar menu -->
    <aside>
        <a href="/dashboard.php">FeeXRay</a>
        <nav>
            <a href="/dashboard.php">Dashboard</a>
            <a href="/portfolios.php">Portfolios</a>
            <a href="/holding-add.php">Add holding</a>
        </nav>
        <a href="/logout.php">Log out</a>
    </aside>

    <!-- Main area -->
    <main>
        <h1>Hi, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>

        <?php
        if (empty($portfolios)) {
            // New user with no portfolios yet
            echo '<section>';
            echo '<h2>Add your first portfolio</h2>';
            echo '<p>Add an account like a TFSA or RRSP to see what you pay in fees.</p>';
            echo '<a href="/portfolio-add.php">Add portfolio</a>';
            echo '</section>';
        } else {
        ?>

            <!-- Summary cards -->
            <section>
                <div>
                    <p>Total invested</p>
                    <p>$<?php echo number_format($summary['total_invested']); ?></p>
                </div>
                <div>
                    <p>Yearly fees</p>
                    <p>$<?php echo number_format($summary['yearly_fees']); ?></p>
                </div>
                <div>
                    <p>Average MER</p>
                    <p><?php echo number_format($summary['average_mer'] * 100, 2); ?>%</p>
                </div>
            </section>

            <!-- Chart (filled in later with Chart.js) -->
            <section>
                <h2>With fees vs low-cost</h2>
                <canvas id="fee-chart"></canvas>
            </section>

            <!-- Portfolio list -->
            <section>
                <h2>Your portfolios</h2>
                <a href="/portfolio-add.php">Add portfolio</a>

                <?php
                foreach ($portfolios as $p) {
                    echo '<div>';
                    echo '<p>' . htmlspecialchars($p['name']) . '</p>';
                    echo '<p>' . htmlspecialchars($p['account_type']) . '</p>';
                    echo '<p>$' . number_format($p['value']) . '</p>';
                    echo '<p>$' . number_format($p['yearly_fee']) . ' a year</p>';
                    echo '<a href="/portfolio-view.php?id=' . $p['id'] . '">View</a>';
                    echo '</div>';
                }
                ?>
            </section>

        <?php
        }
        ?>
    </main>

</body>
</html>