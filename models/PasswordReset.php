<?php
declare(strict_types=1);

final class PasswordReset
{
    public function createForUser(int $userId, string $tokenHash): void
    {
        $connection = Database::connection();
        $connection->begin_transaction();

        try {
            $invalidate = $connection->prepare(
                'UPDATE password_reset_tokens SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL'
            );
            $invalidate->bind_param('i', $userId);
            $invalidate->execute();
            $invalidate->close();

            $ttlMinutes = PASSWORD_RESET_TTL_MINUTES;
            $insert = $connection->prepare(
                'INSERT INTO password_reset_tokens (user_id, token_hash, expires_at) '
                . 'VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? MINUTE))'
            );
            $insert->bind_param('isi', $userId, $tokenHash, $ttlMinutes);
            $insert->execute();
            $insert->close();

            $connection->commit();
        } catch (Throwable $exception) {
            $connection->rollback();
            throw $exception;
        }
    }

    public function isValid(string $tokenHash): bool
    {
        $statement = Database::connection()->prepare(
            'SELECT prt.id FROM password_reset_tokens prt '
            . 'INNER JOIN users u ON u.id = prt.user_id '
            . 'WHERE prt.token_hash = ? AND prt.used_at IS NULL '
            . 'AND prt.expires_at > NOW() AND u.is_active = 1 LIMIT 1'
        );
        $statement->bind_param('s', $tokenHash);
        $statement->execute();
        $isValid = $statement->get_result()->fetch_assoc() !== null;
        $statement->close();

        return $isValid;
    }

    public function resetPassword(string $tokenHash, string $passwordHash): bool
    {
        $connection = Database::connection();
        $connection->begin_transaction();

        try {
            $select = $connection->prepare(
                'SELECT prt.id, prt.user_id FROM password_reset_tokens prt '
                . 'INNER JOIN users u ON u.id = prt.user_id '
                . 'WHERE prt.token_hash = ? AND prt.used_at IS NULL '
                . 'AND prt.expires_at > NOW() AND u.is_active = 1 LIMIT 1 FOR UPDATE'
            );
            $select->bind_param('s', $tokenHash);
            $select->execute();
            $reset = $select->get_result()->fetch_assoc();
            $select->close();

            if ($reset === null) {
                $connection->rollback();
                return false;
            }

            $userId = (int) $reset['user_id'];
            $resetId = (int) $reset['id'];

            $updateUser = $connection->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $updateUser->bind_param('si', $passwordHash, $userId);
            $updateUser->execute();
            $updateUser->close();

            $consume = $connection->prepare(
                'UPDATE password_reset_tokens SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL'
            );
            $consume->bind_param('i', $userId);
            $consume->execute();
            $consume->close();

            $connection->commit();
            return $resetId > 0;
        } catch (Throwable $exception) {
            $connection->rollback();
            throw $exception;
        }
    }
}
