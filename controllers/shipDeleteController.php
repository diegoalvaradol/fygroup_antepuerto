<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db = (new Database())->getConnection();

  /* Verifica si la motonave se encuentra asociado a un ingreso de contenedor o termo */
  $query = "SELECT * FROM app_outer_port WHERE vessel_id = " . $_POST['id'] . "";
  $stmt  = $db->prepare($query);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK2";
  } else {
    $ship     = new ship($db);
    $ship->id = $_POST["id"];

    if ($ship->delete()) {
      echo "OK";
    } else {
      echo "NOOK";
    }
  }
}
