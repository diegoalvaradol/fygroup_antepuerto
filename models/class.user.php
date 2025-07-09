<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

class user extends iQuery
{
  private $conexion;
  protected $table      = "app_users";
  protected $primaryKey = 'user_id';

  public $id              = "user_id";
  public $run             = "run";
  public $name            = "name";
  public $lastname        = "last_name";
  public $email           = "email";
  public $password        = "password";
  public $division        = "division"; /* Indica si el usuario pertenece a SSL o Portal clientes */
  public $lastsession     = "last_session"; /* Indica la hora del inicion de sesion (SOLO APLICA PARA USUARIOS PORTAL DE CLIENTE) */
  public $token           = "reset_token"; /* Token temporal al reestablecer contraseña */
  public $tokenexpiration = "token_expiration"; /* Duración del token proporcionado (duración: 1 hora) */
  public $created         = "created";
  public $lastupdate      = "last_update";

  public function __construct($db)
  {
    $this->conexion = $db;
  }

  public function save()
  {
    $query = "INSERT INTO $this->table (run, name, last_name, email, password, division, created, last_update) VALUES (:run, :name, :lastname, :email, :password, :division, :created, :lastupdate)";
    $stmt  = $this->conexion->prepare($query);

    $this->run        = htmlspecialchars(strip_tags($this->run));
    $this->name       = htmlspecialchars(strip_tags($this->name));
    $this->lastname   = htmlspecialchars(strip_tags($this->lastname));
    $this->email      = htmlspecialchars(strip_tags($this->email));
    $this->password   = password_hash($this->password, PASSWORD_DEFAULT);
    $this->division   = htmlspecialchars(strip_tags($this->division));
    $this->created    = $this->created;
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":run", $this->run, PDO::PARAM_STR);
    $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
    $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
    $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
    $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
    $stmt->bindParam(":division", $this->division, PDO::PARAM_STR);
    $stmt->bindParam(":created", $this->created, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE $this->table SET name = :name, last_name = :lastname, email = :email, password = :password, division = :division, last_update = :lastupdate WHERE run = :run";
    $stmt  = $this->conexion->prepare($query);

    $this->run        = htmlspecialchars(strip_tags($this->run));
    $this->name       = htmlspecialchars(strip_tags($this->name));
    $this->lastname   = htmlspecialchars(strip_tags($this->lastname));
    $this->email      = htmlspecialchars(strip_tags($this->email));
    $this->password   = $this->password;
    $this->division   = htmlspecialchars(strip_tags($this->division));
    $this->lastupdate = $this->lastupdate;

    $stmt->bindParam(":run", $this->run, PDO::PARAM_STR);
    $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
    $stmt->bindParam(":lastname", $this->lastname, PDO::PARAM_STR);
    $stmt->bindParam(":email", $this->email, PDO::PARAM_STR);
    $stmt->bindParam(":password", $this->password, PDO::PARAM_STR);
    $stmt->bindParam(":division", $this->division, PDO::PARAM_STR);
    $stmt->bindParam(":lastupdate", $this->lastupdate, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function login()
  {
    $query = "SELECT * FROM $this->table WHERE run = :run AND division = :division LIMIT 1";
    $stmt  = $this->conexion->prepare($query);
    $stmt->bindParam(":run", $this->run, PDO::PARAM_STR);
    $stmt->bindParam(":division", $this->division);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($this->password, $user['password'])) {
      $_SESSION["user"]         = $user;
      $_SESSION["last_session"] = time();

      $updateQuery = "UPDATE app_users SET last_session = NOW() WHERE run = :run";
      $updateStmt  = $this->conexion->prepare($updateQuery);
      $updateStmt->bindParam(":run", $this->run, PDO::PARAM_STR);
      $updateStmt->execute();

      return $user;
    }

    return false;
  }

  public function setResetToken($email, $token, $expiration)
  {
    $query = "UPDATE $this->table SET reset_token = :token, token_expiration = :expiration WHERE email = :email";
    $stmt  = $this->conexion->prepare($query);

    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    $stmt->bindParam(":expiration", $expiration, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public function resetPassword($token, $newPassword)
  {
    $query = "SELECT * FROM $this->table WHERE reset_token = :token AND token_expiration > NOW() LIMIT 1";
    $stmt  = $this->conexion->prepare($query);
    $stmt->bindParam(":token", $token, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      $update = "UPDATE $this->table SET password = :password, reset_token = :token, token_expiration = :expiration WHERE user_id = :id";
      $stmt2  = $this->conexion->prepare($update);

      $this->password = password_hash($newPassword, PASSWORD_DEFAULT);
      $token          = '';
      $expiration     = '0000-00-00 00:00:00'; // Limpiar el token y la expiración después de restablecer la contraseña

      $stmt2->bindParam(":id", $user['user_id'], PDO::PARAM_STR); // o $user[$this->id] si está bien definido
      $stmt2->bindParam(":password", $this->password, PDO::PARAM_STR);
      $stmt2->bindParam(":token", $token, PDO::PARAM_STR);
      $stmt2->bindParam(":expiration", $expiration, PDO::PARAM_STR);

      return $stmt2->execute();
    }

    return false;
  }

  public function isAdmin($run)
  {
    $query = "SELECT * FROM $this->table WHERE run = :run AND division = 'SSL' LIMIT 1";
    $stmt  = $this->conexion->prepare($query);
    $stmt->bindParam(":run", $run, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && in_array($result['run'], ['18.923.079-6', '15.798.016-5'])) {
      return true;
    } else {
      return false;
    }
  }

  public function isAdminEdit($run)
  {
    $query = "SELECT * FROM $this->table WHERE run = :run AND division = 'SSL' LIMIT 1";
    $stmt  = $this->conexion->prepare($query);
    $stmt->bindParam(":run", $run, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && isset($result['run']) && in_array($result['run'], ['18.923.079-6', '15.798.016-5', '21.394.463-0'])) {
      return true;
    } else {
      return false;
    }
  }

}
