<?php
declare(strict_types=1);

final class SettingsController extends Controller
{
    public function student(): void
    {
        $this->show('student');
    }

    public function staff(): void
    {
        $this->show('staff', 'admin');
    }

    private function show(string ...$allowedRoles): void
    {
        $sessionUser = requireRole(...$allowedRoles);
        $userId = (int) $sessionUser['id'];
        $userModel = new User();
        $account = $userModel->findById($userId);

        if ($account === null || !(bool) $account['is_active']) {
            logoutUser();
            redirectTo('/');
        }

        $errors = [];
        $activeSection = (string) ($_POST['form_action'] ?? 'profile');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
                $errors[] = 'Your session expired. Please refresh the page and try again.';
            } elseif ($activeSection === 'profile') {
                $this->updateProfile($userModel, $account, $errors);
            } elseif ($activeSection === 'password') {
                $this->changePassword($userModel, $account, $errors);
            } else {
                $errors[] = 'Invalid settings action.';
            }

            $account = $userModel->findById($userId) ?? $account;
        }

        $success = $_SESSION['settings_success'] ?? null;
        unset($_SESSION['settings_success']);

        $this->render('settings/index', [
            'pageTitle' => 'Settings | Mindful',
            'pageStyles' => ['student-dashboard', 'settings'],
            'pageScripts' => ['settings'],
            'currentUser' => $account,
            'errors' => $errors,
            'success' => $success,
            'activeSection' => $activeSection,
            'csrfToken' => csrfToken(),
        ]);
    }

    private function updateProfile(User $userModel, array $account, array &$errors): void
    {
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));

        if (mb_strlen($fullName) < 2 || mb_strlen($fullName) > 100) {
            $errors[] = 'Full name must be between 2 and 100 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            $errors[] = 'Enter a valid email address.';
        }
        if ($errors === [] && $userModel->emailBelongsToAnotherUser($email, (int) $account['id'])) {
            $errors[] = 'That email address is already in use.';
        }

        if ($errors !== []) {
            return;
        }

        try {
            $userModel->updateProfile((int) $account['id'], $fullName, $email);
        } catch (mysqli_sql_exception $exception) {
            if ((int) $exception->getCode() === 1062) {
                $errors[] = 'That email address is already in use.';
                return;
            }
            error_log(sprintf(
                'Profile update failed for user %d: [%d] %s',
                (int) $account['id'],
                (int) $exception->getCode(),
                $exception->getMessage()
            ));
            $errors[] = 'We could not update your profile right now. Please try again in a moment.';
            return;
        }

        $_SESSION['user']['full_name'] = $fullName;
        $_SESSION['user']['email'] = $email;
        $_SESSION['settings_success'] = 'Your profile has been updated.';
        redirectTo($this->settingsPath((string) $account['role']));
    }

    private function changePassword(User $userModel, array $account, array &$errors): void
    {
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if (!password_verify($currentPassword, (string) $account['password_hash'])) {
            $errors[] = 'Your current password is incorrect.';
        }
        if (strlen($newPassword) < 8) {
            $errors[] = 'New password must contain at least 8 characters.';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'New password confirmation does not match.';
        }
        if ($newPassword !== '' && password_verify($newPassword, (string) $account['password_hash'])) {
            $errors[] = 'Choose a new password that is different from your current password.';
        }

        if ($errors !== []) {
            return;
        }

        $userModel->updatePassword(
            (int) $account['id'],
            password_hash($newPassword, PASSWORD_DEFAULT)
        );
        session_regenerate_id(true);
        $_SESSION['settings_success'] = 'Your password has been changed securely.';
        redirectTo($this->settingsPath((string) $account['role']));
    }

    private function settingsPath(string $role): string
    {
        return $role === 'student'
            ? '/student/index.php?page=settings'
            : '/staff/index.php?page=settings';
    }
}
