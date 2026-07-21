<?php
declare(strict_types=1);

define('APP_ROOT', __DIR__);

require_once APP_ROOT . '/config/app.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/View.php';
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/controllers/AuthController.php';
require_once APP_ROOT . '/controllers/StudentDashboardController.php';
require_once APP_ROOT . '/controllers/StaffDashboardController.php';
