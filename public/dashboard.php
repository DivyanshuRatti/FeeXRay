<?php

require __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/Database.php';
requireLogin();

$pdo = getConnection();

// All of this user's holdings, including each fund's category
$stmt = $pdo->prepare(
    'SELECT p.account_type, h.amount, h.fund_id,
            COALESCE(f.name, h.custom_name) AS holding_name,
            COALESCE(f.mer, h.custom_mer) AS mer,
            f.asset_class
     FROM portfolios p
     JOIN holdings h ON h.portfolio_id = p.id
     LEFT JOIN funds f ON f.id = h.fund_id
     WHERE p.user_id = ?
     ORDER BY h.id'
);
$stmt->execute([$_SESSION['user_id']]);

// Cheapest fund in each category.
// Funds come cheapest first, so the first one seen in a category is the cheapest.
$cheapest = [];
$funds = $pdo->query('SELECT id, name, fund_code, asset_class, mer FROM funds WHERE asset_class IS NOT NULL ORDER BY mer, id')->fetchAll();

foreach ($funds as $f) {
    if (!isset($cheapest[$f['asset_class']])) {
        $cheapest[$f['asset_class']] = $f;
    }
}

$holdings = [];

foreach ($stmt as $row) {
    $mer = (float) $row['mer'];
    $category = $row['asset_class'];
    $alternative = null;

    // Custom funds have no category, so they get no suggestion
    if ($category !== null && isset($cheapest[$category])) {
        $alt = $cheapest[$category];

        // Only suggest it if it's cheaper and not the same fund
        if ((float) $alt['mer'] < $mer && (int) $alt['id'] !== (int) $row['fund_id']) {
            $alternative = [
                'name' => $alt['name'],
                'code' => $alt['fund_code'],
                'mer'  => (float) $alt['mer'],
            ];
        }
    }

    $holdings[] = [
        'account'     => $row['account_type'],
        'fundId'      => $row['fund_id'] === null ? null : (int) $row['fund_id'],
        'name'        => $row['holding_name'],
        'amount'      => (float) $row['amount'],
        'mer'         => $mer,
        'category'    => $category,
        'alternative' => $alternative,
    ];
}

require __DIR__ . '/../views/dashboard.php';