<?php
declare(strict_types=1);

final class Database
{
    private static ?mysqli $connection = null;

    public static function connection(): mysqli
    {
        if (self::$connection instanceof mysqli) {
            return self::$connection;
        }

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        require APP_ROOT . '/config/database.php';

        if (!isset($conn) || !$conn instanceof mysqli) {
            throw new RuntimeException('Database connection could not be created.');
        }

        self::$connection = $conn;

        return self::$connection;
    }
}
