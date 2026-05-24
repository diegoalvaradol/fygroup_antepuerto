<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ship               = new ship();
  $ship->id           = $_POST["shipId"];
  $ship->finished     = $_POST["status"];
  $ship->finisheddate = $_POST["status"] == 1 ? date('Y-m-d H:i:s') : '0000-00-00 00:00:00';
  $ship->lastupdate   = date('Y-m-d H:i:s');

  if ($ship->endStacking()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
