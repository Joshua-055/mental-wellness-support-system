<?php
declare(strict_types=1);

final class User
{
    public function findByEmail(string $email): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT id, full_name, email, password_hash, role, is_active FROM users WHERE email = ? LIMIT 1'
        );
        $statement->bind_param('s', $email);
        $statement->execute();

        $user = $statement->get_result()->fetch_assoc();
        $statement->close();

        return $user ?: null;
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    public function createStudent(string $fullName, string $email, string $passwordHash): int
    {
        $role = 'student';
        $statement = Database::connection()->prepare(
            'INSERT INTO users (full_name, email, password_hash, role, is_active) VALUES (?, ?, ?, ?, 1)'
        );
        $statement->bind_param('ssss', $fullName, $email, $passwordHash, $role);
        $statement->execute();

        $userId = (int) Database::connection()->insert_id;
        $statement->close();

        return $userId;
    }
}
