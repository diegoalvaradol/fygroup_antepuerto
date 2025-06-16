<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db   = (new Database())->getConnection();
  $port = new outerPort($db);

	$fromVessel = $_POST["fromvessel"]; 
	$toVessel   = $_POST["tovessel"];

  if ($port->vesselTransfer($fromVessel, $toVessel)) {
		echo "OK";
	} else {
		echo "NOOK";
	}
}
