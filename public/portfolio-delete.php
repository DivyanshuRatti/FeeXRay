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

$id = (int) ($_POST['id'] ?? 0);

// Delete it, but only if it belongs to this user
$pdo = getConnection();
$stmt = $pdo->prepare('DELETE FROM portfolios WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);

header('Location: /portfolios.php');
exit;