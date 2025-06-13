<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db = (new Database())->getConnection();

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
