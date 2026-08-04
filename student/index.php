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

    case 'appointment_save':
        (new AppointmentController())->save();
        break;

    case 'support_request':
        (new support_requestController())->index();
        break;

    case 'create_support_request':
        (new support_requestController())->create();
        break;

    case 'support_request_detail':
        (new support_requestController())->detail();
        break;

    case 'settings':
        (new SettingsController())->student();
        break;

    default:
        echo "404 Page Not Found";
}
