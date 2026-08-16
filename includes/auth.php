<?php
declare(strict_types=1);

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function hasValidCsrfToken(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function currentUser(): ?array
{
    return isset($_SESSION['user']) && is_array($_SESSION['user'])
        ? $_SESSION['user']
        : null;
}

function redirectTo(string $path): never
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function redirectToDashboard(string $role): never
{
    if ($role === 'student') {
        redirectTo('/student/');
    }

    if ($role === 'staff' || $role === 'admin') {
        redirectTo('/staff/');
    }

    logoutUser();
    redirectTo('/');
}

function requireGuest(): void
{
    $user = currentUser();
    if ($user !== null) {
        redirectToDashboard((string) ($user['role'] ?? ''));
    }
}

function requireRole(string ...$allowedRoles): array
{
    $user = currentUser();
    if ($user === null) {
        $_SESSION['flash_error'] = 'Please sign in to continue.';
        redirectTo('/');
    }

    if (!in_array($user['role'] ?? null, $allowedRoles, true)) {
        $_SESSION['flash_error'] = 'You do not have permission to access that page.';
        redirectToDashboard((string) ($user['role'] ?? ''));
    }

    return $user;
}

function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}
