<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your portfolios | FeeXRay</title>
</head>
<body>

    <aside>
        <a href="/dashboard.php">FeeXRay</a>
        <nav>
            <a href="/dashboard.php">Dashboard</a>
            <a href="/portfolios.php">Portfolios</a>
            <a href="/holding-add.php">Add holding</a>
        </nav>
        <a href="/logout.php">Log out</a>
    </aside>

    <main>
        <h1>Your portfolios</h1>
        <a href="/portfolio-add.php">Add portfolio</a>

        <?php
        if (empty($portfolios)) {
            echo '<p>Add your first portfolio to see what you pay in fees.</p>';
        } else {
            foreach ($portfolios as $p) {
                echo '<div>';
                echo '<p>' . htmlspecialchars($p['name']) . '</p>';
                echo '<p>' . htmlspecialchars($p['account_type']) . '</p>';
                echo '<a href="/portfolio-view.php?id=' . $p['id'] . '">View</a>';
                echo '</div>';
            }
        }
        ?>
    </main>

</body>
</html>