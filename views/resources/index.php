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


        </section>



        <!-- Search -->

        <div class="resources-search">

    <form method="GET">

        <input 
            type="hidden"
            name="page"
            value="resources">


        <span class="search-icon" aria-hidden="true">⌕</span>

        <input 
            type="search"
            name="search"
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
            placeholder="Search resources..."
            aria-label="Search resources">

    </form>

</div>

        <!-- Resource Cards -->

        <section class="resources-grid">


            <?php foreach ($resources as $resource): ?>


                <article class="resource-card glass-surface">


                    <div class="resource-body">


                        <h3>
                            <?= htmlspecialchars($resource['title']); ?>
                        </h3>


                        <p>
                            <?= htmlspecialchars($resource['description']); ?>
                        </p>


                    </div>



                    <div class="resource-actions">


                        <?php if ($isStaff): ?>


                            <button type="button" class="resource-icon-btn edit-btn" data-bs-toggle="offcanvas"
                                data-bs-target="#editResourcePanel<?= $resource['id']; ?>" title="Edit resource"
                                aria-label="Edit <?= htmlspecialchars($resource['title']); ?>">
                                ✎
                            </button>


                            <form method="POST" action="?page=resources&action=delete"
                                onsubmit="return confirm('Are you sure you want to delete this resource?');">

                                <input type="hidden" name="id" value="<?= $resource['id']; ?>">

                                <button type="submit" class="resource-icon-btn delete-btn" title="Delete resource"
                                    aria-label="Delete <?= htmlspecialchars($resource['title']); ?>">
                                    🗑
                                </button>

                            </form>



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


    </section>

</main>
<?php if ($isStaff): ?>


    <!-- Add Resource Offcanvas -->

    <div class="offcanvas offcanvas-end resource-panel" id="addResourcePanel" tabindex="-1">


        <div class="offcanvas-header">


            <h5 class="offcanvas-title">

                Add Resource

            </h5>



            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">

            </button>


        </div>




        <div class="offcanvas-body">


            <form method="POST" action="?page=resources&action=store">



                <!-- Category -->


                <div class="mb-3">


                    <label class="form-label">

                        Category

                    </label>



                    <select name="category_id" class="form-select" required>


                        <option value="">
                            Select Category
                        </option>


                        <option value="1">
                            Academic Support
                        </option>


                        <option value="2">
                            Counselling
                        </option>


                        <option value="3">
                            Financial Assistance
                        </option>


                        <option value="4">
                            Career Guidance
                        </option>


                        <option value="5">
                            Health & Wellness
                        </option>


                        <option value="6">
                            Other
                        </option>


                    </select>


                </div>





                <!-- Title -->


                <div class="mb-3">


                    <label class="form-label">

                        Title

                    </label>



                    <input type="text" name="title" class="form-control" placeholder="Resource title" required>


                </div>





                <!-- Description -->


                <div class="mb-3">


                    <label class="form-label">

                        Description

                    </label>



                    <textarea name="description" class="form-control" rows="4" placeholder="Resource description"
                        required></textarea>


                </div>





                <!-- Services -->


                <div class="mb-3">


                    <label class="form-label">

                        Services

                    </label>



                    <textarea name="services" class="form-control" rows="4"
                        placeholder="Example: Academic coaching, study planning" required></textarea>


                </div>





                <!-- Email -->


                <div class="mb-3">


                    <label class="form-label">

                        Email

                    </label>



                    <input type="email" name="email" class="form-control" placeholder="Email address" required>


                </div>





                <!-- Phone -->


                <div class="mb-3">


                    <label class="form-label">

                        Phone

                    </label>



                    <input type="text" name="phone" class="form-control" placeholder="Phone number" required>


                </div>





                <!-- Location -->


                <div class="mb-3">


                    <label class="form-label">

                        Location

                    </label>



                    <input type="text" name="location" class="form-control" placeholder="Location" required>


                </div>





                <button type="submit" class="btn btn-primary w-100">


                    Save Resource


                </button>




            </form>



        </div>



    </div>



<?php endif; ?>

<?php if ($isStaff): ?>

    <?php foreach ($resources as $resource): ?>

        <div class="offcanvas offcanvas-end resource-panel" id="editResourcePanel<?= $resource['id']; ?>" tabindex="-1">


            <div class="offcanvas-header">

                <h5 class="offcanvas-title">
                    Edit Resource
                </h5>


                <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
                </button>


            </div>


            <div class="offcanvas-body">


                <form method="POST" action="?page=resources&action=update">


                    <input type="hidden" name="id" value="<?= $resource['id']; ?>">



                    <div class="mb-3">

                        <label class="form-label">
                            Title
                        </label>


                        <input type="text" name="title" class="form-control"
                            value="<?= htmlspecialchars($resource['title']); ?>">

                    </div>



                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>


                        <textarea name="description"
                            class="form-control"><?= htmlspecialchars($resource['description']); ?></textarea>


                    </div>



                    <div class="mb-3">

                        <label class="form-label">
                            Services
                        </label>


                        <textarea name="services"
                            class="form-control"><?= htmlspecialchars(implode(', ', $resource['services'])); ?></textarea>


                    </div>



                    <div class="mb-3">

                        <label class="form-label">
                            Email
                        </label>


                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($resource['email']); ?>">


                    </div>




                    <div class="mb-3">

                        <label class="form-label">
                            Phone
                        </label>


                        <input type="text" name="phone" class="form-control"
                            value="<?= htmlspecialchars($resource['phone']); ?>">


                    </div>



                    <div class="mb-3">

                        <label class="form-label">
                            Location
                        </label>


                        <input type="text" name="location" class="form-control"
                            value="<?= htmlspecialchars($resource['location']); ?>">


                    </div>



                    <button class="btn btn-primary w-100">

                        Update Resource

                    </button>


                </form>


            </div>


        </div>


    <?php endforeach; ?>

<?php endif; ?>

<!-- Student Resource Detail Offcanvas -->


<?php foreach ($resources as $resource): ?>


    <div class="offcanvas offcanvas-end resource-panel" id="resourcePanel<?= $resource['id']; ?>" tabindex="-1">


        <div class="offcanvas-header">


            <h5 class="offcanvas-title">

                <?= htmlspecialchars($resource['title']); ?>

            </h5>


            <button type="button" class="btn-close" data-bs-dismiss="offcanvas">

            </button>


        </div>




        <div class="offcanvas-body">


            <p class="resource-description">

                <?= htmlspecialchars($resource['description']); ?>

            </p>



            <h6>
                What we provide
            </h6>


            <p>

                <?= htmlspecialchars(implode(', ', $resource['services'])); ?>

            </p>



            <h6>
                Contact us
            </h6>


            <p>

                ✉ <?= htmlspecialchars($resource['email']); ?>

                <br>

                ☎ <?= htmlspecialchars($resource['phone']); ?>

            </p>



            <h6>
                Location
            </h6>


            <p>

                <?= htmlspecialchars($resource['location']); ?>

            </p>




            <a href="<?= BASE_URL ?>/student/index.php?page=support_request" class="btn btn-primary w-100">


                Request Appointment


            </a>



        </div>



    </div>



<?php endforeach; ?>
