<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/class.user.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $token   = $_POST["token"];
  $pass    = $_POST["password"];
  $confirm = $_POST["confirm_password"];

  if ($pass !== $confirm) {
    die("Las contraseñas no coinciden.");
  }

  $db   = (new Database())->getConnection();
  $user = new User($db);

  if ($user->resetPassword($token, $pass)) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
