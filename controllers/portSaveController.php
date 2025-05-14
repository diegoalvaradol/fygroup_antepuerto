<?php
require_once __DIR__ . '/../models/class.port.php';
require_once __DIR__ . '/../config/database.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $port             = new port($db);
  $port->id         = $_POST["portId"];
  $port->city       = ucwords(strtolower($_POST["portCity"]));
  $port->country    = ucwords(strtolower($_POST["portCountry"]));
  $port->lastupdate = date('Y-m-d H:i:s');

  if ($port->update()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
