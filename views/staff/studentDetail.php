<?php
$moodLabels = ['excellent' => 'Excellent', 'good' => 'Good', 'neutral' => 'Neutral', 'stressed' => 'Stressed', 'overwhelmed' => 'Overwhelmed'];
$statusLabel = static fn(string $value): string => ucwords(str_replace('_', ' ', $value));
$initial = $student ? strtoupper(substr($student['full_name'], 0, 1)) : '?';
?>
<main class="staff-app">
    <div class="staff-glow staff-glow-blue" aria-hidden="true"></div><div class="staff-glow staff-glow-green" aria-hidden="true"></div>
    <?php $navigationRole = 'staff'; $activePage = 'students'; require APP_ROOT . '/views/layouts/app-sidebar.php'; ?>
    <section class="staff-content">
        <?php $topbarTitle = 'Student Detail'; require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>
        <a class="student-back" href="<?= BASE_URL ?>/staff/index.php?page=students">← Back to students</a>
        <?php if ($student === null): ?>
            <div class="student-empty glass-surface"><h1>Student not found</h1><p>The requested student account does not exist.</p></div>
        <?php else: ?>
            <section class="student-profile glass-surface">
                <span class="student-profile-avatar"><?= escape($initial) ?></span><div><p class="section-kicker">BASIC PROFILE</p><h1><?= escape($student['full_name']) ?></h1><p>Student ID #<?= (int) $student['id'] ?> · <?= (int) $student['is_active'] === 1 ? 'Active account' : 'Inactive account' ?><?= $canViewDetails ? ' · ' . escape($student['email']) : '' ?></p></div>
                <span class="access-badge <?= $canViewDetails ? 'assigned' : 'limited' ?>"><?= $canViewDetails ? 'Full access · Assigned' : 'Basic access only' ?></span>
            </section>
            <?php if (!$canViewDetails): ?>
                <section class="privacy-notice glass-surface"><span>🔒</span><div><h2>Detailed information is protected</h2><p>You can see this student's basic identity only. Mood, stress, wellness trends, support requests and appointments become available when a support request or appointment is assigned to you.</p></div></section>
            <?php else: $wellness = $detail['wellness']; $recent = $wellness['recent']; ?>
                <div class="student-stat-grid">
                    <article class="detail-card glass-surface"><small>Recent mood</small><strong><?= $recent ? escape($moodLabels[$recent['mood']] ?? $recent['mood']) : 'No data' ?></strong><span><?= $recent ? escape(date('j M Y', strtotime($recent['created_at']))) : 'No check-ins yet' ?></span></article>
                    <article class="detail-card glass-surface"><small>Current stress</small><strong><?= $recent ? (int) $recent['stress_level'] . ' / 5' : 'No data' ?></strong><span><?= $recent && (int) $recent['stress_level'] >= 4 ? 'Elevated attention suggested' : 'Latest reported level' ?></span></article>
                    <article class="detail-card glass-surface"><small>Check-in streak</small><strong><?= (int) $wellness['streak'] ?> days</strong><span>Consecutive daily check-ins</span></article>
                    <article class="detail-card glass-surface"><small>Follow-up status</small><strong><?= $recent && (int) $recent['needs_follow_up'] === 1 ? 'Required' : 'No flag' ?></strong><span>Based on latest check-in</span></article>
                </div>
                <div class="detail-two-column">
                    <section class="student-panel glass-surface"><div class="panel-title"><div><p class="section-kicker">WELLNESS TREND</p><h2>Last 7 days</h2></div></div><div class="trend-chart">
                        <?php foreach ($wellness['trend7'] as $point): ?><div class="trend-column"><span class="trend-value"><?= $point['score'] === null ? '—' : (int) $point['score'] ?></span><i style="height: <?= $point['score'] === null ? 5 : max(8, (int) $point['score']) ?>%" class="<?= $point['score'] === null ? 'empty' : '' ?>"></i><small><?= escape($point['label']) ?></small></div><?php endforeach; ?>
                    </div></section>
                    <section class="student-panel glass-surface"><div class="panel-title"><div><p class="section-kicker">MOOD DISTRIBUTION</p><h2>Last 30 days</h2></div></div><div class="mood-list">
                        <?php $totalMoods = max(1, array_sum($wellness['moods'])); foreach ($wellness['moods'] as $mood => $count): ?><div><span><?= escape($moodLabels[$mood]) ?></span><b><i style="width: <?= round($count / $totalMoods * 100) ?>%"></i></b><strong><?= (int) $count ?></strong></div><?php endforeach; ?>
                    </div></section>
                </div>
                <section class="student-panel glass-surface"><div class="panel-title"><div><p class="section-kicker">30-DAY VIEW</p><h2>Wellness activity</h2></div></div><div class="month-trend"><?php foreach ($wellness['trend30'] as $point): ?><span title="<?= escape($point['date']) ?>: <?= $point['score'] ?? 'No check-in' ?>" style="height: <?= $point['score'] === null ? 8 : max(12, (int) $point['score']) ?>%" class="<?= $point['score'] === null ? 'empty' : '' ?>"></span><?php endforeach; ?></div></section>
                <div class="detail-two-column lower-panels">
                    <section class="student-panel glass-surface"><div class="panel-title"><div><p class="section-kicker">RECENT SUPPORT</p><h2>Support requests</h2></div><span><?= count($detail['supportRequests']) ?></span></div>
                        <div class="record-list"><?php if ($detail['supportRequests'] === []): ?><p class="no-records">No support requests.</p><?php else: foreach ($detail['supportRequests'] as $request): ?><article><div><strong><?= escape($request['category_name'] ?? 'General support') ?></strong><p><?= escape($request['description']) ?></p></div><span class="record-status"><?= escape($statusLabel($request['STATUS'])) ?></span><small><?= escape($request['assigned_staff_name'] ?? 'Unassigned') ?> · <?= escape(date('j M Y', strtotime($request['created_at']))) ?></small></article><?php endforeach; endif; ?></div>
                    </section>
                    <section class="student-panel glass-surface"><div class="panel-title"><div><p class="section-kicker">NEXT SESSION</p><h2>Upcoming appointment</h2></div></div>
                        <?php $upcoming = $detail['upcomingAppointment']; if ($upcoming): ?><div class="upcoming-card"><strong><?= escape(date('D, j M Y', strtotime($upcoming['appointment_date']))) ?></strong><b><?= escape(date('g:i A', strtotime($upcoming['appointment_time']))) ?></b><p><?= escape($upcoming['reason'] ?? 'Wellness appointment') ?></p><small>Assigned staff: <?= escape($upcoming['staff_name'] ?? 'Unassigned') ?></small></div><?php else: ?><p class="no-records">No upcoming appointment.</p><?php endif; ?>
                        <div class="panel-title history-title"><div><p class="section-kicker">HISTORY</p><h2>Appointment history</h2></div></div><div class="record-list compact"><?php foreach (array_slice($detail['appointments'], 0, 6) as $appointment): ?><article><div><strong><?= escape(date('j M Y · g:i A', strtotime($appointment['appointment_date'] . ' ' . $appointment['appointment_time']))) ?></strong><p><?= escape($appointment['reason'] ?? 'Wellness appointment') ?></p></div><span class="record-status"><?= escape($statusLabel($appointment['STATUS'])) ?></span><small><?= escape($appointment['staff_name'] ?? 'Unassigned') ?></small></article><?php endforeach; ?></div>
                    </section>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</main>
