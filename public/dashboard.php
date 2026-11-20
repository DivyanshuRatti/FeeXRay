<?php

require __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/Database.php';
requireLogin();

$stmt = getConnection()->prepare(
    'SELECT p.account_type, h.amount, h.fund_id,
            COALESCE(f.name, h.custom_name) AS holding_name,
            COALESCE(f.mer, h.custom_mer) AS mer
     FROM portfolios p
     JOIN holdings h ON h.portfolio_id = p.id
     LEFT JOIN funds f ON f.id = h.fund_id
     WHERE p.user_id = ?
     ORDER BY h.id'
);
$stmt->execute([$_SESSION['user_id']]);
$holdings = [];

foreach ($stmt as $row) {
    $holdings[] = [
        'account' => $row['account_type'],
        'fundId' => $row['fund_id'] === null ? null : (int) $row['fund_id'],
        'name' => $row['holding_name'],
        'amount' => (float) $row['amount'],
        'mer' => (float) $row['mer'],
    ];
}

require __DIR__ . '/../views/dashboard.php';

