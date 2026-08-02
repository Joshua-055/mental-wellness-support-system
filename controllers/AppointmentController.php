<?php
declare(strict_types=1);

final class AppointmentController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('student');

        $userId = (int) $_SESSION['user']['id'];

        $appointmentModel = new Appointment();
        $appointments = $appointmentModel->getAppointmentsByUserId($userId);

        $this->render('student/appointments', [
            'pageTitle' => 'Appointments | Mindful',
            'pageScripts' => ['appointment'],
            'pageStyles' => ['appointment'],
            'currentUser' => $currentUser,
            'appointments' => $appointments,
        ]);
    }
    public function confirm(): void
    {
        $currentUser = requireRole('student');

        // 1. 获取当前登录学生的 ID
        $userId = (int) $_SESSION['user']['id'];

        // 2. 直接实例化就行了，bootstrap 已经帮你 require 过了！
        $supportModel = new SupportRequest();
        $rawRequests = $supportModel->getRequestsByUserId($userId);

        $this->render('student/appointment_confirm', [
            'pageTitle' => 'Confirm Appointment | Mindful',
            'pageStyles' => ['appointment_confirm'],
            'pageScripts' => ['appointmentConfirm'],
            'currentUser' => $currentUser,
            'rawRequests' => $rawRequests,
        ]);
    }

    public function save(): void
    {
        // 确保是 POST 请求并且用户已登录
        $currentUser = requireRole('student');
        $userId = (int) $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. 接收前端传过来的数据
            $date = trim($_POST['appointment_date'] ?? '');
            $time = trim($_POST['appointment_time'] ?? '');
            $supportRequestId = trim($_POST['support_request_id'] ?? '');
            $reason = trim($_POST['reason'] ?? '');

            // 处理可选的外键 (如果是空字符串就转成 null)
            $supportRequestId = ($supportRequestId === '') ? null : (int) $supportRequestId;

            try {
                // 2. 实例化 Model (让 Model 去干活)
                $appointmentModel = new Appointment();

                // 3. 把数据打包传给 Model 的方法
                $isSaved = $appointmentModel->createAppointment([
                    'user_id' => $userId,
                    'support_request_id' => $supportRequestId,
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                    'reason' => $reason
                ]);

                // 4. 判断结果并告诉前端
                if ($isSaved) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => '保存失败，请稍后重试。']);
                }

            } catch (Exception $e) {
                // 如果数据库报错，告诉前端失败原因
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit; // 接口必须直接 exit，不要加载任何 HTML 视图
        }
    }
}