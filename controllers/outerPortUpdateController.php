<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db = (new Database())->getConnection();

  $rawDateOut = str_replace('T', ' ', $_POST['dateout']);
  $dateOut    = DateTime::createFromFormat('Y-m-d H:i', $rawDateOut);

  $outerPort                = new outerPort($db);
  $outerPort->id            = $_POST["rowId"];
  $outerPort->origin        = $_POST["originId"];
  $outerPort->departuredate = $dateOut ? $dateOut->format('Y-m-d H:i:s') : null;

  if ($outerPort->update()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
