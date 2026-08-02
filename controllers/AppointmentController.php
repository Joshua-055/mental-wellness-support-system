<?php
declare(strict_types=1);

final class AppointmentController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('student');

        $this->render('student/appointments', [
            'pageTitle' => 'Appointments | Mindful',
            'pageScripts' => ['appointment'],
            'pageStyles' => ['appointment'],
            'currentUser' => $currentUser,
        ]);
    }
    public function confirm(): void
    {
        $currentUser = requireRole('student');

        // 1. 获取当前登录学生的 ID
        $userId = (int) $_SESSION['user']['id'];

        // 2. 直接实例化就行了，bootstrap 已经帮你 require 过了！
        $supportModel = new SupportRequest();
        $rawRequests = $supportModel->getRequestsByUserId($userId);

        $this->render('student/appointment_confirm', [
            'pageTitle' => 'Confirm Appointment | Mindful',
            'pageStyles' => ['appointment_confirm'],
            'pageScripts' => ['appointmentConfirm'],
            'currentUser' => $currentUser,
            'rawRequests' => $rawRequests,
        ]);
    }
}