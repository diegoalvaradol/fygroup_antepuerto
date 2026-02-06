<?php
require_once __DIR__ . '/../functions/functions.php';

class Database
{
  private string $host;
  private string $db_name;
  private string $username;
  private string $password;

  public function __construct()
  {
    if (esLocalhost()) {
      $this->host     = 'localhost';
      $this->db_name  = 'ssl_chile';
      $this->username = 'ssl_chile';
      $this->password = 'seatrade1313';
    } else {
      $this->host     = 'localhost';
      $this->db_name  = 'l0011525_myssl';
      $this->username = 'l0011525_myssl';
      $this->password = 'nodisu47VA';
    }
  }

  public function getConnection(): \PDO
  {
    try {
      return new \PDO(
        "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
        $this->username,
        $this->password,
        [
          \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
          \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
          \PDO::ATTR_EMULATE_PREPARES   => false
        ]
      );
    } catch (\PDOException $e) {
      throw new \RuntimeException('Error de conexión BD: ' . $e->getMessage());
    }
  }
}
