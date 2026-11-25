<?php
$pageTitle = $portfolio['name'];
require __DIR__ . '/header.php';
?>

<main id="main" class="portfolio-main">
    <a class="portfolio-back" href="/portfolios.php">Back to portfolios</a>
    <?php if (!empty($holdings)): ?>
        <p id="holding-success" class="holding-success glass" role="status" data-portfolio-id="<?= (int) $portfolio['id'] ?>" hidden>
            <span aria-hidden="true">✓</span>
            <span>Holding added to <strong><?= htmlspecialchars($portfolio['name']) ?></strong>
                (<?= htmlspecialchars($accountLabels[$portfolio['account_type']] ?? $portfolio['account_type']) ?>).</span>
        </p>
    <?php endif; ?>
    <div class="portfolio-hero">
        <span class="portfolio-account"><?= htmlspecialchars($accountLabels[$portfolio['account_type']] ?? $portfolio['account_type']) ?></span>
        <h1><?= htmlspecialchars($portfolio['name']) ?></h1>

        <!-- The detail controller supplies these totals. -->
        <?php if (!empty($holdings)): ?>
            <dl class="portfolio-totals">
                <div><dt>Total invested</dt><dd>$<?= number_format($totalAmount, 2) ?></dd></div>
                <div><dt>Yearly fees</dt><dd>$<?= number_format($totalFee, 2) ?></dd></div>
                <div><dt>Average MER</dt><dd><?= number_format($averageMer * 100, 2) ?>%</dd></div>
            </dl>
        <?php endif; ?>
    </div>

    <section class="portfolio-panel glass" aria-labelledby="holdings-title">
        <div class="portfolio-section-heading">
            <h2 id="holdings-title">Holdings</h2>
            <a class="button" href="/holding-add.php?portfolio_id=<?= (int) $portfolio['id'] ?>">Add holding</a>
        </div>

        <?php if (empty($holdings)): ?>
            <p class="portfolio-empty-holdings">No holdings yet. Add one to see what you pay in fees.</p>
        <?php else: ?>
            <div class="portfolio-table-wrap" tabindex="0" role="region" aria-label="Holdings table">
                <table class="portfolio-table">
                    <thead>
                        <tr>
                            <th scope="col">Fund</th>
                            <th scope="col">Amount</th>
                            <th scope="col">MER</th>
                            <th scope="col">Yearly fee</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($holdings as $h): ?>
                            <tr>
                                <th scope="row">
                                    <?= htmlspecialchars($h['name']) ?>
                                    <?php if ($h['is_custom']): ?><small class="portfolio-custom">Custom</small><?php endif; ?>
                                </th>
                                <td>$<?= number_format($h['amount'], 2) ?></td>
                                <td><?= number_format($h['mer'] * 100, 2) ?>%</td>
                                <td>$<?= number_format($h['yearly_fee'], 2) ?></td>
                                <td>
                                    <form method="POST" action="/holding-delete.php" onsubmit="return confirm('Delete this holding?');">
                                        <?php csrfField(); ?>
                                        <input type="hidden" name="id" value="<?= (int) $h['id'] ?>">
                                        <button class="portfolio-delete" type="submit" aria-label="Delete <?= htmlspecialchars($h['name']) ?>">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <!-- Keep destructive actions separate from the holdings controls. -->
    <div class="portfolio-footer">
        <form method="POST" action="/portfolio-delete.php" onsubmit="return confirm('Delete this portfolio and all its holdings?');">
            <?php csrfField(); ?>
            <input type="hidden" name="id" value="<?= (int) $portfolio['id'] ?>">
            <button class="portfolio-delete" type="submit">Delete portfolio</button>
        </form>
    </div>
</main>

<script src="assets/js/holdings.js" defer></script>
</body>
</html>
