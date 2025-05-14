<?php
require_once __DIR__ . '/../models/class.shipLine.php';
require_once __DIR__ . '/../config/database.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $line             = new shipLine($db);
  $line->name       = strtoupper($_POST["shipline"]);
  $line->created    = date('Y-m-d H:i:s');
  $line->lastupdate = date('Y-m-d H:i:s');

  if ($line->save()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
