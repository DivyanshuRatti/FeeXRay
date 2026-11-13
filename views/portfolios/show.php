<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($portfolio['name']); ?> | FeeXRay</title>
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
        <p><a href="/portfolios.php">Back to portfolios</a></p>

        <h1><?php echo htmlspecialchars($portfolio['name']); ?></h1>
        <p><?php echo htmlspecialchars($portfolio['account_type']); ?></p>
        <form method="POST" action="/portfolio-delete.php" onsubmit="return confirm('Delete this portfolio and all its holdings?');">
            <input type="hidden" name="id" value="<?php echo $portfolio['id']; ?>">
            <button type="submit">Delete portfolio</button>
        </form>

        <section>
            <h2>Holdings</h2>
            <p>No holdings yet. Holdings come in the next feature.</p>
        </section>
    </main>

</body>
</html>