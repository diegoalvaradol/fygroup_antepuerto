<?php
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$exporter  = $_POST['exporter'];

$query = "SELECT * FROM app_outer_port WHERE exporter = :exporter AND origin = 1 LIMIT 1";
$stmt  = $outerPort->getDb()->prepare($query);
$stmt->bindParam(":exporter", $exporter, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result !== []) {
  echo $result['agency'];
}
