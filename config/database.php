<?php
require_once __DIR__ . '/../functions/functions.php';
class Database
{
  private $host;
  private $db_name;
  private $username;
  private $password;
  public $conexion;

  public function __construct()
  {
    if (esLocalhost()) {
      /* Localhost */
      $this->host     = "localhost";
      $this->db_name  = "ssl_chile";
      $this->username = "ssl_chile";
      $this->password = "seatrade1313";
    } else {
      /* Server Ferozo */
      $this->host     = "localhost";
      $this->db_name  = "l0011525_myssl";
      $this->username = "l0011525_myssl";
      $this->password = "nodisu47VA";
    }
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
