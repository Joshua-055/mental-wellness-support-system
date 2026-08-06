<?php
declare(strict_types=1);

final class StaffStudentController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('staff', 'admin');
        $model = new StaffStudent();
        $this->render('staff/students', $this->sharedData($currentUser) + [
            'pageTitle' => 'Students | Mindful', 'pageStyles' => ['staff-dashboard', 'staff-students'],
            'students' => $model->listForStaff((int) $currentUser['id'], ($currentUser['role'] ?? '') === 'admin'),
        ]);
    }

    public function show(): void
    {
        $currentUser = requireRole('staff', 'admin');
        $studentId = max(0, (int) ($_GET['student_id'] ?? 0));
        $model = new StaffStudent();
        $student = $model->basicProfile($studentId);
        if ($student === null) { http_response_code(404); }
        $canViewDetails = $student !== null && (($currentUser['role'] ?? '') === 'admin'
            || $model->isAssignedTo($studentId, (int) $currentUser['id']));
        $detail = [];
        if ($canViewDetails) {
            $checkins = $model->checkins($studentId);
            $appointments = $model->appointments($studentId);
            $detail = ['checkins' => $checkins, 'wellness' => $this->wellnessSummary($checkins),
                'supportRequests' => $model->supportRequests($studentId), 'appointments' => $appointments,
                'upcomingAppointment' => $this->upcomingAppointment($appointments)];
        }
        $this->render('staff/studentDetail', $this->sharedData($currentUser) + [
            'pageTitle' => 'Student Detail | Mindful', 'pageStyles' => ['staff-dashboard', 'staff-students'],
            'student' => $student, 'canViewDetails' => $canViewDetails, 'detail' => $detail,
        ]);
    }

    private function sharedData(array $currentUser): array
    {
        return ['currentUser' => $currentUser,
            'unassignedAppointmentsCount' => (new Appointment())->countUnassignedAppointments(),
            'unassignedRequestsCount' => (new SupportRequest())->countUnassignedRequests()];
    }

    private function wellnessSummary(array $checkins): array
    {
        $moods = ['excellent' => 0, 'good' => 0, 'neutral' => 0, 'stressed' => 0, 'overwhelmed' => 0];
        $byDate = [];
        foreach ($checkins as &$checkin) {
            $checkin['mood'] = WellnessCheckin::normalizeMood((string) $checkin['mood']);
            if (isset($moods[$checkin['mood']])) {
                $moods[$checkin['mood']]++;
            }
            $byDate[substr($checkin['created_at'], 0, 10)] = WellnessCheckin::scoreForMood($checkin['mood']);
        }
        unset($checkin);
        $today = new DateTimeImmutable('today');
        $trend = static function (int $days) use ($today, $byDate): array {
            $rows = [];
            for ($offset = $days - 1; $offset >= 0; $offset--) {
                $date = $today->modify("-{$offset} days");
                $rows[] = ['date' => $date->format('Y-m-d'), 'label' => $date->format($days > 7 ? 'j M' : 'D'),
                    'score' => $byDate[$date->format('Y-m-d')] ?? null];
            }
            return $rows;
        };
        $days = array_fill_keys(array_keys($byDate), true);
        $streak = 0;
        for ($cursor = $today; isset($days[$cursor->format('Y-m-d')]); $cursor = $cursor->modify('-1 day')) { $streak++; }
        return ['recent' => $checkins[0] ?? null, 'streak' => $streak, 'moods' => $moods,
            'trend7' => $trend(7), 'trend30' => $trend(30)];
    }

    private function upcomingAppointment(array $appointments): ?array
    {
        $now = new DateTimeImmutable();
        $upcoming = array_values(array_filter($appointments, static function (array $appointment) use ($now): bool {
            $when = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $appointment['appointment_date'] . ' ' . $appointment['appointment_time']);
            return $when !== false && $when >= $now && !in_array($appointment['STATUS'], ['cancelled', 'completed'], true);
        }));
        usort($upcoming, static fn(array $a, array $b): int => strcmp($a['appointment_date'] . $a['appointment_time'], $b['appointment_date'] . $b['appointment_time']));
        return $upcoming[0] ?? null;
    }
}
