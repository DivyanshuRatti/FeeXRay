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
            <a href="/holding-add.php?portfolio_id=<?php echo $portfolio['id']; ?>">Add holding</a>

            <?php
            if (empty($holdings)) {
                echo '<p>No holdings yet. Add one to see what you pay in fees.</p>';
            } else {
                // Summary
                $total = number_format($totalAmount, 2);
                $fees = number_format($totalFee, 2);
                $avg = number_format($averageMer * 100, 2);

                echo "<p>Total invested: $$total</p>";
                echo "<p>Yearly fees: $$fees</p>";
                echo "<p>Average MER: $avg%</p>";

                // One row per holding
                echo '<table>';
                echo '<tr><th>Fund</th><th>Amount</th><th>MER</th><th>Yearly fee</th><th></th></tr>';

                foreach ($holdings as $h) {
                    $name = htmlspecialchars($h['name']);
                    if ($h['is_custom']) {
                        $name = $name . ' (custom)';
                    }
                    $amount = number_format($h['amount'], 2);
                    $mer = number_format($h['mer'] * 100, 2);
                    $fee = number_format($h['yearly_fee'], 2);

                    $id = $h['id'];
                    $deleteForm = "<form method='POST' action='/holding-delete.php' onsubmit=\"return confirm('Delete this holding?');\">"
                                . "<input type='hidden' name='id' value='$id'>"
                                . "<button type='submit'>Delete</button>"
                                . "</form>";

                    echo "<tr><td>$name</td><td>$$amount</td><td>$mer%</td><td>$$fee</td><td>$deleteForm</td></tr>";
                                    
                }
                echo '</table>';
            }
            ?>
        </section>
    </main>

</body>
</html>