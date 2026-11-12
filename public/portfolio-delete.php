<?php
require __DIR__ . '/../src/database.php';
require __DIR__ . '/../src/auth.php';
requireLogin();

// Only allow form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /portfolios.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

// Delete it, but only if it belongs to this user
$pdo = getConnection();
$stmt = $pdo->prepare('DELETE FROM portfolios WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);

header('Location: /portfolios.php');
exit;