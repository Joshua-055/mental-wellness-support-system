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
}
