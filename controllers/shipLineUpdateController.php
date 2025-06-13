<?php
require_once __DIR__ . '/../config/includes.php'; 
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db = (new Database())->getConnection();

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
