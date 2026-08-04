<?php
declare(strict_types=1);

final class WellnessCheckin
{
    private const MOOD_SCORES = [
        'very_good' => 100,
        'good' => 82,
        'neutral' => 70,
        'low' => 58,
        'very_low' => 42,
    ];

    private const STRESS_LEVELS = [
        'very_good' => 1,
        'good' => 2,
        'neutral' => 3,
        'low' => 4,
        'very_low' => 5,
    ];

    public function create(int $userId, string $mood, string $comment): bool
    {
        if (!isset(self::MOOD_SCORES[$mood])) {
            throw new InvalidArgumentException('Please choose a valid mood.');
        }

        $categoryId = $this->defaultCategoryId();
        $stressLevel = self::STRESS_LEVELS[$mood];
        $needsFollowUp = $mood === 'very_low' ? 1 : 0;
        $statement = Database::connection()->prepare(
            'INSERT INTO wellness_checkins (user_id, category_id, mood, stress_level, COMMENT, needs_follow_up) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $statement->bind_param('iisisi', $userId, $categoryId, $mood, $stressLevel, $comment, $needsFollowUp);
        $saved = $statement->execute();
        $statement->close();

        return $saved;
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
