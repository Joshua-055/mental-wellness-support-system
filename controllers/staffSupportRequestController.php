<?php
declare(strict_types=1);

final class StaffSupportRequestController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('staff', 'admin');

        // 调用 Model 获取数据
        $supportRequestModel = new SupportRequest(); // 确保你的 Model 类名正确
        $supportRequests = $supportRequestModel->getAllRequests();

        // Future: fetch staff cases, appointments and aggregates through models here.
        $this->render('staff/supportRequest', [
            'pageTitle' => 'Staff Support Requests | Mindful',
            'pageStyles' => ['staff-supportRequest'],
            'currentUser' => $currentUser,
            'supportRequests' => $supportRequests // 传给表格循环
        ]);
    }

    public function assignStaff(int $requestId, int $staffId): bool
    {
        $statement = Database::connection()->prepare(
            "UPDATE support_requests 
         SET assigned_staff_id = ?, STATUS = 'in_progress', updated_at = NOW() 
         WHERE id = ?"
        );
        $statement->bind_param('ii', $staffId, $requestId);
        $success = $statement->execute();
        $statement->close();
        return $success;
    }

    public function takeCase(): void
{
    // 确保是 staff 登录，并从 session 获取当前员工 ID
    $currentUser = requireRole('staff');
    $staffId = (int) $_SESSION['user']['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requestId = (int) ($_POST['request_id'] ?? 0);

        if (ob_get_length()) { ob_clean(); }
        header('Content-Type: application/json; charset=utf-8');

        if ($requestId > 0) {
            try {
                $model = new SupportRequest();
                $success = $model->assignStaff($requestId, $staffId);
                
                if ($success) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => '数据库更新失败']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => '无效的请求 ID']);
        }
        exit;
    }
}
}
