<?php
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/auth.php';
requireLogin();

// Which portfolio? Read the id from the URL
$id = (int) ($_GET['id'] ?? 0);

// Find it, but only if it belongs to this user
$pdo = getConnection();
$stmt = $pdo->prepare('SELECT id, name, account_type, created_at FROM portfolios WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
$portfolio = $stmt->fetch();

// Not found, or not theirs
if (!$portfolio) {
    http_response_code(404);
    echo 'Portfolio not found.';
    exit;
}

require __DIR__ . '/../views/portfolios/show.php';