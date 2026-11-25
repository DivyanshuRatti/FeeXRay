<?php
$pageTitle = 'Add a portfolio';
require __DIR__ . '/header.php';
?>

<main id="main" class="portfolio-main portfolio-main-narrow">
    <a class="portfolio-back" href="/portfolios.php">Back to portfolios</a>
    <div class="portfolio-heading">
        <h1>Add a portfolio</h1>
        <p>Give your portfolio a name and choose its account type.</p>
    </div>

    <section class="portfolio-panel glass" aria-label="New portfolio details">
        <?php if (!empty($errors)): ?>
            <ul class="portfolio-errors" role="alert">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form class="portfolio-form" method="POST" action="/portfolio-add.php">
            <?php csrfField(); ?>
            <label for="name">
                Portfolio name
                <input type="text" id="name" name="name" placeholder="My TFSA" value="<?= htmlspecialchars($name) ?>" required>
            </label>

            <label for="account_type">
                Account type
                <select id="account_type" name="account_type" required>
                    <option value="">Choose one</option>
                    <option value="TFSA" <?= ($accountType ?? '') === 'TFSA' ? 'selected' : '' ?>>TFSA</option>
                    <option value="RRSP" <?= ($accountType ?? '') === 'RRSP' ? 'selected' : '' ?>>RRSP</option>
                    <option value="RESP" <?= ($accountType ?? '') === 'RESP' ? 'selected' : '' ?>>RESP</option>
                    <option value="PENSION" <?= ($accountType ?? '') === 'PENSION' ? 'selected' : '' ?>>Pension</option>
                    <option value="NON_REG" <?= ($accountType ?? '') === 'NON_REG' ? 'selected' : '' ?>>Non-registered</option>
                </select>
            </label>

            <div class="portfolio-form-actions">
                <button class="button" type="submit">Save portfolio</button>
                <a class="portfolio-link" href="/portfolios.php">Cancel</a>
            </div>
        </form>
    </section>
</main>

</body>
</html>
