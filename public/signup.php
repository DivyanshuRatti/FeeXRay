<?php
require __DIR__ . '/../src/database.php';

$errors = [];
$name = '';
$email = '';

// Runs only when the Sign Up button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Read what the user typed
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    // Check the input
    if ($name === '') {
        $errors[] = 'Please enter your name.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must include at least one uppercase letter.';
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must include at least one lowercase letter.';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must include at least one number.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    // No errors: save the user (next step)
    if (empty($errors)) {
       $pdo = getConnection();

       //check if the email is already used
       $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
       $stmt->execute([$email]);

       if($stmt->fetch()){
        $errors[] = 'An account with this email already exists.';
       }
       else{
        //Scramble the password and then save the user
        $hash=password_hash($password,PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?,?,?) ');
        $stmt->execute([$name,$email,$hash]);

        //Go to the login page after successful entry into db
        header('Location: /login.php?registered=1');
        exit;
       }
    }
}

// Show the sign-up page
require __DIR__ . '/../views/auth/signup.php';