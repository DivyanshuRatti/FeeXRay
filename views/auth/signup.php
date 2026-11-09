<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | FeeXRay</title>
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
        <h1>Create your account</h1>

        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST" action="/signup.php">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="8" required>
            <small>At least 8 characters, with an uppercase letter, a lowercase letter and a number.</small>

            <label for="confirm">Confirm password</label>
            <input type="password" id="confirm" name="confirm" minlength="8" required>

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="/login.php">Login</a></p>
    </main>

</body>
</html>