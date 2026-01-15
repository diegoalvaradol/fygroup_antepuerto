<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db = (new Database())->getConnection();

  $line       = new shipLine($db);
  $line->name = strtoupper($_POST["shipline"]);
  //$line->rut        = $_POST["rutShipLine"];
  $line->created    = date('Y-m-d H:i:s');
  $line->lastupdate = date('Y-m-d H:i:s');

  if ($line->save()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
