<?php
require __DIR__ .'/../src/Database.php';
require __DIR__ .'/../src/auth.php';
require __DIR__ .'/../src/csrf.php';
requireLogin();

$pdo=getConnection();

//Find the portfolio. It must belong to the logged in user
$portfolioId = (int) ($_GET['portfolio_id'] ?? 0);

$stmt = $pdo->prepare('SELECT id, name FROM portfolios WHERE id = ? and user_id = ?');
$stmt->execute([$portfolioId,$_SESSION['user_id']]);
$portfolio = $stmt->fetch();

if(!$portfolio){
    http_response_code(404);
    echo 'Portfolio not found.';
    exit;
}

//Funds for the dropdown menu along with the list of their ids to check the user's choice
$funds = $pdo->query('SELECT id, name, mer FROM funds ORDER BY name')->fetchAll();
$fundsIds=array_column($funds,'id');

$errors = [];
$choice = '';
$customName = '';
$customMer = '';
$amount = '';

//Runs only when the save button is clicked
if($_SERVER['REQUEST_METHOD']==='POST'){
    verifyCsrf();

    $choice = $_POST['fund_id'] ?? '';
    $customName = trim($_POST['custom_name'] ?? '');
    $customMer = trim($_POST['custom_mer'] ?? '');
    $amount = str_replace(',','', trim($_POST['amount'] ?? '' )); //allow 10,000 to be 10000


//Amount
if(!is_numeric($amount) || $amount <=0){
    $errors[] = 'Please enter an amount greater than 0.';
}
elseif($amount>9999999999.99){
    $errors[] = 'Amount is too large.';
}

//Fund: custom or listed
if($choice === 'custom'){
    if($customName === ''){
        $errors[] = 'Please enter a fund name.';
    }

    if(strlen($customName)>150){
        $errors[] = 'Fund name must be 150 characters or less.';
    }

    if(!is_numeric($customMer) || $customMer<0 || $customMer >5){
        $errors[] = 'Please enter an MER between 0 and 5 (for example 2.25).';
    }

    
}

else{
    if(!in_array((int)$choice,$fundsIds)){
        $errors[] = 'Please choose a fund.';
    }

    // Safety net in case JavaScript is off: listed fund + custom boxes filled
        if ($customName !== '' || $customMer !== '') {
            $errors[] = 'You picked a fund from the list, so leave Fund name and MER empty.';
}
}

// Save: listed funds store fund_id only, custom funds store name + MER only
    if (empty($errors)) {
        if ($choice === 'custom') {
            $stmt = $pdo->prepare('INSERT INTO holdings (portfolio_id, custom_name, custom_mer, amount) VALUES (?, ?, ?, ?)');
            $stmt->execute([$portfolioId, $customName, $customMer / 100, $amount]); // 2.25 → 0.0225
        } else {
            $stmt = $pdo->prepare('INSERT INTO holdings (portfolio_id, fund_id, amount) VALUES (?, ?, ?)');
            $stmt->execute([$portfolioId, (int) $choice, $amount]);
        }

        header('Location: /portfolio-view.php?id=' . $portfolioId);
        exit;
    }

}
require __DIR__ . '/../views/holdings/add.php';
