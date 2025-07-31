<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db   = (new Database())->getConnection();
  $port = new outerPort($db);

  $fromVessel = $_POST["fromvessel"];
  $toVessel   = $_POST["tovessel"];

  /* Nave de origen */
  $stmt = $db->prepare("SELECT origin FROM app_outer_port WHERE vessel_id = :fromVessel");
  $stmt->bindParam(":fromVessel", $fromVessel, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  $originFromVessel = $result['origin'];

  /* Nave de destino */
  $stmt1 = $db->prepare("SELECT origin FROM app_outer_port WHERE vessel_id = :toVessel");
  $stmt1->bindParam(":toVessel", $toVessel, PDO::PARAM_INT);
  $stmt1->execute();
  $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

  $origintoVessel = $result1['origin'];

  if ($originFromVessel !== $origintoVessel) {
    echo "ERROR";
    exit;
  }

  if ($port->vesselTransfer($fromVessel, $toVessel)) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
