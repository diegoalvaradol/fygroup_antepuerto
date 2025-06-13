<?php
session_start();
require_once __DIR__ . '/../config/includes.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db       = (new Database())->getConnection();

  $user              = new User($db);
  $user->run         = $_POST["run"];
  $user->password    = $_POST["password"];
  $user->division    = $_POST["division"];
  $user->lastsession = date('Y-m-d H:i:s');

  /* Valida que el perfil del usuario coincida con el que envia el formulario */
  $query = "SELECT * FROM app_users WHERE run = :run LIMIT 1";
  $stmt  = $db->prepare($query);
  $stmt->bindParam(":run", $_POST["run"]);
  $stmt->execute();
  $data = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($data['division'] !== $_POST["division"]) {
    echo "NOOK2";
  } else {
    if ($userData = $user->login()) {
      $_SESSION["user"] = $userData;
      echo "OK";
    } else {
      echo "NOOK";
    }
  }
}
