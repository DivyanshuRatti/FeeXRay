<?php
require __DIR__ .'/../src/auth.php';
require __DIR__ .'/../src/database.php';
requireLogin();

$pdo=getConnection();
$stmt=$pdo->prepare('SELECT id, name, account_type FROM portfolios WHERE user_id = ? ORDER BY created_at');
$stmt->execute([$_SESSION['user_id']]);
$portfolios=$stmt->fetchAll();

require __DIR__ .'/../views/portfolios/list.php';