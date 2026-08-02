<?php
declare(strict_types=1);

final class StaffSupportRequestController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('staff', 'admin');

        // Future: fetch staff cases, appointments and aggregates through models here.
        $this->render('staff/supportRequest', [
            'pageTitle' => 'Staff Support Requests | Mindful',
            'pageStyles' => ['staff-supportRequest'],
            'currentUser' => $currentUser,
        ]);
    }
}
