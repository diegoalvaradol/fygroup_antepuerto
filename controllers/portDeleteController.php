<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ship = new ship();
  $id   = $_POST["id"];

  /* Verifica si el puerto se encuentra asociado a una motonave registrada */
  $sql  = "SELECT * FROM app_ships WHERE pol = :pol OR pod = :pod";
  $list = $ship->getFirstMember($sql, ['pol' => $id, 'pod' => $id]);

  if ($list > 0) {
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
