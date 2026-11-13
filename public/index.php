
<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$accountUrl = $isLoggedIn ? '/FeeXRay/dashboard.php' : '/FeeXRay/views/auth/signup.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FeeXRay helps investors understand investment fees and their long-term impact.">
    <title>FeeXRay | See Beyond Your Investment Fees</title>
    <link rel="stylesheet" href="/FeeXRay/public/assets/css/home.css">
</head>
<body>

<header class="site-header">
    <nav class="navbar" aria-label="Main navigation">
        <a href="/FeeXRay/" class="logo">Fee<span>X</span>Ray</a>

        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#about">About</a>
        </div>

        <div class="auth-buttons">
            <?php if ($isLoggedIn): ?>
                <a href="/FeeXRay/dashboard.php" class="signup-btn">Dashboard</a>
            <?php else: ?>
                <a href="/FeeXRay/views/login.php" class="login-btn">Login</a>
                <a href="/FeeXRay/views/auth/signup.php" class="signup-btn">Get Started</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main>
    <section class="hero">
        <div class="hero-content">
            <span class="eyebrow">INVEST SMARTER. SEE CLEARER.</span>

            <h1>
                Your Investments.<br>
                <span>Your Fees, Exposed.</span>
            </h1>

            <p>
                Investment fees can quietly reduce your long-term returns.
                FeeXRay helps you understand management expense ratios,
                estimate your costs, and see how fees affect your financial future.
            </p>

            <div class="hero-actions">
                <a href="<?= htmlspecialchars($accountUrl, ENT_QUOTES, 'UTF-8') ?>" class="primary-btn">
                    Scan Your Fees Now <span aria-hidden="true">→</span>
                </a>
                <a href="#how-it-works" class="secondary-btn">See How It Works</a>
            </div>

            <div class="hero-note">
                <span class="check-icon">✓</span> Understand MER costs
                <span class="check-icon">✓</span> Explore long-term impact
            </div>
        </div>

        <div class="hero-visual">
            <div class="visual-label">INVESTMENT FEE ANALYSIS</div>

            <div class="preview-card">
                <div class="preview-top">
                    <div>
                        <span class="preview-subtitle">Sample Portfolio</span>
                        <h3>Fee Overview</h3>
                    </div>
                    <span class="preview-status">Illustration</span>
                </div>

                <div class="preview-value">
                    <span>Portfolio Value</span>
                    <h2>$50,000.00</h2>
                </div>

                <div class="preview-stats">
                    <div class="stat-box">
                        <span>Weighted MER</span>
                        <strong>1.25%</strong>
                    </div>
                    <div class="stat-box">
                        <span>Estimated Annual Fees</span>
                        <strong>$625</strong>
                    </div>
                </div>

                <div class="chart-header">
                    <h4>Fee Impact Over Time</h4>
                    <span>Illustrative scenario</span>
                </div>

                <div class="bar-chart" aria-label="Illustrative comparison of portfolio values after fees">
                    <div class="chart-column">
                        <div class="bar bar-one"></div>
                        <span>10 Years</span>
                    </div>
                    <div class="chart-column">
                        <div class="bar bar-two"></div>
                        <span>20 Years</span>
                    </div>
                    <div class="chart-column">
                        <div class="bar bar-three"></div>
                        <span>30 Years</span>
                    </div>
                </div>

                <div class="chart-legend">
                    <span><i class="legend-dot"></i> Example growth after fees</span>
                </div>

                <div class="preview-footer">
                    <span>Know what your investments cost.</span>
                    <span class="preview-arrow">↗</span>
                </div>
            </div>

            <div class="floating-card">
                <span>Annual Fee Estimate</span>
                <strong>$625 / year</strong>
                <small>Based on the sample portfolio</small>
            </div>
        </div>
    </section>

    <section class="trust-strip">
        <p>CLARITY FOR EVERY TYPE OF INVESTOR</p>
        <div class="account-types">
            <span>TFSA</span>
            <span>RRSP</span>
            <span>RESP</span>
            <span>Non-Registered</span>
        </div>
    </section>

    <section id="how-it-works" class="section how-section">
        <div class="section-heading">
            <span class="eyebrow">SIMPLE BY DESIGN</span>
            <h2>How FeeXRay Works</h2>
            <p>Understand your investment fees in three simple steps.</p>
        </div>

        <div class="steps-grid">
            <article class="step-card">
                <div class="step-number">01</div>
                <div class="feature-icon">▤</div>
                <h3>Enter Your Investments</h3>
                <p>
                    Add your portfolio and investment holdings manually,
                    including their values and management expense ratios.
                </p>
            </article>

            <article class="step-card">
                <div class="step-number">02</div>
                <div class="feature-icon">◎</div>
                <h3>Analyze Your Fees</h3>
                <p>
                    FeeXRay calculates your estimated annual costs and
                    weighted MER to help you understand what you pay.
                </p>
            </article>

            <article class="step-card">
                <div class="step-number">03</div>
                <div class="feature-icon">↗</div>
                <h3>Explore Your Future</h3>
                <p>
                    Compare fee scenarios and see how different investment
                    costs may affect your long-term portfolio growth.
                </p>
            </article>
        </div>
    </section>

    <section id="features" class="section features-section">
        <div class="section-heading">
            <span class="eyebrow">POWERFUL INSIGHTS</span>
            <h2>Everything You Need to See the Bigger Picture</h2>
            <p>Clear information to help you make more informed investment decisions.</p>
        </div>

        <div class="features-grid">
            <article class="feature-card">
                <div class="feature-icon">◉</div>
                <h3>MER Transparency</h3>
                <p>
                    Understand management expense ratios and estimate
                    the costs associated with your investment funds.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">▥</div>
                <h3>Portfolio Dashboard</h3>
                <p>
                    Organize your TFSA, RRSP and other investment accounts
                    in one easy-to-understand dashboard.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">⌁</div>
                <h3>Long-Term Projections</h3>
                <p>
                    Explore how different fee rates and assumed returns
                    could affect your portfolio over time.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">▤</div>
                <h3>Investment Reports</h3>
                <p>
                    Review your portfolio fee estimates and create
                    useful summaries for future reference.
                </p>
            </article>
        </div>
    </section>

    <section id="about" class="section about-section">
        <div class="about-content">
            <span class="eyebrow">WHY FEEXRAY?</span>
            <h2>Small Fees Can Make a Big Difference.</h2>
            <p>
                Investment fees are often expressed as small percentages,
                making their actual cost easy to overlook. Over time,
                those costs can add up.
            </p>
            <p>
                FeeXRay is an educational investment analysis platform
                designed to make those numbers easier to understand.
                We believe investors deserve greater transparency.
            </p>
            <a href="<?= htmlspecialchars($accountUrl, ENT_QUOTES, 'UTF-8') ?>" class="primary-btn">
                Start Exploring <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="about-visual">
            <div class="comparison-card">
                <span class="comparison-label">SAMPLE ANNUAL FEE COMPARISON</span>
                <h3>Same $50,000 Portfolio</h3>

                <div class="comparison-row">
                    <div class="comparison-info">
                        <span>1.25% MER</span>
                        <strong>$625 / year</strong>
                    </div>
                    <div class="comparison-track">
                        <div class="comparison-fill high-fee"></div>
                    </div>
                </div>

                <div class="comparison-row">
                    <div class="comparison-info">
                        <span>0.25% MER</span>
                        <strong>$125 / year</strong>
                    </div>
                    <div class="comparison-track">
                        <div class="comparison-fill low-fee"></div>
                    </div>
                </div>

                <div class="comparison-savings">
                    <span>Difference in estimated annual fees</span>
                    <strong>$500</strong>
                </div>
                <small>Illustrative comparison only. Investments may differ in risk, strategy and performance.</small>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-content">
            <span class="eyebrow">TAKE CONTROL OF YOUR INVESTMENTS</span>
            <h2>It's Time to See Beyond the Numbers.</h2>
            <p>Discover what your investment fees could be costing you.</p>
            <a href="<?= htmlspecialchars($accountUrl, ENT_QUOTES, 'UTF-8') ?>" class="primary-btn">
                Get Started with FeeXRay <span aria-hidden="true">→</span>
            </a>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="footer-main">
        <div class="footer-brand">
            <a href="/FeeXRay/public/index.php" class="logo">Fee<span>X</span>Ray</a>
            <p>Helping investors understand the true cost of investing.</p>
        </div>

        <div class="footer-links">
            <a href="#features">Features</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#about">About</a>
            <a href="/FeeXRay/login.php">Login</a>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> FeeXRay. All rights reserved.</p>
        <p>Developed by Divyanshu Ratti &amp; Krish Nagpal | INFO 3135</p>
    </div>

    <p class="disclaimer">
        FeeXRay is an educational project and does not provide financial advice.
        Calculations are estimates and do not guarantee investment performance.
    </p>
</footer>

</body>
</html>
