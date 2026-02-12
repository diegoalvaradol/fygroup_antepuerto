<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ships = new ship();
  $sid   = $_POST["id"];

  /* Verifica si el puerto se encuentra asociado a una motonave registrada */
  $query = "SELECT * FROM app_ships WHERE pol = :pol OR pod = :pod";
  $stmt  = $ship->getDb()->prepare($query);
  $stmt->bindParam(':pol', $id, PDO::PARAM_INT);
  $stmt->bindParam(':pod', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK2";
  } else {
    $port     = new port();
    $port->id = $id;

    if ($port->delete()) {
      echo "OK";
    } else {
      echo "NOOK";
    }
  }

}
