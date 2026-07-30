<?php
declare(strict_types=1);

final class AppointmentController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('student');

        $this->render('student/appointments', [
            'pageTitle' => 'Appointments | Mindful',
            'pageStyles' => ['appointment'],
            'currentUser' => $currentUser,
        ]);
    }
    public function confirm(): void
    {
        $currentUser = requireRole('student');

        $this->render('student/appointment_confirm', [
            'pageTitle' => 'Confirm Appointment | Mindful',
            'pageStyles' => ['appointment_confirm'],
            'currentUser' => $currentUser,
        ]);
    }
}