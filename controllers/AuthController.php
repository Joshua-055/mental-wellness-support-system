<?php
declare(strict_types=1);

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        requireGuest();

        $errors = [];
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = strtolower(trim((string) ($_POST['email'] ?? '')));
            $password = (string) ($_POST['password'] ?? '');

            if (!hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
                $errors[] = 'Your session expired. Please try again.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
                $errors[] = 'Enter a valid email and password.';
            } else {
                $user = (new User())->findByEmail($email);
                $validCredentials = $user !== null
                    && password_verify($password, (string) $user['password_hash']);

                if (!$validCredentials) {
                    $errors[] = 'Invalid email or password.';
                } elseif (!(bool) $user['is_active']) {
                    $errors[] = 'This account has been disabled. Please contact support.';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => (int) $user['id'],
                        'full_name' => (string) $user['full_name'],
                        'email' => (string) $user['email'],
                        'role' => (string) $user['role'],
                    ];

                    redirectToDashboard((string) $user['role']);
                }
            }
        }

        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->render('auth/login', [
            'pageTitle' => 'Welcome Back | Mindful',
            'pageStyles' => ['login', 'auth'],
            'errors' => $errors,
            'email' => $email,
            'flashSuccess' => $flashSuccess,
            'flashError' => $flashError,
            'csrfToken' => csrfToken(),
        ]);
    }

    public function register(): void
    {
        requireGuest();

        $errors = [];
        $old = [
            'full_name' => trim((string) ($_POST['full_name'] ?? '')),
            'email' => strtolower(trim((string) ($_POST['email'] ?? ''))),
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = (string) ($_POST['password'] ?? '');
            $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

            if (!hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
                $errors[] = 'Your session expired. Please try again.';
            }
            if (mb_strlen($old['full_name']) < 2 || mb_strlen($old['full_name']) > 100) {
                $errors[] = 'Full name must be between 2 and 100 characters.';
            }
            if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL) || strlen($old['email']) > 150) {
                $errors[] = 'Enter a valid email address.';
            }
            if (strlen($password) < 8) {
                $errors[] = 'Password must contain at least 8 characters.';
            }
            if ($password !== $confirmPassword) {
                $errors[] = 'Password confirmation does not match.';
            }

            $userModel = new User();
            if ($errors === [] && $userModel->emailExists($old['email'])) {
                $errors[] = 'An account with this email already exists.';
            }

            if ($errors === []) {
                try {
                    $userModel->createStudent(
                        $old['full_name'],
                        $old['email'],
                        password_hash($password, PASSWORD_DEFAULT)
                    );
                    $_SESSION['flash_success'] = 'Your student account has been created. You can now sign in.';
                    redirectTo('/');
                } catch (mysqli_sql_exception $exception) {
                    if ((int) $exception->getCode() === 1062) {
                        $errors[] = 'An account with this email already exists.';
                    } else {
                        throw $exception;
                    }
                }
            }
        }

        $this->render('auth/register', [
            'pageTitle' => 'Create Account | Mindful',
            'pageStyles' => ['login', 'auth'],
            'errors' => $errors,
            'old' => $old,
            'csrfToken' => csrfToken(),
        ]);
    }

    public function logout(): never
    {
        logoutUser();
        redirectTo('/');
    }
}
