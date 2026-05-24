<?php
require_once __DIR__ . '/../functions/functions.php';

class Database
{
  private static ?PDO $conn = null;

  private function __construct()
  {}

  public static function get(): PDO
  {
    if (self::$conn === null) {
      if (esLocalhost()) {
        $host = "localhost";
        $db   = "ssl_chile";
        $user = "ssl_chile";
        $pass = "seatrade1313";
      } else {
        $host = "localhost";
        $db   = "fygroupc_myfy";
        $user = "fygroupc_adminfy";
        $pass = "Seatrade1313_";
      }

      self::$conn = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      );
    }

    return self::$conn;
  }
}
