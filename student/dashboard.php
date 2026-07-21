<?php
$pageTitle = 'Dashboard | Mindful';
$pageStyles = ['student-dashboard'];
include '../includes/header.php';
?>

<main class="student-app">
    <div class="dashboard-glow glow-one" aria-hidden="true"></div>
    <div class="dashboard-glow glow-two" aria-hidden="true"></div>

    <aside class="student-sidebar glass-surface" aria-label="Student navigation">
        <a class="brand dashboard-brand" href="../index.php"><span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span><span>mindful</span></a>
        <nav class="side-nav">
            <a class="side-link active" href="dashboard.php"><span class="nav-icon">⌂</span>Dashboard</a>
            <a class="side-link" href="checkin.php"><span class="nav-icon">♡</span>Wellness Check-In</a>
            <a class="side-link" href="checkin-history.php"><span class="nav-icon">◷</span>History</a>
            <a class="side-link" href="resources.php"><span class="nav-icon">▤</span>Resources</a>
            <a class="side-link" href="appointments.php"><span class="nav-icon">□</span>Appointments</a>
            <a class="side-link" href="support-request.php"><span class="nav-icon">◎</span>Support Requests</a>
        </nav>
        <div class="sidebar-bottom">
            <a class="side-link" href="#"><span class="nav-icon">⚙</span>Settings</a>
            <div class="sidebar-care">
                <span class="care-spark">✦</span>
                <p>Need to talk?</p>
                <a href="support-request.php">Get support <span>→</span></a>
            </div>
        </div>
    </aside>

    <section class="dashboard-content">
        <header class="dashboard-topbar glass-surface">
            <button class="mobile-menu" type="button" aria-label="Open navigation">☰</button>
            <div class="topbar-breadcrumb"><span>Student space</span><strong>Dashboard</strong></div>
            <div class="topbar-actions">
                <button class="notification-button" type="button" aria-label="You have 2 notifications"><span>♢</span><i></i></button>
                <a class="profile-chip" href="#"><span class="avatar">J</span><span class="profile-name">Joshua <small>Student</small></span><span class="chevron">⌄</span></a>
            </div>
        </header>

        <section class="dashboard-hero" aria-labelledby="greeting-title">
            <div>
                <p class="hero-overline"><span></span> MONDAY, JULY 21</p>
                <h1 id="greeting-title">Good morning, Joshua <span>👋</span></h1>
                <p>How are you feeling today? A moment for yourself can change the pace of your day.</p>
            </div>
            <div class="hero-orb" aria-hidden="true"><div class="orb-core">✦</div><i></i><b></b></div>
        </section>

        <section class="mood-section glass-surface" aria-labelledby="mood-title">
            <div class="section-heading inline-heading"><div><p class="section-kicker">DAILY CHECK-IN</p><h2 id="mood-title">Choose your mood</h2></div><a href="checkin.php">Full check-in <span>→</span></a></div>
            <div class="mood-options" role="group" aria-label="How are you feeling today?">
                <button class="mood-option" type="button" data-mood="Excellent"><span>😊</span><strong>Excellent</strong><small>Feeling great</small></button>
                <button class="mood-option" type="button" data-mood="Good"><span>🙂</span><strong>Good</strong><small>Feeling steady</small></button>
                <button class="mood-option selected" type="button" data-mood="Neutral" aria-pressed="true"><span>😐</span><strong>Neutral</strong><small>Taking it easy</small></button>
                <button class="mood-option" type="button" data-mood="Stressed"><span>😔</span><strong>Stressed</strong><small>Feeling tense</small></button>
                <button class="mood-option" type="button" data-mood="Overwhelmed"><span>😢</span><strong>Overwhelmed</strong><small>Need a pause</small></button>
            </div>
            <p class="mood-feedback" role="status" aria-live="polite">Feeling neutral is completely okay. You are doing well by checking in.</p>
        </section>

        <section class="summary-grid" aria-label="Today's wellness summary">
            <article class="summary-card glass-surface"><div class="summary-label"><span class="summary-icon blue">⌁</span><span>Current stress</span><button aria-label="Stress information">i</button></div><div class="summary-stat"><strong>Moderate</strong><span>3 <small>/ 5</small></span></div><div class="level-bars"><i></i><i></i><i class="filled"></i><i class="filled"></i><i class="filled"></i></div><p>Take one thing at a time today.</p></article>
            <article class="summary-card glass-surface"><div class="summary-label"><span class="summary-icon green">✦</span><span>Wellness score</span><button aria-label="Wellness score information">i</button></div><div class="summary-stat"><strong>74 <small>/ 100</small></strong><div class="ring ring-74"><span>Good</span></div></div><p><b>↑ 8%</b> from last week</p></article>
            <article class="summary-card glass-surface"><div class="summary-label"><span class="summary-icon orange">◉</span><span>Check-in streak</span><button aria-label="Check-in streak information">i</button></div><div class="summary-stat"><strong>5 days</strong><span class="streak-fire">🔥</span></div><div class="streak-days"><b>M</b><b>T</b><b>W</b><b>T</b><b>F</b><i>S</i><i>S</i></div><p>Keep your mindful streak going.</p></article>
        </section>

        <section class="dashboard-grid">
            <article class="resources-panel glass-surface">
                <div class="section-heading"><div><p class="section-kicker">JUST FOR YOU</p><h2>Recommended resources</h2></div><a href="resources.php">See all <span>→</span></a></div>
                <div class="resource-carousel">
                    <a class="resource-card blue-resource" href="resources.php"><span class="resource-icon">⌇</span><div><h3>Managing academic stress</h3><p>Small, practical ways to ease study pressure.</p></div><span class="resource-link">Explore →</span></a>
                    <a class="resource-card green-resource" href="resources.php"><span class="resource-icon">☼</span><div><h3>Mindfulness exercises</h3><p>Take a quiet five minutes for yourself.</p></div><span class="resource-link">Explore →</span></a>
                    <a class="resource-card lilac-resource" href="appointments.php"><span class="resource-icon">◌</span><div><h3>Campus counselling</h3><p>Friendly, confidential support is here.</p></div><span class="resource-link">Explore →</span></a>
                </div>
            </article>

            <article class="appointment-card glass-surface">
                <div class="section-heading"><div><p class="section-kicker">UPCOMING</p><h2>Appointment</h2></div><span class="status-badge">Confirmed</span></div>
                <div class="appointment-date"><span class="date-box"><b>24</b><small>THU</small></span><div><h3>2:00 PM – 2:45 PM</h3><p>with Dr. Emily Tan</p></div></div>
                <div class="appointment-meta"><span>◉</span> Student Wellbeing Centre · Room B-12</div>
                <a class="soft-button" href="appointments.php">View details <span>→</span></a>
            </article>

            <article class="history-card glass-surface">
                <div class="section-heading"><div><p class="section-kicker">PAST 7 DAYS</p><h2>Wellness history</h2></div><button class="chart-select" type="button">Mood trend <span>⌄</span></button></div>
                <div class="chart-legend"><span><i></i> Your mood</span><em>Getting steadier <b>↗</b></em></div>
                <div class="line-chart" aria-label="Mood trend chart for the past 7 days"><svg viewBox="0 0 590 166" role="img" aria-label="Mood trend is rising over the last seven days"><defs><linearGradient id="area" x1="0" x2="0" y1="0" y2="1"><stop stop-color="#5ac8fa" stop-opacity=".24"/><stop offset="1" stop-color="#5ac8fa" stop-opacity="0"/></linearGradient></defs><path class="chart-area" d="M8 122 C56 113,69 128,106 108 S169 76,204 94 S252 118,294 89 S354 55,390 72 S446 95,485 53 S540 25,582 32 V160 H8Z"/><path class="chart-line" d="M8 122 C56 113,69 128,106 108 S169 76,204 94 S252 118,294 89 S354 55,390 72 S446 95,485 53 S540 25,582 32"/><circle cx="582" cy="32" r="5"/></svg><div class="chart-labels"><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span></div></div>
            </article>

            <article class="support-card glass-surface">
                <div class="section-heading"><div><p class="section-kicker">YOUR REQUEST</p><h2>Support status</h2></div><a href="support-request.php">View <span>→</span></a></div>
                <div class="support-timeline"><div class="timeline-step done"><i>✓</i><div><strong>Submitted</strong><small>18 July · 10:42 AM</small></div></div><div class="timeline-step done"><i>✓</i><div><strong>Under review</strong><small>Your request is being looked at</small></div></div><div class="timeline-step current"><i></i><div><strong>Assigned</strong><small>We will update you soon</small></div></div><div class="timeline-step"><i></i><div><strong>Completed</strong></div></div></div>
            </article>
        </section>

        <section class="quick-actions" aria-labelledby="quick-title"><div class="section-heading"><div><p class="section-kicker">TAKE THE NEXT STEP</p><h2 id="quick-title">Quick actions</h2></div></div><div class="action-grid"><a href="checkin.php" class="quick-action primary-action"><span>＋</span><b>New check-in</b><small>Take a moment</small></a><a href="appointments.php" class="quick-action"><span>□</span><b>Book appointment</b><small>Talk with someone</small></a><a href="support-request.php" class="quick-action"><span>◎</span><b>Request support</b><small>We are here for you</small></a><a href="resources.php" class="quick-action"><span>▤</span><b>Browse resources</b><small>Find what helps</small></a></div></section>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
