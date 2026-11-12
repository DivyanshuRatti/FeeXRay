
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Log in to FeeXRay and manage your investment portfolio.">
    <title>Login | FeeXRay</title>
    <link rel="stylesheet" href="/FeeXRay/public/assets/css/login.css">
</head>
<body>

<header class="site-header">
    <nav class="navbar">
        <a href="/FeeXRay/public/index.php" class="logo">Fee<span>X</span>Ray</a>

        <div class="nav-right">
            <span>New to FeeXRay?</span>
            <a href="/FeeXRay/public/signup.php" class="signup-link">Get Started</a>
        </div>
    </nav>
</header>

<main class="login-layout">

    <section class="login-intro">
        <div class="intro-content">
            <span class="eyebrow">WELCOME BACK TO FEEXRAY</span>

            <h1>See your money <span>clearly.</span></h1>

            <p>
                Your investments deserve transparency. Log in to explore
                your portfolio, understand your fees, and make more
                informed financial decisions.
            </p>

            <div class="benefits">
                <div class="benefit">
                    <span class="benefit-icon">✓</span>
                    <div>
                        <h3>Understand your investment fees</h3>
                        <p>See how management expense ratios affect your portfolio.</p>
                    </div>
                </div>

                <div class="benefit">
                    <span class="benefit-icon">✓</span>
                    <div>
                        <h3>Your portfolio, one dashboard</h3>
                        <p>Keep track of your investments and estimated costs.</p>
                    </div>
                </div>

                <div class="benefit">
                    <span class="benefit-icon">✓</span>
                    <div>
                        <h3>Plan with confidence</h3>
                        <p>Explore how fees could affect your future returns.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="login-form-section">
        <div class="login-card">
            <div class="form-heading">
                <span class="eyebrow">YOUR ACCOUNT</span>
                <h2>Welcome back</h2>
                <p>Enter your details to log in to FeeXRay.</p>
            </div>

            <?php if (isset($_GET['registered'])): ?>
                <div class="success-box" role="status">
                    Account created successfully. You can log in now.
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error-box" role="alert">
                    <strong>Unable to log in:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/FeeXRay/public/login.php">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" class="login-btn">
                    Log In <span aria-hidden="true">→</span>
                </button>

            </form>

            <p class="signup-text">
                Don't have an account?
                <a href="/FeeXRay/public/signup.php">Create an account</a>
            </p>

            <p class="disclaimer">
                FeeXRay is an educational investment analysis platform
                and does not provide financial advice.
            </p>
        </div>
    </section>

</main>

</body>
</html>
