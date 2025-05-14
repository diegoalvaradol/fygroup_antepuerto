<?php
require_once __DIR__ . '/../models/class.shipLine.php';
require_once __DIR__ . '/../config/database.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $line             = new shipLine($db);
  $line->id         = $_POST["lineId"];
  $line->name       = strtoupper($_POST["lineName"]);
  $line->lastupdate = date('Y-m-d H:i:s');

  if ($line->update()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
