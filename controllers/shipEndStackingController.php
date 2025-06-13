<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db       = (new Database())->getConnection();

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
