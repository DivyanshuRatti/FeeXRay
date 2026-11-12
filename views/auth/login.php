<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FeeXRay</title>
</head>
<body>
    <header>
        <a href="/">FeeXRay</a>
        <nav>
            <a href="/">Home</a>
            <a href="/contact.php">Contact</a>
        </nav>
        <div>
            <a href="/login.php">Login</a>
            <a href="/signup.php">Sign Up</a>
        </div>
    </header>

    <main>
        <h1>Log In</h1>
        <?php if(isset($_GET['registered'])){
            echo'<p> Account created. You can log in now.</p>';
        }
        
        if(!empty($errors)){
            echo '<ul>';
            foreach($errors as $error){
                echo '<li>'.htmlspecialchars($error). '</li>';
            }
            echo '</ul>';
        }
        ?>

        <form method="POST" action="/login.php">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Log In</button>
        </form>

        <p>Don't have an account? <a href="/signup.php">Sign UP</a></p>
    </main>
</body>
</html>