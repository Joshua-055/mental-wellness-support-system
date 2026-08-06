<?php
$isStaff = in_array((string) ($navigationRole ?? ''), ['staff', 'admin'], true);
$appClass = $isStaff ? 'staff-app' : 'student-app';
$contentClass = $isStaff ? 'staff-content' : 'dashboard-content';
$sidebarClass = $isStaff ? 'staff-sidebar' : 'student-sidebar';
$activePage = 'resources';
$topbarTitle = 'Resources';
?>
<main class="<?= $appClass ?> resources-app">
    <?php if ($isStaff): ?>
        <div class="staff-glow staff-glow-blue" aria-hidden="true"></div>
        <div class="staff-glow staff-glow-green" aria-hidden="true"></div>
    <?php else: ?>
        <div class="dashboard-glow glow-one" aria-hidden="true"></div>
        <div class="dashboard-glow glow-two" aria-hidden="true"></div>
    <?php endif; ?>

    <?php require APP_ROOT . '/views/layouts/app-sidebar.php'; ?>

    <?php
    $resources = [

        [
            "id" => 1,
            "icon" => "🎓",
            "title" => "Academic Support",
            "description" => "Study skills workshops and academic guidance.",
            "services" => [
                "Study planning",
                "Academic coaching",
                "Time management support"
            ],
            "email" => "academic@university.edu",
            "phone" => "03-12345678",
            "location" => "Student Success Centre"
        ],

        [
            "id" => 2,
            "icon" => "🧠",
            "title" => "Counselling Services",
            "description" => "Professional counselling and emotional support.",
            "services" => [
                "Individual counselling",
                "Stress management",
                "Emotional support"
            ],
            "email" => "counselling@university.edu",
            "phone" => "03-87654321",
            "location" => "Wellness Centre"
        ],

        [
            "id" => 3,
            "icon" => "💰",
            "title" => "Financial Assistance",
            "description" => "Financial aid and scholarship information.",
            "services" => [
                "Financial management",
            ],
            "email" => "financial@university.edu",
            "phone" => "03-87654666",
            "location" => "Finance Centre"
        ],

        [
            "id" => 4,
            "icon" => "❤️",
            "title" => "Personal Development",
            "description" => "Workshops to improve confidence and communication.",
            "services" => [
                "Personal improvement",
            ],
            "email" => "Studentdev@university.edu",
            "phone" => "03-67654321",
            "location" => "Student Centre"
        ],

        [
            "id" => 5,
            "icon" => "👥",
            "title" => "Peer Support",
            "description" => "Student mentoring and peer support groups.",
            "services" => [
                "Support management",
                "Peer support"
            ],
            "email" => "support@university.edu",
            "phone" => "03-87687312",
            "location" => "Support Centre"
        ],

        [
            "id" => 6,
            "icon" => "📚",
            "title" => "Study Workshops",
            "description" => "Time management and exam preparation sessions.",
            "services" => [
                "Time management",
                "Exam support"
            ],
            "email" => "studyworkshop@university.edu",
            "phone" => "03-83324421",
            "location" => "Workshop Centre"
        ],

    ];

    ?>

    <section class="<?= $contentClass ?> resources-content">
        <?php require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>

        <section class="resources-heading">

            <div class="resources-title-row">

                <div>

                    <p class="section-kicker">
                        SUPPORT FOR YOU
                    </p>

                    <h1>
                        Mental wellness resources
                    </h1>

                    <p>
                        Practical, trustworthy guidance you can return to whenever you need it.
                    </p>

                </div>


                <?php if ($isStaff): ?>

                    <button class="btn btn-primary add-resource-btn" data-bs-toggle="offcanvas"
                        data-bs-target="#addResourcePanel">

                        + Add Resource

                    </button>


                <?php endif; ?>

            </div>


            </div>


        </section>

        <!-- Search -->

        <div class="resources-search">

            <input type="text" placeholder="Search category...">

        </div>

        <section class="resources-grid">

            <?php foreach ($resources as $resource): ?>

                <article class="resource-card glass-surface">

                    <div class="resource-icon">
                        <?= $resource['icon']; ?>
                    </div>


                    <div class="resource-body">

                        <h3>
                            <?= $resource['title']; ?>
                        </h3>

                        <p>
                            <?= $resource['description']; ?>
                        </p>

                    </div>


                    <div class="resource-actions">


                        <?php if ($isStaff): ?>

                            <button class="btn btn-sm btn-outline-primary">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-outline-danger">
                                Delete
                            </button>


                        <?php else: ?>


                            <button class="btn btn-link learn-more" data-bs-toggle="offcanvas"
                                data-bs-target="#resourcePanel<?= $resource['id']; ?>">

                                Learn More →

                            </button>


                        <?php endif; ?>


                    </div>


                </article>


            <?php endforeach; ?>

        </section>

        <!-- Resource Offcanvas -->

        <?php foreach ($resources as $resource): ?>

            <div class="offcanvas offcanvas-end resource-panel" id="resourcePanel<?= $resource['id']; ?>" tabindex="-1">


                <div class="offcanvas-header">


                    <h5 class="offcanvas-title">

                        <?= $resource['icon']; ?>

                        <?= $resource['title']; ?>

                    </h5>


                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                    </button>


                </div>



                <div class="offcanvas-body">


                    <p class="resource-description">

                        <?= $resource['description']; ?>

                    </p>



                    <h6>
                        What we provide
                    </h6>


                    <ul>

                        <?php foreach ($resource['services'] as $service): ?>

                            <li>
                                <?= $service ?>
                            </li>

                        <?php endforeach; ?>

                    </ul>




                    <h6>
                        Contact us
                    </h6>


                    <p>

                        ✉ <?= $resource['email']; ?>

                        <br>

                        ☎ <?= $resource['phone']; ?>

                    </p>




                    <h6>
                        Location
                    </h6>


                    <p>

                        <?= $resource['location']; ?>

                    </p>


                    <a href="<?= BASE_URL ?>/student/index.php?page=support_request" class="btn btn-primary w-100">

                        Request Appointment

                    </a>



                </div>


            </div>


        <?php endforeach; ?>

        <?php if ($isStaff): ?>

            <div class="offcanvas offcanvas-end resource-panel" id="addResourcePanel" tabindex="-1">


                <div class="offcanvas-header">


                    <h5 class="offcanvas-title">

                        ➕ Add Resource

                    </h5>


                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
                    </button>


                </div>



                <div class="offcanvas-body">


                    <form method="POST">


                        <div class="mb-3">

                            <label class="form-label">
                                Icon
                            </label>

                            <input type="text" name="icon" class="form-control" placeholder="Icon">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Title
                            </label>

                            <input type="text" name="title" class="form-control" placeholder="Title">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description" class="form-control" placeholder="Description"></textarea>

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Services
                            </label>

                            <textarea placeholder="Study planning, Academic coaching" name="services" class="form-control"></textarea>

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email" name="email" class="form-control" placeholder="Email">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Phone
                            </label>

                            <input type="text" name="phone" class="form-control" placeholder="Phone Number">

                        </div>



                        <div class="mb-3">

                            <label class="form-label">
                                Location
                            </label>

                            <input type="text" name="location" class="form-control" placeholder="Location">

                        </div>



                        <button class="btn btn-primary w-100">

                            Save Resource

                        </button>



                    </form>


                </div>


            </div>

        <?php endif; ?>

</main>