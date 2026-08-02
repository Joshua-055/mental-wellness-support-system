<?php
declare(strict_types=1);

define('APP_ROOT', __DIR__);

require_once APP_ROOT . '/config/app.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessionPath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
        . DIRECTORY_SEPARATOR
        . 'mental-wellness-sessions';

    if (!is_dir($sessionPath) && !mkdir($sessionPath, 0700, true) && !is_dir($sessionPath)) {
        throw new RuntimeException('Unable to create the session storage directory.');
    }

    session_save_path($sessionPath);
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}

require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/View.php';
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/includes/auth.php';
require_once APP_ROOT . '/models/User.php';
require_once APP_ROOT . '/models/PasswordReset.php';
require_once APP_ROOT . '/controllers/AuthController.php';
require_once APP_ROOT . '/controllers/PasswordResetController.php';
require_once APP_ROOT . '/controllers/StudentDashboardController.php';
require_once APP_ROOT . '/controllers/StaffDashboardController.php';
require_once APP_ROOT . '/controllers/AppointmentController.php';
require_once APP_ROOT . '/controllers/support_requestController.php';
require_once APP_ROOT . '/models/SupportRequest.php';
require_once APP_ROOT . '/models/Appointment.php';
require_once APP_ROOT . '/controllers/staffAppointmentController.php';
require_once APP_ROOT . '/controllers/staffSupportRequestController.php';