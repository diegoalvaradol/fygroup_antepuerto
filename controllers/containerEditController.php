<?php
require_once __DIR__ . '/../config/includes.php';

$outer = new outerPort();
$id    = $_POST['id'];

$query = "SELECT *, sh.vessel_name AS vesselname, sh.voyage AS voyage FROM app_outer_port AS cnt JOIN app_ships AS sh ON cnt.vessel_id = sh.ship_id WHERE cnt.row_id = :id AND cnt.origin = 1 LIMIT 1";
$stmt  = $outer->getDb()->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);
