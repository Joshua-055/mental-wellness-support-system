<?php
declare(strict_types=1);

final class StaffDashboardController extends Controller
{
    public function index(): void
    {
        // Future: fetch staff cases, appointments and aggregates through models here.
        $this->render('staff/dashboard', [
            'pageTitle' => 'Staff Dashboard | Mindful',
            'pageStyles' => ['staff-dashboard'],
        ]);
    }
}
