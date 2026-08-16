<?php
declare(strict_types=1);

final class PasswordResetController extends Controller
{
    public function request(): void
    {
        requireGuest();

        $errors = [];
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $message = $_SESSION['password_reset_message'] ?? null;
        $previewUrl = $_SESSION['password_reset_preview_url'] ?? null;
        unset($_SESSION['password_reset_message'], $_SESSION['password_reset_preview_url']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
                $errors[] = 'Your session expired. Please try again.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
                $errors[] = 'Enter a valid email address.';
            } else {
                $user = (new User())->findByEmail($email);

                if ($user !== null && (bool) $user['is_active']) {
                    $plainToken = bin2hex(random_bytes(32));
                    $tokenHash = hash('sha256', $plainToken);
                    (new PasswordReset())->createForUser((int) $user['id'], $tokenHash);

                    if (APP_ENV === 'local') {
                        $_SESSION['password_reset_preview_url'] = BASE_URL
                            . '/auth/reset-password.php?token=' . urlencode($plainToken);
                    }
                }

                $_SESSION['password_reset_message'] = 'If an active account matches that email, a password reset link has been prepared.';
                redirectTo('/auth/forgot-password.php');
            }
        }

        $this->render('auth/forgot-password', [
            'pageTitle' => 'Reset Password | Mindful',
            'pageStyles' => ['login', 'auth'],
            'errors' => $errors,
            'email' => $email,
            'message' => $message,
            'previewUrl' => $previewUrl,
            'csrfToken' => csrfToken(),
        ]);
    }

    public function reset(): void
    {
        requireGuest();

        $errors = [];
        $token = trim((string) ($_POST['token'] ?? $_GET['token'] ?? ''));
        $validTokenFormat = preg_match('/\A[a-f0-9]{64}\z/', $token) === 1;
        $tokenHash = $validTokenFormat ? hash('sha256', $token) : '';
        $resetModel = new PasswordReset();
        $tokenIsValid = $validTokenFormat && $resetModel->isValid($tokenHash);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = (string) ($_POST['password'] ?? '');
            $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

            if (!hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
                $errors[] = 'Your session expired. Please try again.';
            }
            if (!$tokenIsValid) {
                $errors[] = 'This password reset link is invalid or has expired.';
            }
            if (strlen($password) < 8) {
                $errors[] = 'Password must contain at least 8 characters.';
            }
            if ($password !== $confirmPassword) {
                $errors[] = 'Password confirmation does not match.';
            }

            if ($errors === []) {
                $updated = $resetModel->resetPassword(
                    $tokenHash,
                    password_hash($password, PASSWORD_DEFAULT)
                );

                if ($updated) {
                    session_regenerate_id(true);
                    $_SESSION['flash_success'] = 'Your password has been updated. You can now sign in.';
                    redirectTo('/');
                }

                $errors[] = 'This password reset link is invalid or has expired.';
                $tokenIsValid = false;
            }
        }

        $this->render('auth/reset-password', [
            'pageTitle' => 'Choose New Password | Mindful',
            'pageStyles' => ['login', 'auth'],
            'errors' => $errors,
            'token' => $token,
            'tokenIsValid' => $tokenIsValid,
            'csrfToken' => csrfToken(),
        ]);
    }
}
