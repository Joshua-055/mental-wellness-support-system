<?php
declare(strict_types=1);

final class SupportRequest
{
    public function createRequest(int $userId, ?int $checkinId, int $categoryId, string $description, string $priority, string $status, ?int $assignedStaffId): bool
    {
        // 1. 使用你系统专属的 Database::connection() 获取数据库连接
        $statement = Database::connection()->prepare(
            'INSERT INTO support_requests (user_id, checkin_id, category_id, description, priority, STATUS, assigned_staff_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())'
        );

        // 2. 绑定参数 (mysqli 专属语法)
        // 'iiisssi' 代表数据类型：i=整型(int), s=字符串(string)
        // 顺序对应: userId(i), checkinId(i), categoryId(i), description(s), priority(s), status(s), assignedStaffId(i)
        $statement->bind_param('iiisssi', $userId, $checkinId, $categoryId, $description, $priority, $status, $assignedStaffId);

        // 3. 执行并获取结果
        $success = $statement->execute();

        // 4. 关闭 statement
        $statement->close();

        return $success;
    }

    // 👇 新增这个方法来获取列表数据
    public function getRequestsByUserId(int $userId): array
    {
        // 使用 LEFT JOIN 连表查询，把分类名称 (NAME) 也一起查出来，按时间倒序排列
        $statement = Database::connection()->prepare(
            'SELECT sr.*, wc.NAME as category_name 
             FROM support_requests sr 
             LEFT JOIN wellness_categories wc ON sr.category_id = wc.id 
             WHERE sr.user_id = ? 
             ORDER BY sr.created_at DESC'
        );

        $statement->bind_param('i', $userId);
        $statement->execute();

        $result = $statement->get_result();
        $requests = $result->fetch_all(MYSQLI_ASSOC);

        $statement->close();

        return $requests;
    }

    // 👇 新增这个方法，用来查单个 Request 的完整信息
    public function getRequestById(int $id, int $userId): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT sr.*, wc.NAME as category_name 
             FROM support_requests sr 
             LEFT JOIN wellness_categories wc ON sr.category_id = wc.id 
             WHERE sr.id = ? AND sr.user_id = ?'
        );

        $statement->bind_param('ii', $id, $userId);
        $statement->execute();

        $result = $statement->get_result()->fetch_assoc();
        $statement->close();

        return $result ?: null; // 如果找不到，返回 null
    }

    public function getAllRequests(): array
    {
        $statement = Database::connection()->prepare(
            'SELECT sr.*, u.full_name AS student_name, staff.full_name AS assigned_staff_name
         FROM support_requests sr
         LEFT JOIN users u ON sr.user_id = u.id
         LEFT JOIN users staff ON sr.assigned_staff_id = staff.id
         ORDER BY sr.created_at DESC'
        );
        $statement->execute();
        $result = $statement->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $statement->close();
        return $data;
    }

    // 🌟 在这里补上这个方法，用来更新指派员工和状态
    public function assignStaff(int $requestId, int $staffId): bool
    {
        $statement = Database::connection()->prepare(
            "UPDATE support_requests 
             SET assigned_staff_id = ?, STATUS = 'under_review', updated_at = NOW() 
             WHERE id = ? AND (assigned_staff_id IS NULL OR assigned_staff_id = 0)"
        );

        $statement->bind_param('ii', $staffId, $requestId);
        $success = $statement->execute();
        $statement->close();

        return $success;
    }

    public function countUnassignedRequests(): int
    {
        $statement = Database::connection()->prepare(
            "SELECT COUNT(*) AS total FROM support_requests WHERE assigned_staff_id IS NULL OR assigned_staff_id = 0"
        );
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        $statement->close();
        return (int) ($result['total'] ?? 0);
    }

    public function completeByAssignedStaff(int $requestId, int $staffId): bool
    {
        $statement = Database::connection()->prepare(
            "UPDATE support_requests SET STATUS = 'resolved', updated_at = NOW()
             WHERE id = ? AND assigned_staff_id = ? AND STATUS NOT IN ('resolved', 'closed')"
        );
        $statement->bind_param('ii', $requestId, $staffId);
        $statement->execute();
        $completed = $statement->affected_rows === 1;
        $statement->close();
        return $completed;
    }
}
