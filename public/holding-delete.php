<?php
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/auth.php';
require __DIR__ . '/../src/csrf.php';
requireLogin();

// Only allow form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /portfolios.php');
    exit;
}

// Block forms that didn't come from a FeeXRay page
verifyCsrf();

$holdingId = (int) ($_POST['id'] ?? 0);
$pdo = getConnection();

// Find the holding, but only if its portfolio belongs to this user
$stmt = $pdo->prepare('
    SELECT h.portfolio_id
    FROM holdings h
    JOIN portfolios p ON h.portfolio_id = p.id
    WHERE h.id = ? AND p.user_id = ?
');
$stmt->execute([$holdingId, $_SESSION['user_id']]);
$holding = $stmt->fetch();

// Not found or not theirs: do nothing, go back to the list
if (!$holding) {
    header('Location: /portfolios.php');
    exit;
}

// It's theirs: delete it
$stmt = $pdo->prepare('DELETE FROM holdings WHERE id = ?');
$stmt->execute([$holdingId]);

// Back to the same portfolio page
header('Location: /portfolio-view.php?id=' . $holding['portfolio_id']);
exit;