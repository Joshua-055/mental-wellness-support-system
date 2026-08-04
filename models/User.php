<?php
declare(strict_types=1);

final class User
{
    public function findById(int $userId): ?array
    {
        $statement = Database::connection()->prepare(
            'SELECT id, full_name, email, password_hash, role, is_active FROM users WHERE id = ? LIMIT 1'
        );
        $statement->bind_param('i', $userId);
        $statement->execute();

        $user = $statement->get_result()->fetch_assoc();
        $statement->close();

        return $user ?: null;
    }

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

    public function emailBelongsToAnotherUser(string $email, int $userId): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1'
        );
        $statement->bind_param('si', $email, $userId);
        $statement->execute();
        $exists = $statement->get_result()->fetch_assoc() !== null;
        $statement->close();

        return $exists;
    }

    public function updateProfile(int $userId, string $fullName, string $email): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE users SET full_name = ?, email = ? WHERE id = ?'
        );
        $statement->bind_param('ssi', $fullName, $email, $userId);
        $statement->execute();
        $statement->close();
    }

    public function updatePassword(int $userId, string $passwordHash): void
    {
        $statement = Database::connection()->prepare(
            'UPDATE users SET password_hash = ? WHERE id = ?'
        );
        $statement->bind_param('si', $passwordHash, $userId);
        $statement->execute();
        $statement->close();
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
