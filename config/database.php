<?php
class Database
{

  /*
  private $host     = "localhost";
  private $db_name  = "ssl_chile";
  private $username = "root";
  private $password = "seatrade1313";
  public $conexion;
   */

  private $host     = "localhost";
  private $db_name  = "l0011525_myssl";
  private $username = "l0011525_myssl";
  private $password = "nodisu47VA";
  public $conexion;

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
