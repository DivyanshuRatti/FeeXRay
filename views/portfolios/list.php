<?php
$pageTitle = 'Your portfolios';
$isPortfolioList = true;
require __DIR__ . '/header.php';
?>

<main id="main" class="portfolio-main">
    <div class="portfolio-heading portfolio-heading-row">
        <h1>Your portfolios</h1>
        <a class="button" href="/portfolio-add.php">Add portfolio</a>
    </div>

    <?php if (empty($portfolios)): ?>
        <section class="portfolio-panel portfolio-empty glass">
            <h2>No portfolios yet</h2>
            <p>Add a portfolio to organize your holdings and see what you pay in fees.</p>
        </section>
    <?php else: ?>
        <!-- Each saved portfolio links to its existing detail page. -->
        <ul class="portfolio-list glass" aria-label="Saved portfolios">
            <?php foreach ($portfolios as $p): ?>
                <li class="portfolio-list-row">
                    <div>
                        <h2><?= htmlspecialchars($p['name']) ?></h2>
                        <span class="portfolio-account"><?= htmlspecialchars($accountLabels[$p['account_type']] ?? $p['account_type']) ?></span>
                    </div>
                    <a class="portfolio-secondary" href="/portfolio-view.php?id=<?= (int) $p['id'] ?>"
                        aria-label="View <?= htmlspecialchars($p['name']) ?>">View portfolio</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

</body>
</html>
