<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db = (new Database())->getConnection();

  $rawEta = str_replace('T', ' ', $_POST['shipEta']);
  $eta    = DateTime::createFromFormat('Y-m-d H:i', $rawEta);

  $rawEtd = str_replace('T', ' ', $_POST['shipEtd']);
  $etd    = DateTime::createFromFormat('Y-m-d H:i', $rawEtd);

  $ship             = new ship($db);
  $ship->id         = $_POST["shipId"];
  $ship->vessel     = strtoupper($_POST["shipName"]);
  $ship->voyage     = strtoupper($_POST["shipVoyage"]);
  $ship->line       = $_POST["shipLine"];
  $ship->pol        = $_POST["shipPOL"];
  $ship->pod        = $_POST["shipPOD"];
  $ship->eta        = $eta ? $eta->format('Y-m-d H:i:s') : null;
  $ship->etd        = $etd ? $etd->format('Y-m-d H:i:s') : null;
  $ship->lastupdate = date('Y-m-d H:i:s');

  if ($ship->update()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
