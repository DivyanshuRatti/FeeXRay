<?php
session_start();
require __DIR__ . '/../src/database.php';

$errors = [];
$email = '';

//Runs only when the login button is clicked
if($_SERVER['REQUEST_METHOD']==='POST'){
    //read user input
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    //Find user by email
    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT id, name, password_hash, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    //Check password if the user exists
    if($user && password_verify($password,$user['password_hash'])){

        //New Session ID for safety and to remember the user
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        //Take the user to the dashboard
        header('Location: /FeeXRay/public/dashboard.php');
        exit;
    }

    //Send the error message 
    $errors[]='Wrong email or password.';
}

//Show the login page again
require __DIR__ . '/../views/auth/login.php';
?>