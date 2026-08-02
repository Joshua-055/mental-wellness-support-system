<?php
declare(strict_types=1);

final class Appointment
{
    /**
     * 将新的预约存入数据库
     */
    public function createAppointment(array $data): bool
    {
        // 1. 使用你系统专属的 Database::connection()，并将占位符改为 ?
        $statement = Database::connection()->prepare(
            'INSERT INTO appointments (user_id, support_request_id, appointment_date, appointment_time, reason, STATUS, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, \'pending\', NOW(), NOW())'
        );

        // 2. 绑定参数 (mysqli 专属语法)
        // 'iisss' 代表数据类型：i=整型(int), s=字符串(string)
        // 对应顺序: user_id(i), support_request_id(i), appointment_date(s), appointment_time(s), reason(s)
        $statement->bind_param(
            'iisss',
            $data['user_id'],
            $data['support_request_id'],
            $data['appointment_date'],
            $data['appointment_time'],
            $data['reason']
        );

        // 3. 执行并获取结果
        $success = $statement->execute();

        // 4. 关闭 statement
        $statement->close();

        return $success;
    }

    // ✅ 查询预约也放这里
    public function getAppointmentsByUserId(int $userId): array
    {
        // 1. 使用 LEFT JOIN 查出 appointments 表的所有必要字段，外加 support_requests 的 description
        $statement = Database::connection()->prepare(
            'SELECT 
                a.id,
                a.appointment_date,
                a.appointment_time,
                a.reason,
                a.STATUS,
                a.staff_remark,
                sr.description AS support_description
             FROM appointments a
             LEFT JOIN support_requests sr ON a.support_request_id = sr.id
             WHERE a.user_id = ?
             ORDER BY a.appointment_date DESC, a.appointment_time DESC'
        );

        $statement->bind_param('i', $userId);

        $statement->execute();

        $result = $statement->get_result();

        $appointments = [];

        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }

        $statement->close();

        return $appointments;
    }
}
