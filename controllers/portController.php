<?php
require_once __DIR__ . '/../models/class.port.php';
require_once __DIR__ . '/../config/database.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $port             = new port($db);
  $port->city       = ucwords(strtolower($_POST["city"]));
  $port->country    = ucwords(strtolower($_POST["country"]));
  $port->created    = date('Y-m-d H:i:s');
  $port->lastupdate = date('Y-m-d H:i:s');

  if ($port->save()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
