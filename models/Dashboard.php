<?php
declare(strict_types=1);

/** Read-only database projections for the student and staff dashboards. */
final class Dashboard
{
    public function student(int $userId): array
    {
        $database = Database::connection();
        $wellnessModel = new WellnessCheckin();

        $statement = $database->prepare(
            'SELECT mood, stress_level, category_id, created_at FROM wellness_checkins
             WHERE user_id = ? ORDER BY created_at DESC LIMIT 1'
        );
        $statement->bind_param('i', $userId);
        $statement->execute();
        $latestCheckin = $statement->get_result()->fetch_assoc() ?: null;
        $statement->close();
        if ($latestCheckin !== null) {
            $latestCheckin['mood'] = WellnessCheckin::normalizeMood((string) $latestCheckin['mood']);
        }

        $categoryId = (int) ($latestCheckin['category_id'] ?? 0);
        $statement = $database->prepare(
            'SELECT r.id, r.title, r.description, r.category_id, wc.NAME AS category_name
             FROM resources r LEFT JOIN wellness_categories wc ON wc.id = r.category_id
             WHERE r.is_active = 1
             ORDER BY (r.category_id = ?) DESC, r.created_at DESC LIMIT 3'
        );
        $statement->bind_param('i', $categoryId);
        $statement->execute();
        $resources = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $statement = $database->prepare(
            "SELECT a.id, a.appointment_date, a.appointment_time, a.reason, a.STATUS,
                    staff.full_name AS staff_name
             FROM appointments a LEFT JOIN users staff ON staff.id = a.staff_id
             WHERE a.user_id = ? AND a.STATUS IN ('pending', 'approved', 'rescheduled')
               AND TIMESTAMP(a.appointment_date, a.appointment_time) >= NOW()
             ORDER BY a.appointment_date, a.appointment_time LIMIT 1"
        );
        $statement->bind_param('i', $userId);
        $statement->execute();
        $upcomingAppointment = $statement->get_result()->fetch_assoc() ?: null;
        $statement->close();

        $statement = $database->prepare(
            'SELECT sr.id, sr.STATUS, sr.created_at, sr.updated_at, sr.priority,
                    wc.NAME AS category_name
             FROM support_requests sr LEFT JOIN wellness_categories wc ON wc.id = sr.category_id
             WHERE sr.user_id = ? ORDER BY sr.created_at DESC LIMIT 1'
        );
        $statement->bind_param('i', $userId);
        $statement->execute();
        $latestSupportRequest = $statement->get_result()->fetch_assoc() ?: null;
        $statement->close();

        return [
            'has_checkin_today' => $wellnessModel->hasCheckinToday($userId),
            'latest_checkin' => $latestCheckin,
            'wellness' => $wellnessModel->summaryByUser($userId),
            'resources' => $resources,
            'upcoming_appointment' => $upcomingAppointment,
            'latest_support_request' => $latestSupportRequest,
        ];
    }

    public function staff(int $staffId): array
    {
        $database = Database::connection();

        $overview = $database->query(
            "SELECT
                (SELECT COUNT(*) FROM support_requests WHERE STATUS NOT IN ('resolved', 'closed')) AS pending_requests,
                (SELECT COUNT(*) FROM support_requests WHERE STATUS NOT IN ('resolved', 'closed') AND created_at >= CURDATE() - INTERVAL 6 DAY) AS requests_this_week,
                (SELECT COUNT(*) FROM support_requests WHERE priority = 'high' AND STATUS NOT IN ('resolved', 'closed')) AS high_priority_cases,
                (SELECT COUNT(*) FROM appointments WHERE appointment_date = CURDATE() AND STATUS <> 'cancelled') AS today_appointments,
                (SELECT COUNT(*) FROM appointments WHERE appointment_date = CURDATE() AND appointment_time >= CURTIME() AND STATUS NOT IN ('completed', 'cancelled')) AS remaining_appointments,
                (SELECT COUNT(*) FROM users WHERE role = 'student' AND is_active = 1) AS active_students,
                (SELECT COUNT(*) FROM users WHERE role = 'student' AND is_active = 1 AND created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')) AS students_this_month,
                (SELECT COUNT(*) FROM users WHERE role = 'student' AND is_active = 1 AND created_at >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01') AND created_at < DATE_FORMAT(CURDATE(), '%Y-%m-01')) AS students_last_month"
        )->fetch_assoc();

        $priorityRequests = $database->query(
            "SELECT sr.id, sr.priority, sr.STATUS, sr.created_at, u.id AS student_id,
                    u.full_name AS student_name, wc.NAME AS category_name
             FROM support_requests sr INNER JOIN users u ON u.id = sr.user_id
             LEFT JOIN wellness_categories wc ON wc.id = sr.category_id
             WHERE sr.STATUS NOT IN ('resolved', 'closed')
             ORDER BY FIELD(sr.priority, 'high', 'medium', 'normal'), sr.created_at ASC LIMIT 3"
        )->fetch_all(MYSQLI_ASSOC);

        $statement = $database->prepare(
            "SELECT a.id, a.appointment_time, a.reason, a.STATUS, a.staff_id, u.full_name AS student_name
             FROM appointments a INNER JOIN users u ON u.id = a.user_id
             WHERE a.appointment_date = CURDATE() AND a.STATUS <> 'cancelled'
               AND (a.staff_id = ? OR a.staff_id IS NULL OR a.staff_id = 0)
             ORDER BY a.appointment_time LIMIT 5"
        );
        $statement->bind_param('i', $staffId);
        $statement->execute();
        $todayAppointments = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $wellness = $database->query(
            "SELECT
                ROUND(AVG(CASE WHEN created_at >= CURDATE() - INTERVAL 6 DAY THEN stress_level END), 1) AS average_stress,
                COUNT(CASE WHEN created_at >= CURDATE() - INTERVAL 6 DAY THEN 1 END) AS recent_checkins,
                COUNT(CASE WHEN created_at >= CURDATE() - INTERVAL 13 DAY AND created_at < CURDATE() - INTERVAL 6 DAY THEN 1 END) AS previous_checkins,
                ROUND(100 * SUM(CASE WHEN created_at >= CURDATE() - INTERVAL 6 DAY AND mood IN ('excellent', 'good', 'neutral') THEN 1 ELSE 0 END)
                    / NULLIF(COUNT(CASE WHEN created_at >= CURDATE() - INTERVAL 6 DAY THEN 1 END), 0)) AS positive_percent
             FROM wellness_checkins"
        )->fetch_assoc();

        $dailyRows = $database->query(
            "SELECT DATE(created_at) AS checkin_date, COUNT(*) AS total
             FROM wellness_checkins
             WHERE created_at >= CURDATE() - INTERVAL 6 DAY
             GROUP BY DATE(created_at)"
        )->fetch_all(MYSQLI_ASSOC);
        $countsByDate = [];
        foreach ($dailyRows as $row) {
            $countsByDate[(string) $row['checkin_date']] = (int) $row['total'];
        }
        $wellness['daily_counts'] = [];
        $today = new DateTimeImmutable('today');
        for ($day = 6; $day >= 0; $day--) {
            $date = $today->modify("-{$day} days");
            $wellness['daily_counts'][] = [
                'label' => $date->format('D'),
                'count' => $countsByDate[$date->format('Y-m-d')] ?? 0,
            ];
        }

        $workflow = ['submitted' => 0, 'under_review' => 0, 'follow_up_scheduled' => 0, 'resolved' => 0];
        $workflowRows = $database->query(
            "SELECT STATUS, COUNT(*) AS total FROM support_requests WHERE STATUS <> 'closed' GROUP BY STATUS"
        )->fetch_all(MYSQLI_ASSOC);
        foreach ($workflowRows as $row) {
            if (array_key_exists((string) $row['STATUS'], $workflow)) {
                $workflow[(string) $row['STATUS']] = (int) $row['total'];
            }
        }

        $followUps = $database->query(
            "SELECT u.id AS student_id, u.full_name AS student_name,
                    COUNT(*) AS high_stress_count, MAX(wc.created_at) AS latest_checkin
             FROM wellness_checkins wc INNER JOIN users u ON u.id = wc.user_id
             WHERE wc.created_at >= CURDATE() - INTERVAL 6 DAY AND wc.stress_level >= 4 AND u.is_active = 1
             GROUP BY u.id, u.full_name HAVING COUNT(*) >= 2
             ORDER BY high_stress_count DESC, latest_checkin DESC LIMIT 2"
        )->fetch_all(MYSQLI_ASSOC);

        $resources = $database->query(
            "SELECT SUM(is_active = 1) AS published, SUM(is_active = 0) AS drafts,
                    SUM(updated_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')) AS updated_this_month
             FROM resources"
        )->fetch_assoc();

        return [
            'overview' => $overview,
            'priority_requests' => $priorityRequests,
            'today_appointments' => $todayAppointments,
            'wellness' => $wellness,
            'workflow' => $workflow,
            'follow_ups' => $followUps,
            'resources' => $resources,
        ];
    }
}
