<?php
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$id        = $_POST['id'];

$sql = "SELECT *, sh.vessel_name AS vesselname, sh.voyage AS voyage
	FROM app_outer_port AS cnt
	JOIN app_ships AS sh ON cnt.vessel_id = sh.ship_id
	WHERE cnt.row_id = :id AND cnt.origin = 2
	LIMIT 1
";

$list = $outerPort->getFirstMember($sql, ['id' => $id]);

echo json_encode($list);
