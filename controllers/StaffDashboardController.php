<?php
declare(strict_types=1);

final class StaffDashboardController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('staff', 'admin');
        $appointmentModel = new Appointment();
        $supportRequestModel = new SupportRequest();

        $this->render('staff/dashboard', [
            'pageTitle' => 'Staff Dashboard | Mindful',
            'pageStyles' => ['staff-dashboard'],
            'currentUser' => $currentUser,
            'dashboard' => (new Dashboard())->staff((int) $currentUser['id']),
            'unassignedAppointmentsCount' => $appointmentModel->countUnassignedAppointments(),
            'unassignedRequestsCount' => $supportRequestModel->countUnassignedRequests(),
        ]);
    }
}
