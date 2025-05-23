<?php
require_once __DIR__ . '/../models/class.ship.php';
require_once __DIR__ . '/../config/database.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $ship               = new ship($db);
  $ship->id           = $_POST["shipId"];
  $ship->finished     = 1;
  $ship->finisheddate = date('Y-m-d H:i:s');
  $ship->lastupdate   = date('Y-m-d H:i:s');

  if ($ship->endStacking()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
