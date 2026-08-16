<?php $assignedCount = count(array_filter($students, static fn(array $s): bool => (bool) $s['is_assigned'])); ?>
<main class="staff-app">
    <div class="staff-glow staff-glow-blue" aria-hidden="true"></div><div class="staff-glow staff-glow-green" aria-hidden="true"></div>
    <?php $navigationRole = 'staff'; $activePage = 'students'; require APP_ROOT . '/views/layouts/app-sidebar.php'; ?>
    <section class="staff-content">
        <?php $topbarTitle = 'Students'; require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>
        <header class="students-heading"><div><p class="section-kicker">STUDENT DIRECTORY</p><h1>Student overview</h1><p>Detailed wellness information is available only for students assigned to you.</p></div><span><?= $assignedCount ?> assigned</span></header>
        <?php if ($students === []): ?>
            <div class="student-empty glass-surface">No student accounts found.</div>
        <?php else: ?>
            <div class="student-card-grid">
                <?php foreach ($students as $student): $assigned = (bool) $student['is_assigned']; ?>
                    <article class="student-card glass-surface <?= $assigned ? 'is-assigned' : '' ?>">
                        <div class="student-card-top"><span class="student-initial"><?= escape(strtoupper(substr($student['full_name'], 0, 1))) ?></span><span class="access-badge <?= $assigned ? 'assigned' : 'limited' ?>"><?= $assigned ? 'Assigned to you' : 'Limited view' ?></span></div>
                        <h2><?= escape($student['full_name']) ?></h2><p>Student ID #<?= (int) $student['id'] ?></p>
                        <div class="student-card-meta"><?php if ($assigned): ?><span><?= (int) $student['request_count'] ?> support requests</span><span><?= (int) $student['appointment_count'] ?> appointments</span><?php else: ?><span><?= (int) $student['is_active'] === 1 ? 'Active account' : 'Inactive account' ?></span><span>Joined <?= escape(date('M Y', strtotime($student['created_at']))) ?></span><?php endif; ?></div>
                        <a class="student-detail-button" href="<?= BASE_URL ?>/staff/index.php?page=student_detail&amp;student_id=<?= (int) $student['id'] ?>"><?= $assigned ? 'View full details' : 'View basic profile' ?> <span>→</span></a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>
