<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  exit;
}

$db   = (new Database())->getConnection();
$port = new outerPort($db);

$fromVessel = (int) ($_POST['fromvessel'] ?? 0);
$toVessel   = (int) ($_POST['tovessel'] ?? 0);
$rowId      = $_POST['rowId'] ?? [];

if (!$fromVessel || !$toVessel || empty($rowId)) {
  echo "ERROR";

  exit;
}

/* Origen nave */
$stmt = $db->prepare("SELECT origin FROM app_outer_port WHERE vessel_id = :vessel LIMIT 1");
$stmt->bindParam(":vessel", $fromVessel, PDO::PARAM_INT);
$stmt->execute();
$originFrom = $stmt->fetchColumn();

/* Origen nave destino */
$stmt->bindParam(":vessel", $toVessel, PDO::PARAM_INT);
$stmt->execute();
$originTo = $stmt->fetchColumn();

if (!$originFrom || !$originTo || $originFrom !== $originTo) {
  echo "ERROR";

  exit;
}

if ($port->vesselTransfer($fromVessel, $toVessel, $rowId)) {
  echo "OK";
} else {
  echo "NOOK";
}
