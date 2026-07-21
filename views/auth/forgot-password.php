<main class="login-page">
    <div class="ambient ambient-blue" aria-hidden="true"></div>
    <div class="ambient ambient-green" aria-hidden="true"></div>
    <div class="ambient ambient-lilac" aria-hidden="true"></div>

    <nav class="login-nav glass-surface" aria-label="Primary navigation">
        <a class="brand" href="<?= BASE_URL ?>/" aria-label="Mindful home">
            <span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span>
            <span>mindful</span>
        </a>
        <a class="nav-help" href="mailto:wellness@university.edu">Need help?</a>
    </nav>

    <section class="login-shell password-reset-shell" aria-labelledby="forgot-title">
        <section class="login-card glass-surface" aria-label="Reset your password">
            <div class="card-heading">
                <p class="overline">ACCOUNT RECOVERY</p>
                <h2 id="forgot-title">Forgot your password?</h2>
                <p>Enter your university email and we will prepare a secure reset link.</p>
            </div>

            <?php if (!empty($message)): ?>
                <div class="auth-alert success" role="status"><?= escape((string) $message) ?></div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="auth-alert error" role="alert">
                    <?php foreach ($errors as $error): ?><p><?= escape((string) $error) ?></p><?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($previewUrl)): ?>
                <div class="dev-reset-link" role="status">
                    <strong>Local development reset link</strong>
                    Email delivery is not connected yet. <a href="<?= escape((string) $previewUrl) ?>">Continue to reset your password</a>.
                </div>
            <?php endif; ?>

            <form class="login-form" method="post" action="<?= BASE_URL ?>/auth/forgot-password.php">
                <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">
                <div class="field-group">
                    <label for="email">University email</label>
                    <div class="input-wrap">
                        <span class="text-icon" aria-hidden="true">@</span>
                        <input id="email" name="email" type="email" autocomplete="email" maxlength="150" placeholder="you@university.edu" value="<?= escape($email ?? '') ?>" required>
                    </div>
                </div>
                <button class="sign-in-button" type="submit"><span>Prepare reset link</span><span aria-hidden="true">→</span></button>
            </form>

            <p class="auth-switch"><a href="<?= BASE_URL ?>/">← Back to sign in</a></p>
            <div class="privacy-note">For privacy, we show the same response whether or not an email is registered.</div>
        </section>
    </section>
</main>
