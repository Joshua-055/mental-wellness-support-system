<main class="login-page register-page">
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

    <section class="login-shell register-shell" aria-labelledby="register-title">
        <div class="welcome-panel">
            <div class="eyebrow"><span class="eyebrow-dot"></span> Your wellbeing journey</div>
            <h1>A supportive space,<br>made for you.</h1>
            <p class="welcome-copy">Create your private student account to check in, find resources and reach support when you need it.</p>
            <div class="wellbeing-preview glass-surface register-preview">
                <div class="preview-topline"><span>Student account</span><span class="preview-date">Private & protected</span></div>
                <div class="register-benefits"><span>✓ Daily wellness check-ins</span><span>✓ Personal support resources</span><span>✓ Easy appointment booking</span></div>
            </div>
        </div>

        <section class="login-card register-card glass-surface" aria-label="Create student account">
            <div class="card-heading">
                <p class="overline">STUDENT REGISTRATION</p>
                <h2 id="register-title">Create your account</h2>
                <p>Only student accounts can be created here.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="auth-alert error" role="alert">
                    <?php foreach ($errors as $error): ?><p><?= escape((string) $error) ?></p><?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form id="registerForm" class="login-form" method="post" action="<?= BASE_URL ?>/auth/register.php">
                <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">

                <div class="field-group">
                    <label for="full_name">Full name</label>
                    <div class="input-wrap">
                        <span class="text-icon" aria-hidden="true">Aa</span>
                        <input id="full_name" name="full_name" type="text" autocomplete="name" maxlength="100" placeholder="Your full name" value="<?= escape($old['full_name'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="field-group">
                    <label for="email">University email</label>
                    <div class="input-wrap">
                        <span class="text-icon" aria-hidden="true">@</span>
                        <input id="email" name="email" type="email" autocomplete="email" maxlength="150" placeholder="you@university.edu" value="<?= escape($old['email'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="register-fields">
                    <div class="field-group">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" placeholder="At least 8 characters" required>
                            <button class="password-toggle" type="button" aria-label="Show password" aria-pressed="false">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="field-group">
                        <label for="confirm_password">Confirm password</label>
                        <div class="input-wrap">
                            <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" minlength="8" placeholder="Repeat password" required>
                            <button class="password-toggle" type="button" aria-label="Show password" aria-pressed="false">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button class="sign-in-button" type="submit"><span>Create student account</span><span aria-hidden="true">→</span></button>
            </form>

            <p class="auth-switch">Already registered? <a href="<?= BASE_URL ?>/">Sign in</a></p>
            <div class="privacy-note">Student registration cannot create staff or administrator accounts.</div>
        </section>
    </section>
</main>
