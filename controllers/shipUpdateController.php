<?php
require_once __DIR__ . '/../models/class.ship.php';
require_once __DIR__ . '/../config/database.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $rawEta = str_replace('T', ' ', $_POST['shipEta']);
  $eta    = DateTime::createFromFormat('Y-m-d H:i', $rawEta);

  $rawEtd = str_replace('T', ' ', $_POST['shipEtd']);
  $etd    = DateTime::createFromFormat('Y-m-d H:i', $rawEtd);

  $ship             = new ship($db);
  $ship->id         = $_POST["shipId"];
  $ship->vessel     = strtoupper($_POST["shipName"]);
  $ship->voyage     = strtoupper($_POST["shipVoyage"]);
  $ship->line       = $_POST["shipLine"];
  $ship->port       = $_POST["shipPOD"];
  $ship->eta        = $eta ? $eta->format('Y-m-d H:i:s') : null;
  $ship->etd        = $etd ? $etd->format('Y-m-d H:i:s') : null;
  $ship->lastupdate = date('Y-m-d H:i:s');

  if ($ship->update()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
