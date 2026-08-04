<?php
declare(strict_types=1);

final class ResourcesController extends Controller
{
    public function student(): void
    {
        $currentUser = requireRole('student');

        $this->renderResources($currentUser, 'student');
    }

    public function staff(): void
    {
        $currentUser = requireRole('staff', 'admin');

        $this->renderResources($currentUser, 'staff');
    }

    private function renderResources(array $currentUser, string $role): void
    {
        $viewData = [
            'pageTitle' => 'Resources | Mindful',
            'pageStyles' => [$role === 'staff' ? 'staff-dashboard' : 'student-dashboard', 'resources'],
            'currentUser' => $currentUser,
            'navigationRole' => $role,
        ];

        $this->render('resources/index', $viewData);
    }
}
