<?php
require_once __DIR__ . '/../models/class.shipLine.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  /* Verifica si la linea naviera se encuentra asociado a una motonave registrada */
  $query = "SELECT * FROM app_ships WHERE ship_line = " . $_POST["id"] . "";
  $stmt  = $db->prepare($query);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK2";
  } else {
    $line     = new shipLine($db);
    $line->id = $_POST["id"];

    if ($line->delete()) {
      echo "OK";
    } else {
      echo "NOOK";
    }
  }
}
