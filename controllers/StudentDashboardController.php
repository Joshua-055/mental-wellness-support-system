<?php
declare(strict_types=1);

final class StudentDashboardController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('student');

        // Future: fetch the signed-in student's data through models here.
        $this->render('student/dashboard', [
            'pageTitle' => 'Dashboard | Mindful',
            'pageStyles' => ['student-dashboard'],
            'currentUser' => $currentUser,
        ]);
    }
}
