<?php
declare(strict_types=1);

final class Database
{
    public static function connection(): mysqli
    {
        require APP_ROOT . '/config/database.php';

        if (!isset($conn) || !$conn instanceof mysqli) {
            throw new RuntimeException('Database connection could not be created.');
        }

        return $conn;
    }
}
