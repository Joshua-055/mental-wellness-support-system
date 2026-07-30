<?php
declare(strict_types=1);

final class AppointmentController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('student');

        $this->render('student/appointments', [
            'pageTitle' => 'Appointments | Mindful',
            'pageStyles' => ['student-dashboard'],
            'currentUser' => $currentUser,
        ]);
    }
}