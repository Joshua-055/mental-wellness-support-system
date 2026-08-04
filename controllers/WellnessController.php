<?php
declare(strict_types=1);

final class WellnessController extends Controller
{
    public function checkin(): void
    {
        $currentUser = requireRole('student');
        $model = new WellnessCheckin();
        $hasCheckinToday = $model->hasCheckinToday((int) $currentUser['id']);
        $this->render('student/checkin', [
            'pageTitle' => 'Wellness Check-In | Mindful',
            'pageStyles' => ['student-dashboard', 'wellness'],
            'pageScripts' => ['wellness'],
            'currentUser' => $currentUser,
            'hasCheckinToday' => $hasCheckinToday,
            'errorMessage' => $_SESSION['flash_error'] ?? ($hasCheckinToday
                ? 'You have already completed today\'s wellness check-in. You can check in again tomorrow.'
                : null),
        ]);
        unset($_SESSION['flash_error']);
    }

    public function save(): void
    {
        $currentUser = requireRole('student');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['flash_error'] = 'Your check-in could not be verified. Please try again.';
            redirectTo('/student/index.php?page=checkin');
        }

        $mood = (string) ($_POST['mood'] ?? '');
        $comment = trim((string) ($_POST['comment'] ?? ''));
        if (mb_strlen($comment) > 280) {
            $_SESSION['flash_error'] = 'Please keep your reflection within 280 characters.';
            redirectTo('/student/index.php?page=checkin');
        }

        try {
            (new WellnessCheckin())->create((int) $currentUser['id'], $mood, $comment);
            $_SESSION['flash_success'] = 'Your wellness check-in has been saved.';
            redirectTo('/student/index.php?page=checkin_history');
        } catch (InvalidArgumentException | DomainException $exception) {
            $_SESSION['flash_error'] = $exception->getMessage();
            redirectTo('/student/index.php?page=checkin');
        } catch (mysqli_sql_exception $exception) {
            $_SESSION['flash_error'] = 'We could not save your check-in right now. Please try again.';
            redirectTo('/student/index.php?page=checkin');
        } catch (RuntimeException $exception) {
            $_SESSION['flash_error'] = $exception->getMessage();
            redirectTo('/student/index.php?page=checkin');
        }
    }

    public function history(): void
    {
        $currentUser = requireRole('student');
        $model = new WellnessCheckin();
        $this->render('student/checkin-history', [
            'pageTitle' => 'Wellness History | Mindful',
            'pageStyles' => ['student-dashboard', 'wellness'],
            'pageScripts' => ['wellness'],
            'currentUser' => $currentUser,
            'checkins' => $model->recentByUser((int) $currentUser['id']),
            'summary' => $model->summaryByUser((int) $currentUser['id']),
            'successMessage' => $_SESSION['flash_success'] ?? null,
        ]);
        unset($_SESSION['flash_success']);
    }
}
