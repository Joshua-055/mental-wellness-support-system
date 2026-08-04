<?php
declare(strict_types=1);

final class staffAppointmentController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('staff', 'admin');

        $appointmentModel = new Appointment();
        $appointments = $appointmentModel->getAllAppointments();

        // 🌟 统计未分配的 Appointment 数量
        $unassignedAppointmentsCount = $appointmentModel->countUnassignedAppointments();

        $supportRequestModel = new SupportRequest();
        $unassignedRequestsCount = $supportRequestModel->countUnassignedRequests();

        $this->render('staff/appointment', [
            'pageTitle' => 'Staff Appointments | Mindful',
            'pageStyles' => ['staff-appointment'],
            'pageScripts' => ['staff-appointment'],
            'currentUser' => $currentUser,
            'appointments' => $appointments,
            'unassignedAppointmentsCount' => $unassignedAppointmentsCount, // 传给视图
            'unassignedRequestsCount' => $unassignedRequestsCount, // 👈 补上这个！
        ]);
    }

    public function create(): void
    {
        $currentUser = requireRole('staff', 'admin');

        $appointmentId = (int) ($_GET['appointment_id'] ?? 0);

        $this->render('staff/appointmentRemark', [
            'pageTitle' => 'Staff Appointment Remark | Mindful',
            'pageStyles' => ['staff-appointmentRemark'],
            'pageScripts' => ['staff-appointmentRemark'],
            'currentUser' => $currentUser,
            'appointmentId' => $appointmentId
        ]);
    }

    public function take(): void
    {
        // 1. 确保当前是 Staff 登录
        $currentUser = requireRole('staff');

        // 🌟 2. 系统自动从 Session 中提取当前登录的 Staff ID (就是你截图里的 id)
        $staffId = (int) $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointmentId = (int) ($_POST['appointment_id'] ?? 0);

            // 清理多余输出并声明返回 JSON
            if (ob_get_length()) {
                ob_clean();
            }
            header('Content-Type: application/json; charset=utf-8');

            if ($appointmentId > 0) {
                try {
                    $appointmentModel = new Appointment();
                    $success = $appointmentModel->assignStaffWithRemark($appointmentId, $staffId, '');

                    if ($success) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => '更新失败，请重试']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => '无效的预约 ID']);
            }
            exit;
        }
    }

    public function takeSave(): void
    {
        $currentUser = requireRole('staff');
        $staffId = (int) $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $appointmentId = (int) ($_POST['appointment_id'] ?? 0);
            $remark = trim($_POST['staff_remark'] ?? '');

            if (ob_get_length()) {
                ob_clean();
            }
            header('Content-Type: application/json; charset=utf-8');

            if ($appointmentId > 0 && $remark !== '') {
                try {
                    $model = new Appointment();
                    $success = $model->assignStaffWithRemark($appointmentId, $staffId, $remark);

                    if ($success) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => '数据库更新失败']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => '参数不完整']);
            }
            exit;
        }
    }
}