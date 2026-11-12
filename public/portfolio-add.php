<?php
require __DIR__ .'/../src/database.php';
require __DIR__ .'/../src/auth.php';
requireLogin();

$name='';
$errors=[];
$allowedTypes=['TFSA','RRSP','PENSION','NON_REG'];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name=trim($_POST['name']?? '');
    $accountType=$_POST['account_type'] ?? '';


if($name===''){
    $errors[] = 'Please enter a portfolio name.';
}
if(strlen($name)>100){
    $errors[] = 'Name must be 100 characters or less.';
}
if(!in_array($accountType, $allowedTypes)){
    $errors[] = 'Please choose an account type.';
}

if(empty($errors)){
    $pdo=getConnection();
    $stmt=$pdo->prepare('INSERT INTO portfolios (user_id,name, account_type) VALUES (?,?,?)');
    $stmt->execute([$_SESSION['user_id'],$name,$accountType]);

    header('Location: /portfolios.php');
        exit;
}
}
require __DIR__ . '/../views/portfolios/add.php';
