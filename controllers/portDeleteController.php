<?php
require_once __DIR__ . '/../models/class.port.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  /* Verifica si el puerto se encuentra asociado a una motonave registrada */
  $query = "SELECT * FROM app_ships WHERE port_discharge = " . $_POST["id"] . "";
  $stmt  = $db->prepare($query);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK2";
  } else {
    $port     = new port($db);
    $port->id = $_POST["id"];

    if ($port->delete()) {
      echo "OK";
    } else {
      echo "NOOK";
    }
  }

}
