<?php
$role = (string) ($currentUser['role'] ?? 'student');
$isStudent = $role === 'student';
$displayName = escape((string) ($currentUser['full_name'] ?? 'User'));
$initial = escape(strtoupper(substr(trim((string) ($currentUser['full_name'] ?? 'U')), 0, 1)));
$spaceLabel = $isStudent ? 'Student' : 'Staff';
$settingsUrl = BASE_URL . ($isStudent ? '/student/index.php?page=settings' : '/staff/index.php?page=settings');
?>
<main class="student-app settings-app">
    <div class="dashboard-glow glow-one" aria-hidden="true"></div>
    <div class="dashboard-glow glow-two" aria-hidden="true"></div>

    <?php
    $navigationRole = $role;
    $activePage = 'settings';
    $sidebarClass = 'student-sidebar';
    require APP_ROOT . '/views/layouts/app-sidebar.php';
    ?>

    <section class="dashboard-content settings-content">
        <?php $topbarTitle = 'Settings'; require APP_ROOT . '/views/layouts/dashboard-topbar.php'; ?>

        <header class="settings-heading">
            <p class="section-kicker">PERSONAL PREFERENCES</p>
            <h1>Settings</h1>
            <p>Manage your profile, password and how Mindful looks on this device.</p>
        </header>

        <?php if (!empty($success)): ?>
            <div class="settings-alert success" role="status"><?= escape((string) $success) ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="settings-alert error" role="alert">
                <?php foreach ($errors as $error): ?><p><?= escape((string) $error) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="settings-grid">
            <section class="settings-card glass-surface" aria-labelledby="profile-heading">
                <div class="settings-card-heading"><span class="settings-icon blue">◉</span><div><p>PROFILE</p><h2 id="profile-heading">Personal information</h2></div></div>
                <form method="post" action="<?= $settingsUrl ?>" class="settings-form">
                    <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">
                    <input type="hidden" name="form_action" value="profile">
                    <label for="full_name">Full Name</label>
                    <input id="full_name" name="full_name" type="text" maxlength="100" autocomplete="name" value="<?= escape((string) $currentUser['full_name']) ?>" required>
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" maxlength="150" autocomplete="email" value="<?= escape((string) $currentUser['email']) ?>" required>
                    <button class="settings-primary-button" type="submit">Save profile</button>
                </form>
            </section>

            <section class="settings-card glass-surface" aria-labelledby="security-heading">
                <div class="settings-card-heading"><span class="settings-icon green">◇</span><div><p>SECURITY</p><h2 id="security-heading">Change Password</h2></div></div>
                <form method="post" action="<?= $settingsUrl ?>" class="settings-form">
                    <input type="hidden" name="csrf_token" value="<?= escape($csrfToken) ?>">
                    <input type="hidden" name="form_action" value="password">
                    <label for="current_password">Current password</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                    <label for="new_password">New password</label>
                    <input id="new_password" name="new_password" type="password" minlength="8" autocomplete="new-password" required>
                    <label for="confirm_password">Confirm new password</label>
                    <input id="confirm_password" name="confirm_password" type="password" minlength="8" autocomplete="new-password" required>
                    <button class="settings-primary-button" type="submit">Change password</button>
                </form>
            </section>

            <section class="settings-card glass-surface appearance-card" aria-labelledby="appearance-heading">
                <div class="settings-card-heading"><span class="settings-icon purple">◐</span><div><p>APPEARANCE</p><h2 id="appearance-heading">Choose your theme</h2></div></div>
                <p class="settings-description">Your choice is saved on this device.</p>
                <div class="theme-options" role="group" aria-label="Appearance theme">
                    <button type="button" class="theme-option" data-theme-choice="light"><span class="theme-preview light-preview"><i></i><b></b></span><strong>Light Mode</strong><small>Bright and calming</small></button>
                    <button type="button" class="theme-option" data-theme-choice="dark"><span class="theme-preview dark-preview"><i></i><b></b></span><strong>Dark Mode</strong><small>Gentle in low light</small></button>
                </div>
            </section>

            <section class="settings-card glass-surface account-card" aria-labelledby="account-heading">
                <div class="settings-card-heading"><span class="settings-icon orange">↗</span><div><p>ACCOUNT</p><h2 id="account-heading">Session</h2></div></div>
                <p class="settings-description">Sign out safely when you have finished using Mindful.</p>
                <a class="logout-button" href="<?= BASE_URL ?>/auth/logout.php">Logout <span aria-hidden="true">→</span></a>
            </section>
        </div>
    </section>
</main>
