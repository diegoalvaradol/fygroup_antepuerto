<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user = new user();
  $run  = $_POST["run"];

  $query = "SELECT * FROM app_users WHERE run = :run LIMIT 1";
  $stmt  = $user->getDb()->prepare($query);
  $stmt->bindParam(":run", $run, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (password_verify($_POST["password"], $result['password'])) {
    $pass = $result['password'];
  } else {
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
  }

  $user->run        = $run;
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
