<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add portfolio | FeeXRay</title>
</head>
<body>

    <main>
        <h1>Add a portfolio</h1>

        <?php
        if (!empty($errors)) {
            echo '<ul>';
            foreach ($errors as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
        }
        ?>

        <form method="POST" action="/portfolio-add.php">
            <label for="name">Portfolio name</label>
            <input type="text" id="name" name="name" placeholder="My TFSA" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="account_type">Account type</label>
            <select id="account_type" name="account_type" required>
                <option value="">Choose one</option>
                <option value="TFSA">TFSA</option>
                <option value="RRSP">RRSP</option>
                <option value="PENSION">Pension</option>
                <option value="NON_REG">Non-registered</option>
            </select>

            <button type="submit">Save</button>
        </form>

        <p><a href="/dashboard.php">Back to dashboard</a></p>
    </main>

</body>
</html>