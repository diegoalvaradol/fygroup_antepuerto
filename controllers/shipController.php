<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db = (new Database())->getConnection();

  $rawEta = str_replace('T', ' ', $_POST['eta']);
  $eta    = DateTime::createFromFormat('Y-m-d H:i', $rawEta);

  $rawEtd = str_replace('T', ' ', $_POST['etd']);
  $etd    = DateTime::createFromFormat('Y-m-d H:i', $rawEtd);

  $ship             = new ship($db);
  $ship->vessel     = strtoupper($_POST["vessel"]);
  $ship->voyage     = strtoupper($_POST["voyage"]);
  $ship->line       = $_POST["line"];
  $ship->port       = $_POST["pod"];
  $ship->eta        = $eta ? $eta->format('Y-m-d H:i:s') : null;
  $ship->etd        = $etd ? $etd->format('Y-m-d H:i:s') : null;
  $ship->created    = date('Y-m-d H:i:s');
  $ship->lastupdate = date('Y-m-d H:i:s');

  if ($ship->save()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
