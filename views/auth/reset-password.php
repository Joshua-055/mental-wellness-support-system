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

    <section class="login-shell password-reset-shell" aria-labelledby="reset-title">
        <section class="login-card glass-surface" aria-label="Choose a new password">
            <?php if ($tokenIsValid): ?>
                <div class="card-heading">
                    <p class="overline">SECURE PASSWORD RESET</p>
                    <h2 id="reset-title">Choose a new password</h2>
                    <p>Use at least 8 characters and choose something unique to this account.</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="auth-alert error" role="alert">
                        <?php foreach ($errors as $error): ?><p><?= escape((string) $error) ?></p><?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form class="login-form" method="post" action="<?= BASE_URL ?>/auth/reset-password.php">
                    <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">
                    <input type="hidden" name="token" value="<?= escape($token) ?>">
                    <div class="field-group">
                        <label for="password">New password</label>
                        <div class="input-wrap">
                            <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" placeholder="At least 8 characters" required>
                            <button class="password-toggle" type="button" aria-label="Show password" aria-pressed="false">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="field-group">
                        <label for="confirm_password">Confirm new password</label>
                        <div class="input-wrap">
                            <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" minlength="8" placeholder="Repeat your new password" required>
                            <button class="password-toggle" type="button" aria-label="Show password" aria-pressed="false">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            </button>
                        </div>
                    </div>
                    <button class="sign-in-button" type="submit"><span>Update password</span><span aria-hidden="true">→</span></button>
                </form>
            <?php else: ?>
                <div class="reset-status-icon" aria-hidden="true">!</div>
                <div class="card-heading">
                    <p class="overline">LINK UNAVAILABLE</p>
                    <h2 id="reset-title">This link is no longer valid</h2>
                    <p>It may have expired or already been used. Request a new link to continue.</p>
                </div>
                <a class="sign-in-button" href="<?= BASE_URL ?>/auth/forgot-password.php"><span>Request a new link</span><span aria-hidden="true">→</span></a>
            <?php endif; ?>

            <p class="auth-switch"><a href="<?= BASE_URL ?>/">← Back to sign in</a></p>
        </section>
    </section>
</main>
