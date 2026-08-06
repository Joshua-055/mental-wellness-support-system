<?php
declare(strict_types=1);

final class WellnessCheckin
{
    private const DAILY_LIMIT_MESSAGE = 'You have already completed today\'s wellness check-in. You can check in again tomorrow.';

    private const MOOD_SCORES = [
        'excellent' => 100,
        'good' => 82,
        'neutral' => 70,
        'stressed' => 58,
        'overwhelmed' => 42,
    ];

    private const STRESS_LEVELS = [
        'excellent' => 1,
        'good' => 2,
        'neutral' => 3,
        'stressed' => 4,
        'overwhelmed' => 5,
    ];

    public function create(int $userId, string $mood, string $comment): bool
    {
        if (!isset(self::MOOD_SCORES[$mood])) {
            throw new InvalidArgumentException('Please choose a valid mood.');
        }

        $database = Database::connection();
        $lockName = 'wellness-checkin:' . $userId . ':' . (new DateTimeImmutable('today'))->format('Y-m-d');
        if (!$this->acquireDailyLock($database, $lockName)) {
            throw new RuntimeException('Your check-in is still being processed. Please wait a moment and try again.');
        }

        try {
            if ($this->hasCheckinToday($userId)) {
                throw new DomainException(self::DAILY_LIMIT_MESSAGE);
            }

            $categoryId = $this->defaultCategoryId();
            $stressLevel = self::STRESS_LEVELS[$mood];
            $needsFollowUp = $mood === 'overwhelmed' ? 1 : 0;
            $statement = $database->prepare(
                'INSERT INTO wellness_checkins (user_id, category_id, mood, stress_level, COMMENT, needs_follow_up) VALUES (?, ?, ?, ?, ?, ?)'
            );
            $statement->bind_param('iisisi', $userId, $categoryId, $mood, $stressLevel, $comment, $needsFollowUp);
            $saved = $statement->execute();
            $statement->close();

            return $saved;
        } finally {
            $this->releaseDailyLock($database, $lockName);
        }
    }

    public function hasCheckinToday(int $userId): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT EXISTS(SELECT 1 FROM wellness_checkins WHERE user_id = ? AND created_at >= CURDATE() AND created_at < CURDATE() + INTERVAL 1 DAY) AS has_checkin'
        );
        $statement->bind_param('i', $userId);
        $statement->execute();
        $result = (bool) $statement->get_result()->fetch_assoc()['has_checkin'];
        $statement->close();

        return $result;
    }

    public function recentByUser(int $userId, int $limit = 10): array
    {
        $statement = Database::connection()->prepare(
            'SELECT id, mood, COMMENT AS comment, created_at FROM wellness_checkins WHERE user_id = ? ORDER BY created_at DESC LIMIT ?'
        );
        $statement->bind_param('ii', $userId, $limit);
        $statement->execute();
        $checkins = $statement->get_result()->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        return $checkins;
    }

    public function summaryByUser(int $userId): array
    {
        $checkins = $this->recentByUser($userId, 100);
        $now = new DateTimeImmutable('today');
        $weekStart = $now->modify('-6 days')->format('Y-m-d');
        $thisWeek = array_values(array_filter($checkins, static fn(array $checkin): bool => substr($checkin['created_at'], 0, 10) >= $weekStart));
        $scores = array_map(fn(array $checkin): int => self::scoreForMood($checkin['mood']), $thisWeek);

        return [
            'average' => $scores === [] ? null : (int) round(array_sum($scores) / count($scores)),
            'week_count' => count(array_unique(array_map(static fn(array $checkin): string => substr($checkin['created_at'], 0, 10), $thisWeek))),
            'streak' => $this->streak($checkins, $now),
            'trend' => $this->trend($checkins, $now),
        ];
    }

    public static function scoreForMood(string $mood): int
    {
        return self::MOOD_SCORES[$mood] ?? 0;
    }

    private function defaultCategoryId(): int
    {
        $database = Database::connection();
        $result = $database->query('SELECT id FROM wellness_categories WHERE is_active = 1 ORDER BY id ASC LIMIT 1');
        $category = $result->fetch_assoc();
        if ($category !== null) {
            return (int) $category['id'];
        }

        $name = 'General wellness';
        $statement = $database->prepare('INSERT INTO wellness_categories (NAME, is_active) VALUES (?, 1)');
        $statement->bind_param('s', $name);
        $statement->execute();
        $id = $database->insert_id;
        $statement->close();

        return $id;
    }

    private function acquireDailyLock(mysqli $database, string $lockName): bool
    {
        $statement = $database->prepare('SELECT GET_LOCK(?, 5) AS acquired');
        $statement->bind_param('s', $lockName);
        $statement->execute();
        $acquired = (int) $statement->get_result()->fetch_assoc()['acquired'] === 1;
        $statement->close();

        return $acquired;
    }

    private function releaseDailyLock(mysqli $database, string $lockName): void
    {
        $statement = $database->prepare('SELECT RELEASE_LOCK(?)');
        $statement->bind_param('s', $lockName);
        $statement->execute();
        $statement->close();
    }

    private function streak(array $checkins, DateTimeImmutable $today): int
    {
        $days = array_flip(array_unique(array_map(static fn(array $checkin): string => substr($checkin['created_at'], 0, 10), $checkins)));
        $streak = 0;
        $cursor = $today;
        while (isset($days[$cursor->format('Y-m-d')])) {
            $streak++;
            $cursor = $cursor->modify('-1 day');
        }

        return $streak;
    }

    private function trend(array $checkins, DateTimeImmutable $today): array
    {
        $scoresByDay = [];
        foreach ($checkins as $checkin) {
            $date = substr($checkin['created_at'], 0, 10);
            $scoresByDay[$date] ??= self::scoreForMood($checkin['mood']);
        }

        $trend = [];
        for ($day = 6; $day >= 0; $day--) {
            $date = $today->modify("-{$day} days");
            $trend[] = [
                'label' => $date->format('D'),
                'day' => $date->format('j'),
                'score' => $scoresByDay[$date->format('Y-m-d')] ?? null,
            ];
        }

        return $trend;
    }
}
