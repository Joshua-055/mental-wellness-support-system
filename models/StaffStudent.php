<?php
declare(strict_types=1);

final class StaffStudent
{
    public function listForStaff(int $staffId, bool $isAdmin = false): array
    {
        $statement = Database::connection()->prepare(
            "SELECT u.id, u.full_name, u.is_active, u.created_at,
                (SELECT COUNT(*) FROM support_requests sr WHERE sr.user_id = u.id) AS request_count,
                (SELECT COUNT(*) FROM appointments a WHERE a.user_id = u.id) AS appointment_count,
                (EXISTS(SELECT 1 FROM support_requests sr2 WHERE sr2.user_id = u.id AND sr2.assigned_staff_id = ? AND sr2.STATUS NOT IN ('resolved', 'closed'))
                OR EXISTS(SELECT 1 FROM appointments a2 WHERE a2.user_id = u.id AND a2.staff_id = ? AND a2.STATUS NOT IN ('completed', 'cancelled'))) AS is_assigned
             FROM users u WHERE u.role = 'student' ORDER BY is_assigned DESC, u.full_name ASC"
        );
        $statement->bind_param('ii', $staffId, $staffId);
        $statement->execute();
        $students = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        if ($isAdmin) {
            foreach ($students as &$student) { $student['is_assigned'] = 1; }
            unset($student);
        }
        return $students;
    }

    public function basicProfile(int $studentId): ?array
    {
        $statement = Database::connection()->prepare(
            "SELECT id, full_name, email, is_active, created_at FROM users WHERE id = ? AND role = 'student' LIMIT 1"
        );
        $statement->bind_param('i', $studentId);
        $statement->execute();
        $student = $statement->get_result()->fetch_assoc();
        $statement->close();
        return $student ?: null;
    }

    public function isAssignedTo(int $studentId, int $staffId): bool
    {
        $statement = Database::connection()->prepare(
            "SELECT (EXISTS(SELECT 1 FROM support_requests WHERE user_id = ? AND assigned_staff_id = ? AND STATUS NOT IN ('resolved', 'closed'))
             OR EXISTS(SELECT 1 FROM appointments WHERE user_id = ? AND staff_id = ? AND STATUS NOT IN ('completed', 'cancelled'))) AS assigned"
        );
        $statement->bind_param('iiii', $studentId, $staffId, $studentId, $staffId);
        $statement->execute();
        $assigned = (bool) ($statement->get_result()->fetch_assoc()['assigned'] ?? false);
        $statement->close();
        return $assigned;
    }

    public function checkins(int $studentId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT mood, stress_level, COMMENT AS comment, needs_follow_up, created_at FROM wellness_checkins
             WHERE user_id = ? AND created_at >= CURDATE() - INTERVAL 29 DAY ORDER BY created_at DESC'
        );
        $statement->bind_param('i', $studentId);
        $statement->execute();
        $rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        return $rows;
    }

    public function supportRequests(int $studentId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT sr.*, wc.NAME AS category_name, staff.full_name AS assigned_staff_name FROM support_requests sr
             LEFT JOIN wellness_categories wc ON wc.id = sr.category_id LEFT JOIN users staff ON staff.id = sr.assigned_staff_id
             WHERE sr.user_id = ? ORDER BY sr.created_at DESC LIMIT 10'
        );
        $statement->bind_param('i', $studentId);
        $statement->execute();
        $rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        return $rows;
    }

    public function appointments(int $studentId): array
    {
        $statement = Database::connection()->prepare(
            'SELECT a.*, staff.full_name AS staff_name FROM appointments a LEFT JOIN users staff ON staff.id = a.staff_id
             WHERE a.user_id = ? ORDER BY a.appointment_date DESC, a.appointment_time DESC'
        );
        $statement->bind_param('i', $studentId);
        $statement->execute();
        $rows = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        return $rows;
    }
}
