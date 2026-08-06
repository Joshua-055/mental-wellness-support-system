<?php
declare(strict_types=1);

final class ResourcesController extends Controller
{
    public function student(): void
    {
        $currentUser = requireRole('student');

        $this->renderResources($currentUser, 'student');
    }

    public function staff(): void
{
    $currentUser = requireRole('staff', 'admin');


    $this->renderResources($currentUser, 'staff');
}

    private function renderResources(array $currentUser, string $role): void
{
    $resourcesModel = new Resources();


    $keyword = trim($_GET['search'] ?? '');



    if ($keyword !== '') {

        $resources = $resourcesModel->searchResources(
            $keyword,
            $role === 'student'
        );

    } else {


        $resources = $role === 'staff'
            ? $resourcesModel->getAllResources()
            : $resourcesModel->getActiveResources();

    }



    $viewData = [
        'pageTitle' => 'Resources | Mindful',
        'pageStyles' => [
            $role === 'staff' ? 'staff-dashboard' : 'student-dashboard',
            'resources'
        ],
        'currentUser' => $currentUser,
        'navigationRole' => $role,
        'resources' => $resources,
    ];


    $this->render('resources/index', $viewData);
}

    public function create(): void
    {
        requireRole('staff', 'admin');

        $resourceModel = new Resources();

        $viewData = [
            'pageTitle' => 'Add Resource | Mindful',
            'pageStyles' => [
                'staff-dashboard',
                'resources'
            ],
            'navigationRole' => 'staff',
            'categories' => $resourceModel->getCategories(),
        ];


        $this->render(
            'resources/create',
            $viewData
        );
    }

    public function store(): void
{
    requireRole('staff', 'admin');


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header(
    'Location: ' . BASE_URL . '/staff/index.php?page=resources'
);
        exit;
    }


    $resourceModel = new Resources();


    $success = $resourceModel->createResource(
        (int) $_POST['category_id'],
        trim($_POST['title']),
        trim($_POST['description']),
        trim($_POST['services']),
        trim($_POST['email']),
        trim($_POST['phone']),
        trim($_POST['location']),
    );


    if ($success) {

        header(
    'Location: ' . BASE_URL . '/staff/index.php?page=resources'
        );

        exit;
    }
}
public function update(): void
{
    requireRole('staff','admin');


    $resourceModel = new Resources();


    $resourceModel->updateResource(
        (int)$_POST['id'],
        trim($_POST['title']),
        trim($_POST['description']),
        trim($_POST['services']),
        trim($_POST['email']),
        trim($_POST['phone']),
        trim($_POST['location'])
    );


    header(
        'Location: ' . BASE_URL . '/staff/index.php?page=resources'
    );

    exit;
}

public function delete(): void
{
    requireRole('staff','admin');


    $resourceModel = new Resources();


    $resourceModel->deleteResource(
        (int)$_POST['id']
    );


    header(
        'Location: ' . BASE_URL . '/staff/index.php?page=resources'
    );

    exit;
}
}