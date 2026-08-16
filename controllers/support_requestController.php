<?php
declare(strict_types=1);

final class support_requestController extends Controller
{
    public function index(): void
    {
        $currentUser = requireRole('student');

        // 1. 获取当前学生 ID
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = (int) $_SESSION['user']['id'];

        // 2. 呼叫 Model 拿数据
        $supportModel = new SupportRequest();
        $requests = $supportModel->getRequestsByUserId($userId);

        // 3. 把 $requests 传给前端页面
        $this->render('student/support_request', [
            'pageTitle' => 'Support Request | Mindful',
            'pageStyles' => ['support_request'],
            'pageScripts' => ['support_request'],
            'currentUser' => $currentUser,
            'requests' => $requests, // 👈 这一行非常重要！
        ]);
    }

    public function create(): void
    {
        $currentUser = requireRole('student');

        // 1. 拦截并处理 POST 表单提交
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // 确保用户已登录
            if (!isset($_SESSION['user']['id'])) {
                die("Error: 您尚未登录或登录已过期，请重新登录！");
            }
            $userId = (int) $_SESSION['user']['id'];

            // 接收数据并处理类型
            $title = trim($_POST['title'] ?? '');
            // 如果用户没有选分类，就存为 null，否则转成整数
            $categoryId = !empty($_POST['category']) ? (int) $_POST['category'] : null;
            $description = trim($_POST['description'] ?? '');

            // 拼接 subject 和 description
            $finalDescription = "【Subject】: " . $title . "\n【Details】: " . $description;

            // 默认值
            $priority = 'Normal';
            $status = 'submitted';
            $assignedStaffId = null;
            $checkinId = null;

            try {
                // 👉 这里的逻辑变得非常干净！直接呼叫厨师 (Model) 去干活！
                $supportModel = new SupportRequest();

                $supportModel->createRequest(
                    $userId,
                    $checkinId,
                    $categoryId,
                    $finalDescription,
                    $priority,
                    $status,
                    $assignedStaffId
                );

                // 提交成功后跳回主页面（小窗口里的 target="_parent" 会让大页面跟着刷新）
                header("Location: index.php?page=support_request&success=1");
                exit;

            } catch (Exception $e) {
                die("数据库操作失败: " . $e->getMessage());
            }
        }

        // 2. 如果不是 POST（比如第一次点开小窗口），就正常渲染页面
        $this->render('student/create_support_request', [
            'pageTitle' => 'Create Support Request | Mindful',
            'pageStyles' => ['create_support_request'],
            'currentUser' => $currentUser,
        ]);
    }
    
    // 👇 新增 detail 方法，处理详情页逻辑
    public function detail(): void
    {
        $currentUser = requireRole('student');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $userId = (int) $_SESSION['user']['id'];
        
        // 获取网址上的 id (比如 &id=102)
        $requestId = (int) ($_GET['id'] ?? 0);

        if ($requestId === 0) {
            // 如果没带 ID，跳回列表页
            header("Location: index.php?page=support_request");
            exit;
        }

        // 去数据库查这条数据
        $supportModel = new SupportRequest();
        $requestData = $supportModel->getRequestById($requestId, $userId);

        if (!$requestData) {
            die("Error: Request not found or you don't have permission to view it.");
        }

        // 把查到的数据传给详情页
        $this->render('student/support_request_detail', [
            'pageTitle' => 'Request Details | Mindful',
            'pageStyles' => ['support_request'], // 你可以复用之前的 CSS
            'currentUser' => $currentUser,
            'request' => $requestData, // 👈 单条记录数据传到前端了
        ]);
    }
}