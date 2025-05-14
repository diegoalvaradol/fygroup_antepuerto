<?php
require_once __DIR__ . '/../models/class.user.php';
require_once __DIR__ . '/../config/database.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $user = new user($db);

  $query = "SELECT * FROM app_users WHERE run = :run LIMIT 1";
  $stmt  = $db->prepare($query);
  $stmt->bindParam(":run", $_POST["run"]);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (password_verify($_POST["password"], $result['password'])) {
    $pass = $result['password'];
  } else {
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
  }

  $user->run        = $_POST["run"];
  $user->name       = $_POST["name"];
  $user->lastname   = $_POST["lastname"];
  $user->email      = $_POST["email"];
  $user->password   = $pass;
  $user->division   = $_POST["division"];
  $user->lastupdate = date('Y-m-d H:i:s');

  if ($user->update()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
