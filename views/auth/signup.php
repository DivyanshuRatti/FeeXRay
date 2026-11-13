
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Create your FeeXRay account and start understanding your investment fees.">
    <title>Create Account | FeeXRay</title>
    <link rel="stylesheet" href="/assets/css/signup.css">
</head>
<body>

<header class="site-header">
    <nav class="navbar">
        <a href="/FeeXRay/public/index.php" class="logo">Fee<span>X</span>Ray</a>
        <div class="nav-right">
            <span>Already have an account?</span>
            <a href="/FeeXRay/views/login.php" class="login-btn">Log In</a>
        </div>
    </nav>
</header>

<main class="signup-layout">
    <section class="signup-intro">
        <div class="intro-content">
            <span class="eyebrow">YOUR FINANCIAL CLARITY STARTS HERE</span>

            <h1>Invest with <span>confidence.</span></h1>

            <p>
                Create your free FeeXRay account to understand your investment
                fees, explore your portfolio, and see how small costs can
                impact your long-term returns.
            </p>

            <div class="benefits">
                <div class="benefit">
                    <span class="benefit-icon">✓</span>
                    <div>
                        <h3>See the real cost of investing</h3>
                        <p>Understand MER fees across your portfolio.</p>
                    </div>
                </div>

                <div class="benefit">
                    <span class="benefit-icon">✓</span>
                    <div>
                        <h3>Keep your investments organized</h3>
                        <p>View your accounts and holdings in one place.</p>
                    </div>
                </div>

                <div class="benefit">
                    <span class="benefit-icon">✓</span>
                    <div>
                        <h3>Plan for your financial future</h3>
                        <p>Explore the long-term impact of investment fees.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="signup-form-section">
        <div class="signup-card">
            <div class="form-heading">
                <span class="eyebrow">GET STARTED</span>
                <h2>Create your account</h2>
                <p>Enter your details to join FeeXRay.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="error-box" role="alert">
                    <strong>Please correct the following:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/signup.php" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter your full name"
                        value="<?= htmlspecialchars($name ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        autocomplete="name"
                        maxlength="150"
                        required
                    >
                </div>

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
                        placeholder="Create a strong password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                    <small>At least 8 characters, including uppercase, lowercase and a number.</small>
                </div>

                <div class="form-group">
                    <label for="confirm">Confirm Password</label>
                    <input
                        type="password"
                        id="confirm"
                        name="confirm"
                        placeholder="Confirm your password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <button type="submit" class="signup-btn">
                    Create Account <span aria-hidden="true">→</span>
                </button>
            </form>

            <p class="signin-text">
                Already have an account?
                <a href="/login.php">Log in</a>
            </p>

            <p class="disclaimer">
                FeeXRay is an educational investment analysis platform,
                not a financial advisory service.
            </p>
        </div>
    </section>
</main>

</body>
</html>
