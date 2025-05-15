<?php
class Database
{
  private $host;
  private $db_name;
  private $username;
  private $password;
  public $conexion;

  public function __construct()
  {
    if ($this->esLocalhost()) {
      /* Localhost */
      $this->host     = "localhost";
      $this->db_name  = "ssl_chile";
      $this->username = "root";
      $this->password = "seatrade1313";
    } else {
      /* Server Ferozo */
      $this->host     = "localhost";
      $this->db_name  = "l0011525_myssl";
      $this->username = "l0011525_myssl";
      $this->password = "nodisu47VA";
    }
  }

  private function esLocalhost()
  {
    $whitelist = ['127.0.0.1', '::1', 'localhost'];

    return in_array($_SERVER['REMOTE_ADDR'], $whitelist) || in_array($_SERVER['SERVER_NAME'], $whitelist);
  }

  public function getConnection()
  {
    $this->conexion = null;

    try {
      $this->conexion = new PDO(
        "mysql:host={$this->host};dbname={$this->db_name}",
        $this->username,
        $this->password
      );
      $this->conexion->exec("set names utf8");
    } catch (PDOException $exception) {
      echo "Error de conexión: " . $exception->getMessage();
    }

    return $this->conexion;
  }
}
