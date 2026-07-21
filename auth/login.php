<?php
$pageTitle = 'Welcome Back | Mindful';
$pageStyles = ['login'];
require_once __DIR__ . '/../includes/header.php';
?>

<main class="login-page">
    <div class="ambient ambient-blue" aria-hidden="true"></div>
    <div class="ambient ambient-green" aria-hidden="true"></div>
    <div class="ambient ambient-lilac" aria-hidden="true"></div>

    <nav class="login-nav glass-surface" aria-label="Primary navigation">
        <a class="brand" href="../index.php" aria-label="Mindful home">
            <span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span>
            <span>mindful</span>
        </a>
        <a class="nav-help" href="mailto:wellness@university.edu">Need help?</a>
    </nav>

    <section class="login-shell" aria-labelledby="login-title">
        <div class="welcome-panel">
            <div class="eyebrow"><span class="eyebrow-dot"></span> Student wellness, made simple</div>
            <h1>A calmer space<br>starts here.</h1>
            <p class="welcome-copy">Check in with yourself, discover support and connect with people who care—on your own terms.</p>

            <div class="wellbeing-preview glass-surface" aria-label="Wellness preview">
                <div class="preview-topline">
                    <span>Today’s wellbeing</span>
                    <span class="preview-date">July 21</span>
                </div>
                <div class="mood-row" aria-hidden="true">
                    <span>😊</span><span>🙂</span><span class="active-mood">😌</span><span>😔</span><span>😫</span>
                </div>
                <div class="preview-footer">
                    <span class="pulse-dot"></span>
                    A small check-in can make a difference
                </div>
            </div>
        </div>

        <section class="login-card glass-surface" aria-label="Sign in">
            <div class="card-heading">
                <p class="overline">WELCOME BACK</p>
                <h2 id="login-title">Sign in to Mindful</h2>
                <p>Your private student wellbeing space.</p>
            </div>

            <form id="loginForm" class="login-form" method="post" action="#" novalidate>
                <div class="field-group">
                    <label for="email">University email</label>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 6.5h17v11h-17zM4 7l8 6 8-6"/></svg>
                        <input id="email" name="email" type="email" autocomplete="email" placeholder="you@university.edu" required>
                    </div>
                </div>

                <div class="field-group">
                    <div class="label-row">
                        <label for="password">Password</label>
                        <a href="#">Forgot password?</a>
                    </div>
                    <div class="input-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required>
                        <button class="password-toggle" type="button" aria-label="Show password" aria-pressed="false">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                        </button>
                    </div>
                </div>

                <label class="remember-option">
                    <input type="checkbox" name="remember">
                    <span class="custom-checkbox" aria-hidden="true"></span>
                    Remember me for 30 days
                </label>

                <button class="sign-in-button" type="submit">
                    <span>Sign in securely</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
                <p id="formMessage" class="form-message" role="status" aria-live="polite"></p>
            </form>

            <div class="privacy-note">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3.5 19 6v5.2c0 4.5-2.9 7.7-7 9.3-4.1-1.6-7-4.8-7-9.3V6l7-2.5Z"/><path d="m9 12 2 2 4-4"/></svg>
                Your wellbeing information is private and protected.
            </div>
        </section>
    </section>

    <p class="login-footer">© <?= date('Y') ?> Mindful Student Wellness &nbsp;·&nbsp; <a href="#">Privacy</a></p>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
