<?php
$displayName = escape((string) ($currentUser['full_name'] ?? 'Staff'));
$firstName = escape(explode(' ', trim((string) ($currentUser['full_name'] ?? 'Staff')))[0]);
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'S')), 0, 1)));
?>
<main class="staff-app">
    <div class="staff-glow staff-glow-blue" aria-hidden="true"></div>
    <div class="staff-glow staff-glow-green" aria-hidden="true"></div>
    <aside class="staff-sidebar glass-surface" aria-label="Staff navigation">
        <a class="brand dashboard-brand" href="../index.php"><span class="brand-mark"
                aria-hidden="true"><span></span><span></span><span></span></span><span>mindful</span></a>
        <p class="staff-space-label">STAFF SPACE</p>
       <nav class="side-nav">
            <a class="side-link active" href="./"><span class="nav-icon">⌂</span>Dashboard</a>
            
                <!-- Support Requests 导航：一直显示数字，如果没有就显示 0 -->
            <a class="side-link <?= ($page === 'supportRequest') ? 'active' : '' ?>" href="index.php?page=supportRequest">
                <span class="nav-icon">◎</span>Support Requests
                <b class="side-count"><?= $unassignedRequestsCount ?? 0 ?></b>
            </a>
            
            <!-- Appointments 导航 -->
            <a class="side-link <?= ($page === 'appointment') ? 'active' : '' ?>" href="index.php?page=appointment">
                <span class="nav-icon">□</span>Appointments
                <?php if (!empty($unassignedAppointmentsCount) && $unassignedAppointmentsCount > 0): ?>
                    <b class="side-count"><?= $unassignedAppointmentsCount ?></b>
                <?php endif; ?>
            </a>

            <a class="side-link" href="#"><span class="nav-icon">◉</span>Students</a>
            <a class="side-link" href="resources.php"><span class="nav-icon">▤</span>Resources</a>
            <a class="side-link" href="#"><span class="nav-icon">⌁</span>Reports</a>
        </nav>
        <div class="sidebar-bottom"><a class="side-link" href="#"><span class="nav-icon">⚙</span>Settings</a><a
                class="side-link staff-logout" href="<?= BASE_URL ?>/auth/logout.php"><span class="nav-icon">↗</span>Log
                out</a></div>
    </aside>
    <section class="staff-content">
        <header class="dashboard-topbar glass-surface"><button class="mobile-menu" type="button"
                aria-label="Open navigation">☰</button>
            <div class="topbar-breadcrumb"><span>Staff space</span><strong>Dashboard</strong></div>
            <div class="topbar-actions"><button class="notification-button" type="button"
                    aria-label="You have 4 notifications"><span>♢</span><i></i></button><a class="profile-chip"
                    href="#"><span class="avatar staff-avatar"><?= $initial ?></span><span
                        class="profile-name"><?= $displayName ?> <small>Wellness counsellor</small></span><span
                        class="chevron">⌄</span></a></div>
        </header>
        <section class="staff-hero">
            <div>
                <p class="hero-overline"><span></span> MONDAY, JULY 21</p>
                <h1>Good morning, <?= $firstName ?> <span>👋</span></h1>
                <p>Here is your overview. You have <b>3 priority items</b> that may need your attention today.</p>
            </div>
            <div class="staff-orb" aria-hidden="true"><span>✦</span><i></i><b></b></div>
        </section>
        <section class="staff-stat-grid" aria-label="Today's overview">
            <article class="staff-stat glass-surface"><span class="stat-symbol blue">◎</span>
                <p>Pending requests</p><strong>12</strong><small><b>4</b> added this week</small>
            </article>
            <article class="staff-stat glass-surface"><span class="stat-symbol red">!</span>
                <p>High priority cases</p><strong>3</strong><small class="red-copy">Needs attention today</small>
            </article>
            <article class="staff-stat glass-surface"><span class="stat-symbol green">□</span>
                <p>Today's appointments</p><strong>5</strong><small><b>2</b> remaining today</small>
            </article>
            <article class="staff-stat glass-surface"><span class="stat-symbol purple">◉</span>
                <p>Active students</p><strong>148</strong><small><b>↑ 12%</b> from last month</small>
            </article>
        </section>
        <section class="staff-main-grid">
            <article class="priority-panel glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">NEEDS ATTENTION</p>
                        <h2>Priority support requests</h2>
                    </div><a href="support-request.php">View all <span>→</span></a>
                </div>
                <div class="priority-table" role="table">
                    <div class="priority-row priority-header" role="row">
                        <span>STUDENT</span><span>CONCERN</span><span>PRIORITY</span><span>SUBMITTED</span><span>STATUS</span><span></span>
                    </div>
                    <div class="priority-row" role="row"><span class="student-cell"><i
                                class="student-avatar a1">A</i><b>Aisyah
                                Rahman<small>STU-24108</small></b></span><span>Academic stress</span><span><b
                                class="priority-badge high">● High</b></span><span>Today, 9:24 AM</span><span><b
                                class="request-status review">Under review</b></span><a href="support-request.php"
                            aria-label="Open Aisyah Rahman's request">→</a></div>
                    <div class="priority-row" role="row"><span class="student-cell"><i
                                class="student-avatar a2">D</i><b>Daniel
                                Wong<small>STU-21942</small></b></span><span>Personal wellbeing</span><span><b
                                class="priority-badge medium">● Medium</b></span><span>Yesterday</span><span><b
                                class="request-status assigned">Assigned</b></span><a href="support-request.php"
                            aria-label="Open Daniel Wong's request">→</a></div>
                    <div class="priority-row" role="row"><span class="student-cell"><i
                                class="student-avatar a3">N</i><b>Nur
                                Iman<small>STU-24162</small></b></span><span>Financial support</span><span><b
                                class="priority-badge high">● High</b></span><span>18 Jul</span><span><b
                                class="request-status new">New</b></span><a href="support-request.php"
                            aria-label="Open Nur Iman's request">→</a></div>
                </div>
            </article>
            <article class="today-card glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">TODAY · JULY 21</p>
                        <h2>Appointments</h2>
                    </div><a href="appointments.php">Calendar <span>→</span></a>
                </div>
                <div class="calendar-list">
                    <div class="calendar-item current"><time>10:30<small>AM</small></time><span
                            class="calendar-line"></span>
                        <div><b>Alex Tan</b><small>Follow-up counselling</small></div><em>In 18 min</em>
                    </div>
                    <div class="calendar-item"><time>1:00<small>PM</small></time><span class="calendar-line"></span>
                        <div><b>Mei Ling</b><small>Initial consultation</small></div><a href="appointments.php">Start
                            →</a>
                    </div>
                    <div class="calendar-item"><time>2:30<small>PM</small></time><span class="calendar-line"></span>
                        <div><b>Joshua Lee</b><small>Academic support</small></div><a href="appointments.php">Details
                            →</a>
                    </div>
                </div><a class="soft-button" href="appointments.php">Open day schedule <span>→</span></a>
            </article>
            <article class="analytics-panel glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">LAST 7 DAYS</p>
                        <h2>Student wellness overview</h2>
                    </div><button class="chart-select" type="button">This week <span>⌄</span></button>
                </div>
                <div class="analytics-cards">
                    <div class="analytic">
                        <p>Average stress</p><strong>3.2 <small>/ 5</small></strong>
                        <div class="mini-bars"><i></i><i></i><i></i><i class="active"></i><i class="active"></i></div>
                        <small>Moderate across check-ins</small>
                    </div>
                    <div class="analytic mood-donut">
                        <p>Mood distribution</p>
                        <div class="donut"><b>62<small>%</small></b></div><small><i></i> Positive or neutral</small>
                    </div>
                    <div class="analytic">
                        <p>Recent check-ins</p><strong>86</strong>
                        <div class="mini-chart"><svg viewBox="0 0 128 43" aria-hidden="true">
                                <path d="M1 36 C15 33,23 40,35 28 S55 19,68 26 S85 35,96 17 S113 13,127 4" />
                            </svg></div><small><b>↑ 14%</b> compared to last week</small>
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
                    <div class="workflow-step complete"><i>✓</i><span>Submitted</span></div>
                    <div class="workflow-step complete"><i>✓</i><span>Under review</span></div>
                    <div class="workflow-step active"><i></i><span>Assigned</span></div>
                    <div class="workflow-step"><i></i><span>Appointment</span></div>
                    <div class="workflow-step"><i></i><span>Resolved</span></div>
                </div>
                <p class="workflow-note"><span>✦</span> 8 cases are currently moving through this workflow.</p>
            </article>
        </section>
        <section class="followup-grid">
            <article class="followup-panel glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">FOLLOW-UP INSIGHTS</p>
                        <h2>Recommended next steps</h2>
                    </div><a href="#">View all <span>→</span></a>
                </div>
                <div class="followup-list">
                    <div><span class="follow-icon orange">⌁</span>
                        <p><b>High stress pattern detected</b><small>Nur Iman has reported high stress for 3 consecutive
                                check-ins.</small></p><a href="appointments.php">Schedule session →</a>
                    </div>
                    <div><span class="follow-icon blue">◷</span>
                        <p><b>Appointment follow-up</b><small>Daniel Wong has missed 2 recent appointments.</small></p>
                        <a href="#">Send reminder →</a>
                    </div>
                </div>
            </article>
            <article class="resource-summary glass-surface">
                <div class="section-heading">
                    <div>
                        <p class="section-kicker">RESOURCE LIBRARY</p>
                        <h2>Resource management</h2>
                    </div><a href="resources.php">Manage <span>→</span></a>
                </div>
                <div class="resource-stats">
                    <span><b>24</b>Published</span><span><b>3</b>Drafts</span><span><b>6</b>Updated this month</span>
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
            <div class="action-grid"><a href="resources.php" class="quick-action primary-action"><span>＋</span><b>Add
                        resource</b><small>Share useful support</small></a><a href="support-request.php"
                    class="quick-action"><span>◎</span><b>Assign case</b><small>Route it to a colleague</small></a><a
                    href="appointments.php" class="quick-action"><span>□</span><b>Schedule appointment</b><small>Create
                        a new session</small></a><a href="#" class="quick-action"><span>⌁</span><b>Generate
                        report</b><small>Review wellbeing trends</small></a></div>
        </section>
    </section>
</main>