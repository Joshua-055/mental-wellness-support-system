<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Student'));
$firstName = escape(explode(' ', trim((string) ($currentUser['full_name'] ?? 'Student')))[0]);
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));
$dashboard = $dashboard ?? [];
$wellness = $dashboard['wellness'] ?? ['average' => null, 'streak' => 0, 'trend' => []];
$latestCheckin = $dashboard['latest_checkin'] ?? null;
$stressLevel = (int) ($latestCheckin['stress_level'] ?? 0);
$stressLabels = [0 => 'No data', 1 => 'Very low', 2 => 'Low', 3 => 'Moderate', 4 => 'High', 5 => 'Very high'];
$wellnessScore = $wellness['average'] ?? null;
$scoreLabel = $wellnessScore === null ? 'No data' : ($wellnessScore >= 80 ? 'Good' : ($wellnessScore >= 60 ? 'Steady' : 'Needs care'));
$upcomingAppointment = $dashboard['upcoming_appointment'] ?? null;
$supportRequest = $dashboard['latest_support_request'] ?? null;
$resources = $dashboard['resources'] ?? [];
$greeting = (int) date('G') < 12 ? 'Good morning' : ((int) date('G') < 18 ? 'Good afternoon' : 'Good evening');
$trend = $wellness['trend'] ?? [];
$chartPoints = [];
foreach ($trend as $index => $point) {
    if ($point['score'] !== null) {
        $chartPoints[] = round(8 + ($index * 95.6), 1) . ',' . round(150 - ((int) $point['score'] * 1.18), 1);
    }
}
?>
<main class="student-app">
    <div class="dashboard-glow glow-one" aria-hidden="true"></div>
    <div class="dashboard-glow glow-two" aria-hidden="true"></div>

    <?php $activePage = 'dashboard'; require APP_ROOT . '/views/layouts/app-sidebar.php'; ?>

    <section class="dashboard-content">
        <?php $topbarTitle = 'Dashboard'; require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>

        <section class="dashboard-hero" aria-labelledby="greeting-title">
            <div>
                <p class="hero-overline"><span></span> <?= escape(strtoupper(date('l, F j'))) ?></p>
                <h1 id="greeting-title"><?= escape($greeting) ?>, <?= $firstName ?> <span>👋</span></h1>
                <p>How are you feeling today? A moment for yourself can change the pace of your day.</p>
            </div>
            <div class="hero-orb" aria-hidden="true"><div class="orb-core">✦</div><i></i><b></b></div>
        </section>

        <section class="mood-section glass-surface" aria-labelledby="mood-title">
            <div class="section-heading inline-heading"><div><p class="section-kicker">DAILY CHECK-IN</p><h2 id="mood-title"><?= !empty($dashboard['has_checkin_today']) ? 'Today\'s check-in is complete' : 'Choose your mood' ?></h2></div><a href="index.php?page=checkin">Full check-in <span>→</span></a></div>
            <div class="mood-options" role="group" aria-label="How are you feeling today?">
                <button class="mood-option" type="button" data-mood="excellent"><span>😊</span><strong>Excellent</strong><small>Feeling great</small></button>
                <button class="mood-option" type="button" data-mood="good"><span>🙂</span><strong>Good</strong><small>Feeling steady</small></button>
                <button class="mood-option" type="button" data-mood="neutral" aria-pressed="false"><span>😐</span><strong>Neutral</strong><small>Taking it easy</small></button>
                <button class="mood-option" type="button" data-mood="stressed"><span>😔</span><strong>Stressed</strong><small>Feeling tense</small></button>
                <button class="mood-option" type="button" data-mood="overwhelmed"><span>😢</span><strong>Overwhelmed</strong><small>Need a pause</small></button>
            </div>
            <p class="mood-feedback" role="status" aria-live="polite"><?= !empty($dashboard['has_checkin_today']) ? 'Thank you for checking in today. You can check in again tomorrow.' : 'Choose a mood to continue your daily check-in.' ?></p>
        </section>

        <section class="summary-grid" aria-label="Today's wellness summary">
            <article class="summary-card glass-surface"><div class="summary-label"><span class="summary-icon blue">⌁</span><span>Current stress</span><button aria-label="Stress information">i</button></div><div class="summary-stat"><strong><?= escape($stressLabels[$stressLevel] ?? 'No data') ?></strong><span><?= $stressLevel ?> <small>/ 5</small></span></div><div class="level-bars"><?php for ($level = 1; $level <= 5; $level++): ?><i<?= $level <= $stressLevel ? ' class="filled"' : '' ?>></i><?php endfor; ?></div><p><?= $latestCheckin === null ? 'Complete a check-in to see your stress level.' : 'Based on your latest check-in.' ?></p></article>
            <article class="summary-card glass-surface"><div class="summary-label"><span class="summary-icon green">✦</span><span>Wellness score</span><button aria-label="Wellness score information">i</button></div><div class="summary-stat"><strong><?= $wellnessScore === null ? '—' : (int) $wellnessScore . ' <small>/ 100</small>' ?></strong><div class="ring" style="--p:<?= (int) ($wellnessScore ?? 0) ?>%"><span><?= escape($scoreLabel) ?></span></div></div><p><?= $wellnessScore === null ? 'Your 7-day score will appear here.' : 'Average from your last 7 days.' ?></p></article>
            <article class="summary-card glass-surface"><div class="summary-label"><span class="summary-icon orange">◉</span><span>Check-in streak</span><button aria-label="Check-in streak information">i</button></div><div class="summary-stat"><strong><?= (int) ($wellness['streak'] ?? 0) ?> days</strong><span class="streak-fire">🔥</span></div><div class="streak-days"><?php foreach ($trend as $point): ?><?= $point['score'] !== null ? '<b>' : '<i>' ?><?= escape(substr((string) $point['label'], 0, 1)) ?><?= $point['score'] !== null ? '</b>' : '</i>' ?><?php endforeach; ?></div><p><?= (int) ($wellness['streak'] ?? 0) > 0 ? 'Keep your mindful streak going.' : 'Start your streak with today\'s check-in.' ?></p></article>
        </section>

        <section class="dashboard-grid">
            <article class="resources-panel glass-surface">
                <div class="section-heading"><div><p class="section-kicker">JUST FOR YOU</p><h2>Recommended resources</h2></div><a href="<?= BASE_URL ?>/student/index.php?page=resources">See all <span>→</span></a></div>
                <div class="resource-carousel">
                    <?php $resourceStyles = ['blue-resource', 'green-resource', 'lilac-resource']; $resourceIcons = ['⌇', '☼', '◌']; ?>
                    <?php foreach ($resources as $index => $resource): ?>
                        <a class="resource-card <?= $resourceStyles[$index % 3] ?>" href="<?= BASE_URL ?>/student/index.php?page=resources"><span class="resource-icon"><?= $resourceIcons[$index % 3] ?></span><div><h3><?= escape((string) $resource['title']) ?></h3><p><?= escape(mb_strimwidth((string) $resource['description'], 0, 92, '...')) ?></p></div><span class="resource-link">Explore →</span></a>
                    <?php endforeach; ?>
                    <?php if ($resources === []): ?><p class="dashboard-empty">No active resources are available yet.</p><?php endif; ?>
                </div>
            </article>

            <article class="appointment-card glass-surface">
                <div class="section-heading"><div><p class="section-kicker">UPCOMING</p><h2>Appointment</h2></div><?php if ($upcomingAppointment): ?><span class="status-badge"><?= escape(ucfirst((string) $upcomingAppointment['STATUS'])) ?></span><?php endif; ?></div>
                <?php if ($upcomingAppointment): $appointmentDate = new DateTimeImmutable((string) $upcomingAppointment['appointment_date']); ?>
                    <div class="appointment-date"><span class="date-box"><b><?= $appointmentDate->format('j') ?></b><small><?= escape(strtoupper($appointmentDate->format('D'))) ?></small></span><div><h3><?= escape(date('g:i A', strtotime((string) $upcomingAppointment['appointment_time']))) ?></h3><p><?= !empty($upcomingAppointment['staff_name']) ? 'with ' . escape((string) $upcomingAppointment['staff_name']) : 'Staff assignment pending' ?></p></div></div>
                    <div class="appointment-meta"><span>◉</span> <?= escape((string) ($upcomingAppointment['reason'] ?: 'Wellness support session')) ?></div>
                <?php else: ?><p class="dashboard-empty">You have no upcoming appointment.</p><?php endif; ?>
                <a class="soft-button" href="index.php?page=appointments"><?= $upcomingAppointment ? 'View details' : 'Book appointment' ?> <span>→</span></a>
            </article>

            <article class="history-card glass-surface">
                <div class="section-heading"><div><p class="section-kicker">PAST 7 DAYS</p><h2>Wellness history</h2></div><a class="chart-select" href="index.php?page=checkin_history">History <span>→</span></a></div>
                <?php if ($chartPoints !== []): ?><div class="chart-legend"><span><i></i> Your mood score</span><em><?= count($chartPoints) ?> check-in<?= count($chartPoints) === 1 ? '' : 's' ?></em></div><div class="line-chart" aria-label="Mood trend for the past 7 days"><svg viewBox="0 0 590 166" role="img"><polyline class="chart-line" points="<?= escape(implode(' ', $chartPoints)) ?>"/><?php foreach ($chartPoints as $point): [$cx, $cy] = explode(',', $point); ?><circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="4"/><?php endforeach; ?></svg><div class="chart-labels"><?php foreach ($trend as $point): ?><span><?= escape((string) $point['label']) ?></span><?php endforeach; ?></div></div><?php else: ?><p class="dashboard-empty">Your mood trend will appear after your first check-in.</p><?php endif; ?>
            </article>

            <article class="support-card glass-surface">
                <div class="section-heading"><div><p class="section-kicker">YOUR REQUEST</p><h2>Support status</h2></div><a href="index.php?page=support_request">View <span>→</span></a></div>
                <?php if ($supportRequest): $statusIndex = ['submitted' => 0, 'under_review' => 1, 'follow_up_scheduled' => 2, 'resolved' => 3, 'closed' => 3][(string) $supportRequest['STATUS']] ?? 0; $steps = ['Submitted', 'Under review', 'Appointment scheduled', 'Completed']; ?>
                    <div class="support-timeline"><?php foreach ($steps as $index => $step): $class = $index < $statusIndex || $statusIndex === 3 ? 'done' : ($index === $statusIndex ? 'current' : ''); ?><div class="timeline-step <?= $class ?>"><i><?= $class === 'done' ? '✓' : '' ?></i><div><strong><?= escape($step) ?></strong><?php if ($index === 0): ?><small><?= escape(date('j M Y, g:i A', strtotime((string) $supportRequest['created_at']))) ?></small><?php endif; ?></div></div><?php endforeach; ?></div>
                <?php else: ?><p class="dashboard-empty">You have not submitted a support request.</p><a class="soft-button" href="index.php?page=create_support_request">Request support <span>→</span></a><?php endif; ?>
            </article>
        </section>

        <section class="quick-actions" aria-labelledby="quick-title"><div class="section-heading"><div><p class="section-kicker">TAKE THE NEXT STEP</p><h2 id="quick-title">Quick actions</h2></div></div><div class="action-grid"><a href="index.php?page=checkin" class="quick-action primary-action"><span>＋</span><b>New check-in</b><small>Take a moment</small></a><a href="index.php?page=appointments" class="quick-action"><span>□</span><b>Book appointment</b><small>Talk with someone</small></a><a href="index.php?page=create_support_request" class="quick-action"><span>◎</span><b>Request support</b><small>We are here for you</small></a><a href="<?= BASE_URL ?>/student/index.php?page=resources" class="quick-action"><span>▤</span><b>Browse resources</b><small>Find what helps</small></a></div></section>
    </section>
</main>
