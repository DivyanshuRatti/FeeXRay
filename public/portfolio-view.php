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


//Get this portfolio's holdings.
//LEFT JOIN keeps custom holdings which have no matching fund.
$stmt=$pdo->prepare('
SELECT h.id, h.amount, h.custom_name, h.custom_mer, f.name AS fund_name, f.mer as fund_mer
FROM holdings h
LEFT JOIN funds f on h.fund_id=f.id
WHERE h.portfolio_id = ?
ORDER BY h.id');

$stmt->execute([$portfolio['id']]);
$rows=$stmt->fetchAll();

$holdings = [];
$totalAmount = 0;
$totalFee= 0;

foreach($rows as $row){
    //Listed fund: use the fund's name and MER. Custom: use the custom ones.
    $name=$row['fund_name']??$row['custom_name'];
    $mer = $row['fund_mer']?? $row['custom_mer'];

    $yearlyFee= $row['amount'] * $mer; //e.g 10000*0.0225=225

    $holdings[] = [
        'id' => $row['id'],
        'name' => $name,
        'is_custom' => $row['fund_name'] ===null,
        'amount' => $row['amount'],
        'mer' => $mer,
        'yearly_fee' => $yearlyFee,
    ];

    $totalAmount += $row['amount'];
    $totalFee += $yearlyFee;
}

// Average MER, weighted by how much is in each fund
$averageMer = $totalAmount > 0 ? $totalFee / $totalAmount : 0;
require __DIR__ . '/../views/portfolios/show.php';