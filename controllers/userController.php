<?php
require_once __DIR__ . '/../models/class.user.php';
require_once __DIR__ . '/../config/database.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $user = new user($db);

  $user->run        = $_POST["run"];
  $user->name       = $_POST["name"];
  $user->lastname   = $_POST["lastname"];
  $user->email      = $_POST["email"];
  $user->password   = $_POST["password"];
  $user->division   = $_POST["division"];
  $user->created    = date('Y-m-d H:i:s');
  $user->lastupdate = date('Y-m-d H:i:s');

  if ($user->save()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
