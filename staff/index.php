<?php

require_once __DIR__ . '/../bootstrap.php';

$page = $_GET['page'] ?? 'dashboard';

switch ($page) {

    case 'dashboard':
        (new StaffDashboardController())->index();
        break;

    case 'appointment':
        (new staffAppointmentController())->index();
        break;

    case 'supportRequest':
        (new StaffSupportRequestController())->index();
        break;

    case 'appointmentRemark':
        (new staffAppointmentController())->create();
        break;

    case 'appointment_staff_remark':
        (new staffAppointmentController())->create();
        break;

    case 'appointment_take':
        (new staffAppointmentController())->take();
        break;

    case 'appointment_take_save':
        (new staffAppointmentController())->takeSave();
        break;

    default:
        echo "404 Page Not Found";

}
