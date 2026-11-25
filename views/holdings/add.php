<?php
$pageTitle = 'Add a holding';
require __DIR__ . '/../portfolios/header.php';
$portfolioId = (int) $portfolio['id'];
?>

<main id="main" class="portfolio-main portfolio-main-narrow">
    <a class="portfolio-back" href="/portfolio-view.php?id=<?= $portfolioId ?>">Back to portfolio</a>
    <div class="portfolio-heading">
        <h1>Add a holding</h1>
    </div>

    <section class="portfolio-panel glass" aria-label="Holding details">
        <div class="holding-destination">
            <span>Adding to</span>
            <strong><?= htmlspecialchars($portfolio['name']) ?></strong>
        </div>

        <?php if (!empty($errors)): ?>
            <ul class="portfolio-errors" role="alert">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <!-- Keep the existing save route, fields and CSRF token. -->
        <form class="portfolio-form" id="holding-form" method="POST"
            action="/holding-add.php?portfolio_id=<?= $portfolioId ?>" data-portfolio-id="<?= $portfolioId ?>">
            <?php csrfField(); ?>

            <label for="fund_id">
                Fund
                <select id="fund_id" name="fund_id" required>
                    <option value="">Choose a fund</option>
                    <?php foreach ($funds as $fund): ?>
                        <option value="<?= (int) $fund['id'] ?>" <?= (string) $choice === (string) $fund['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($fund['name']) ?> · <?= number_format($fund['mer'] * 100, 2) ?>% MER
                        </option>
                    <?php endforeach; ?>
                    <option value="custom" <?= $choice === 'custom' ? 'selected' : '' ?>>My fund isn’t listed</option>
                </select>
            </label>

            <div id="custom-fields" class="holding-custom-fields" <?= $choice === 'custom' ? '' : 'hidden' ?>>
                <label for="custom_name">
                    Fund name
                    <input type="text" id="custom_name" name="custom_name" value="<?= htmlspecialchars($customName) ?>">
                </label>
                <label for="custom_mer">
                    MER (%)
                    <input type="text" id="custom_mer" name="custom_mer" inputmode="decimal" placeholder="2.25"
                        value="<?= htmlspecialchars($customMer ?? '') ?>">
                </label>
            </div>

            <label for="amount">
                Amount invested
                <span class="holding-amount">
                    <span aria-hidden="true">$</span>
                    <input type="text" id="amount" name="amount" inputmode="decimal" placeholder="10,000.00"
                        value="<?= htmlspecialchars($amount) ?>" aria-label="Amount invested in dollars" required>
                </span>
            </label>

            <div class="portfolio-form-actions">
                <button class="button" type="submit">Add holding</button>
                <a class="portfolio-link" href="/portfolio-view.php?id=<?= $portfolioId ?>">Cancel</a>
            </div>
        </form>
    </section>
</main>

<script src="assets/js/holdings.js" defer></script>
</body>
</html>
