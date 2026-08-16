<?php
declare(strict_types=1);

final class StudentDashboardController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('student');

        $dashboard = (new Dashboard())->student((int) $currentUser['id']);

        $this->render('student/dashboard', [
            'pageTitle' => 'Dashboard | Mindful',
            'pageStyles' => ['student-dashboard'],
            'currentUser' => $currentUser,
            'dashboard' => $dashboard,
        ]);
    }
}
