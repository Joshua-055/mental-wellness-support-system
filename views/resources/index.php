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

        <header class="resources-heading">
            <p class="section-kicker"><?= $isStaff ? 'RESOURCE LIBRARY' : 'SUPPORT FOR YOU' ?></p>
            <h1>Mental wellness resources</h1>
            <p><?= $isStaff ? 'Publish and maintain helpful support materials for students.' : 'Practical, trustworthy guidance you can return to whenever you need it.' ?></p>
        </header>

        <section class="resources-grid" aria-label="Resource categories">
            <article class="resource-library-card glass-surface"><span>⌇</span><div><h2>Managing academic stress</h2><p>Simple ways to plan your workload, reset expectations and ask for help early.</p></div><a href="#" aria-disabled="true">Content coming soon</a></article>
            <article class="resource-library-card glass-surface"><span>☼</span><div><h2>Mindfulness exercises</h2><p>Short breathing and grounding practices designed for busy student schedules.</p></div><a href="#" aria-disabled="true">Content coming soon</a></article>
            <article class="resource-library-card glass-surface"><span>♡</span><div><h2>Campus counselling</h2><p>Learn what to expect and how to prepare before speaking with a counsellor.</p></div><a href="#" aria-disabled="true">Content coming soon</a></article>
        </section>
    </section>
</main>
