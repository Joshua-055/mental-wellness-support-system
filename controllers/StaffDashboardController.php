<?php
declare(strict_types=1);

final class StaffDashboardController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('staff', 'admin');


        $appointmentModel = new Appointment();
        $unassignedAppointmentsCount = $appointmentModel->countUnassignedAppointments();

        $supportRequestModel = new SupportRequest();
        $unassignedRequestsCount = $supportRequestModel->countUnassignedRequests();

        // Future: fetch staff cases, appointments and aggregates through models here.
        $this->render('staff/dashboard', [
            'pageTitle' => 'Staff Dashboard | Mindful',
            'pageStyles' => ['staff-dashboard'],
            'currentUser' => $currentUser,
            'unassignedAppointmentsCount' => $unassignedAppointmentsCount, // 传给视图
            'unassignedRequestsCount' => $unassignedRequestsCount, // 👈 补上这个！
        ]);
    }
}
