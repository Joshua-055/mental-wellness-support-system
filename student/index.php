<?php

require_once __DIR__ . '/../bootstrap.php';

$page = $_GET['page'] ?? 'dashboard';

switch ($page) {

    case 'dashboard':
        (new StudentDashboardController())->index();
        break;

    case 'appointments':
        (new AppointmentController())->index();
        break;

    case 'appointment_confirm':
        (new AppointmentController())->confirm();
        break;

    default:
        echo "404 Page Not Found";
}