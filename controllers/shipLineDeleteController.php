<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ship = new ship();
  $id   = $_POST["id"];

  /* Verifica si la linea naviera se encuentra asociado a una motonave registrada */
  $query = "SELECT * FROM app_ships WHERE ship_line = :id";
  $stmt  = $ship->getDb()->prepare($query);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK2";
  } else {
    $line     = new shipLine();
    $line->id = $id;

    if ($line->delete()) {
      echo "OK";
    } else {
      echo "NOOK";
    }
  }
}
