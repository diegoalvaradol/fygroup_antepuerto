<?php

declare(strict_types=1);

require_once __DIR__ . '/../functions/functions.php';

final class Database
{
    private static ?PDO $conn = null;

    private function __construct()
    {
    }

    public static function get(): PDO
    {
        if (self::$conn instanceof PDO) {
            return self::$conn;
        }

        if (esLocalhost()) {
            $host = 'localhost';
            $db   = 'fygroup_antepuerto';
            $user = 'adminfy';
            $pass = 'seatrade1313';
        } else {
            $host = 'localhost';
            $db   = 'fygroup1_antepuerto';
            $user = 'fygroup1_adminfy';
            $pass = 'Seatrade1313_';
        }

        try {
            self::$conn = new PDO(
                "mysql:host={$host};dbname={$db};charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            throw new RuntimeException(
                'No fue posible conectar con la base de datos.',
                0,
                $e
            );
        }

        return self::$conn;
    }
}
