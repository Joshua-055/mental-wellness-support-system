<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Staff'));
$firstName = escape(explode(' ', trim((string) ($currentUser['full_name'] ?? 'Staff')))[0]);
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));
$dashboard = $dashboard ?? [];
$overview = $dashboard['overview'] ?? [];
$priorityRequests = $dashboard['priority_requests'] ?? [];
$todayAppointments = $dashboard['today_appointments'] ?? [];
$wellness = $dashboard['wellness'] ?? [];
$workflow = $dashboard['workflow'] ?? [];
$followUps = $dashboard['follow_ups'] ?? [];
$resourceStats = $dashboard['resources'] ?? [];
$pendingRequests = (int) ($overview['pending_requests'] ?? 0);
$highPriorityCases = (int) ($overview['high_priority_cases'] ?? 0);
$priorityItems = $highPriorityCases + (int) ($overview['remaining_appointments'] ?? 0);
$studentsThisMonth = (int) ($overview['students_this_month'] ?? 0);
$studentsLastMonth = (int) ($overview['students_last_month'] ?? 0);
$studentGrowth = $studentsLastMonth > 0 ? (int) round((($studentsThisMonth - $studentsLastMonth) / $studentsLastMonth) * 100) : null;
$recentCheckins = (int) ($wellness['recent_checkins'] ?? 0);
$previousCheckins = (int) ($wellness['previous_checkins'] ?? 0);
$checkinChange = $previousCheckins > 0 ? (int) round((($recentCheckins - $previousCheckins) / $previousCheckins) * 100) : null;
$greeting = (int) date('G') < 12 ? 'Good morning' : ((int) date('G') < 18 ? 'Good afternoon' : 'Good evening');
$dailyCheckins = $wellness['daily_counts'] ?? [];
$maxDailyCheckins = max(1, ...array_map(static fn(array $day): int => (int) $day['count'], $dailyCheckins));
$miniChartPoints = [];
foreach ($dailyCheckins as $index => $day) {
    $miniChartPoints[] = round(1 + ($index * 21), 1) . ',' . round(39 - (((int) $day['count'] / $maxDailyCheckins) * 35), 1);
}
?>
<main class="staff-app">
    <div class="staff-glow staff-glow-blue" aria-hidden="true"></div>
    <div class="staff-glow staff-glow-green" aria-hidden="true"></div>
    <?php $navigationRole = 'staff'; $activePage = 'dashboard'; require APP_ROOT . '/views/layouts/app-sidebar.php'; ?>
    <section class="staff-content">
        <?php $topbarTitle = 'Dashboard'; require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>
        <section class="staff-hero">
            <div>
                <p class="hero-overline"><span></span> <?= escape(strtoupper(date('l, F j'))) ?></p>
                <h1><?= escape($greeting) ?>, <?= $firstName ?> <span>👋</span></h1>
                <p>Here is your overview. You have <b><?= $priorityItems ?> priority item<?= $priorityItems === 1 ? '' : 's' ?></b> that may need your attention today.</p>
            </div>
            <div class="staff-orb" aria-hidden="true"><span>✦</span><i></i><b></b></div>
        </section>
        <section class="staff-stat-grid" aria-label="Today's overview">
            <article class="staff-stat glass-surface"><span class="stat-symbol blue">◎</span>
                <p>Pending requests</p><strong><?= $pendingRequests ?></strong><small><b><?= (int) ($overview['requests_this_week'] ?? 0) ?></b> added this week</small>
            </article>
            <article class="staff-stat glass-surface"><span class="stat-symbol red">!</span>
                <p>High priority cases</p><strong><?= $highPriorityCases ?></strong><small class="red-copy"><?= $highPriorityCases > 0 ? 'Needs attention today' : 'No urgent cases' ?></small>
            </article>
            <article class="staff-stat glass-surface"><span class="stat-symbol green">□</span>
                <p>Today's appointments</p><strong><?= (int) ($overview['today_appointments'] ?? 0) ?></strong><small><b><?= (int) ($overview['remaining_appointments'] ?? 0) ?></b> remaining today</small>
            </article>
            <article class="staff-stat glass-surface"><span class="stat-symbol purple">◉</span>
                <p>Active students</p><strong><?= (int) ($overview['active_students'] ?? 0) ?></strong><small><?php if ($studentGrowth !== null): ?><b><?= $studentGrowth >= 0 ? '↑' : '↓' ?> <?= abs($studentGrowth) ?>%</b> from last month<?php else: ?>Current active accounts<?php endif; ?></small>
            </article>
        </section>
        <section class="staff-main-grid">
            <article class="priority-panel glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">NEEDS ATTENTION</p>
                        <h2>Priority support requests</h2>
                    </div><a href="<?= BASE_URL ?>/staff/index.php?page=supportRequest">View all <span>→</span></a>
                </div>
                <div class="priority-table" role="table">
                    <div class="priority-row priority-header" role="row">
                        <span>STUDENT</span><span>CONCERN</span><span>PRIORITY</span><span>SUBMITTED</span><span>STATUS</span><span></span>
                    </div>
                    <?php foreach ($priorityRequests as $index => $request): $studentName = (string) $request['student_name']; $priority = (string) $request['priority']; $status = (string) $request['STATUS']; ?>
                        <div class="priority-row" role="row"><span class="student-cell"><i class="student-avatar a<?= ($index % 3) + 1 ?>"><?= escape(strtoupper(substr($studentName, 0, 1))) ?></i><b><?= escape($studentName) ?><small>STU-<?= str_pad((string) $request['student_id'], 5, '0', STR_PAD_LEFT) ?></small></b></span><span><?= escape((string) ($request['category_name'] ?: 'General wellness')) ?></span><span><b class="priority-badge <?= escape($priority) ?>">● <?= escape(ucfirst($priority)) ?></b></span><span><?= escape(date('d M, g:i A', strtotime((string) $request['created_at']))) ?></span><span><b class="request-status <?= $status === 'submitted' ? 'new' : 'review' ?>"><?= escape(ucwords(str_replace('_', ' ', $status))) ?></b></span><a href="<?= BASE_URL ?>/staff/index.php?page=supportRequest" aria-label="Open <?= escape($studentName) ?>'s request">→</a></div>
                    <?php endforeach; ?>
                    <?php if ($priorityRequests === []): ?><p class="dashboard-empty">There are no open support requests.</p><?php endif; ?>
                </div>
            </article>
            <article class="today-card glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">TODAY · <?= escape(strtoupper(date('F j'))) ?></p>
                        <h2>Appointments</h2>
                    </div><a href="<?= BASE_URL ?>/staff/index.php?page=appointment">Calendar <span>→</span></a>
                </div>
                <div class="calendar-list">
                    <?php foreach ($todayAppointments as $appointment): $time = strtotime((string) $appointment['appointment_time']); $isUpcoming = $time >= strtotime(date('H:i:s')); ?>
                        <div class="calendar-item<?= $isUpcoming ? ' current' : '' ?>"><time><?= escape(date('g:i', $time)) ?><small><?= escape(date('A', $time)) ?></small></time><span class="calendar-line"></span><div><b><?= escape((string) $appointment['student_name']) ?></b><small><?= escape(mb_strimwidth((string) ($appointment['reason'] ?: 'Wellness consultation'), 0, 42, '...')) ?></small></div><a href="<?= BASE_URL ?>/staff/index.php?page=appointment"><?= empty($appointment['staff_id']) ? 'Assign' : escape(ucfirst((string) $appointment['STATUS'])) ?> →</a></div>
                    <?php endforeach; ?>
                    <?php if ($todayAppointments === []): ?><p class="dashboard-empty">No appointments are scheduled for you today.</p><?php endif; ?>
                </div><a class="soft-button" href="<?= BASE_URL ?>/staff/index.php?page=appointment">Open day schedule <span>→</span></a>
            </article>
            <article class="analytics-panel glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">LAST 7 DAYS</p>
                        <h2>Student wellness overview</h2>
                    </div><span class="chart-select">This week</span>
                </div>
                <div class="analytics-cards">
                    <div class="analytic">
                        <p>Average stress</p><strong><?= $wellness['average_stress'] === null ? '—' : escape((string) $wellness['average_stress']) ?> <small>/ 5</small></strong>
                        <div class="mini-bars"><?php for ($level = 1; $level <= 5; $level++): ?><i<?= $level <= (int) round((float) ($wellness['average_stress'] ?? 0)) ? ' class="active"' : '' ?>></i><?php endfor; ?></div>
                        <small><?= $recentCheckins > 0 ? 'Across recent check-ins' : 'No check-ins this week' ?></small>
                    </div>
                    <div class="analytic mood-donut">
                        <p>Mood distribution</p>
                        <?php $positivePercent = (int) ($wellness['positive_percent'] ?? 0); ?><div class="donut" style="background:conic-gradient(#34c759 <?= $positivePercent ?>%, #e8edf2 0)"><b><?= $positivePercent ?><small>%</small></b></div><small><i></i> Positive or neutral</small>
                    </div>
                    <div class="analytic">
                        <p>Recent check-ins</p><strong><?= $recentCheckins ?></strong>
                        <div class="mini-chart"><svg viewBox="0 0 128 43" aria-hidden="true"><polyline points="<?= escape(implode(' ', $miniChartPoints)) ?>" /></svg></div><small><?php if ($checkinChange !== null): ?><b><?= $checkinChange >= 0 ? '↑' : '↓' ?> <?= abs($checkinChange) ?>%</b> compared to last week<?php else: ?>No previous-week comparison<?php endif; ?></small>
                    </div>
                </div>
            </article>
            <article class="workflow-panel glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">CASE FLOW</p>
                        <h2>Support request workflow</h2>
                    </div>
                </div>
                <div class="workflow">
                    <div class="workflow-step<?= (int) ($workflow['submitted'] ?? 0) > 0 ? ' active' : '' ?>"><i><?= (int) ($workflow['submitted'] ?? 0) ?></i><span>Submitted</span></div>
                    <div class="workflow-step<?= (int) ($workflow['under_review'] ?? 0) > 0 ? ' active' : '' ?>"><i><?= (int) ($workflow['under_review'] ?? 0) ?></i><span>Under review</span></div>
                    <div class="workflow-step<?= (int) ($workflow['follow_up_scheduled'] ?? 0) > 0 ? ' active' : '' ?>"><i><?= (int) ($workflow['follow_up_scheduled'] ?? 0) ?></i><span>Appointment</span></div>
                    <div class="workflow-step complete"><i><?= (int) ($workflow['resolved'] ?? 0) ?></i><span>Resolved</span></div>
                </div>
                <?php $activeCases = (int) ($workflow['submitted'] ?? 0) + (int) ($workflow['under_review'] ?? 0) + (int) ($workflow['follow_up_scheduled'] ?? 0); ?><p class="workflow-note"><span>✦</span> <?= $activeCases ?> case<?= $activeCases === 1 ? '' : 's' ?> currently moving through this workflow.</p>
            </article>
        </section>
        <section class="followup-grid">
            <article class="followup-panel glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">FOLLOW-UP INSIGHTS</p>
                        <h2>Recommended next steps</h2>
                    </div><a href="<?= BASE_URL ?>/staff/index.php?page=students">View students <span>→</span></a>
                </div>
                <div class="followup-list">
                    <?php foreach ($followUps as $index => $followUp): ?>
                        <div><span class="follow-icon <?= $index % 2 === 0 ? 'orange' : 'blue' ?>"><?= $index % 2 === 0 ? '⌁' : '◷' ?></span><p><b>High stress pattern detected</b><small><?= escape((string) $followUp['student_name']) ?> reported high stress in <?= (int) $followUp['high_stress_count'] ?> recent check-ins.</small></p><a href="<?= BASE_URL ?>/staff/index.php?page=student_detail&id=<?= (int) $followUp['student_id'] ?>">Review student →</a></div>
                    <?php endforeach; ?>
                    <?php if ($followUps === []): ?><p class="dashboard-empty">No repeated high-stress patterns were detected this week.</p><?php endif; ?>
                </div>
            </article>
            <article class="resource-summary glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">RESOURCE LIBRARY</p>
                        <h2>Resource management</h2>
                    </div><a href="<?= BASE_URL ?>/staff/index.php?page=resources">Manage <span>→</span></a>
                </div>
                <div class="resource-stats">
                    <span><b><?= (int) ($resourceStats['published'] ?? 0) ?></b>Published</span><span><b><?= (int) ($resourceStats['drafts'] ?? 0) ?></b>Drafts</span><span><b><?= (int) ($resourceStats['updated_this_month'] ?? 0) ?></b>Updated this month</span>
                </div>
            </article>
        </section>
        <section class="quick-actions staff-actions">
            <div class="section-heading">
                <div>
                    <p class="section-kicker">WORKSPACE</p>
                    <h2>Quick actions</h2>
                </div>
            </div>
            <div class="action-grid"><a href="<?= BASE_URL ?>/staff/index.php?page=resources" class="quick-action primary-action"><span>＋</span><b>Add
                        resource</b><small>Share useful support</small></a><a href="<?= BASE_URL ?>/staff/index.php?page=supportRequest"
                    class="quick-action"><span>◎</span><b>Assign case</b><small>Route it to a colleague</small></a><a
                    href="<?= BASE_URL ?>/staff/index.php?page=appointment" class="quick-action"><span>□</span><b>Schedule appointment</b><small>Create
                        a new session</small></a></div>
        </section>
    </section>
</main>
