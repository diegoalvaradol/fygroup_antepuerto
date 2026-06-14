<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$outer = new outerPort();
$id = $_POST['id'];

$sql = 'SELECT *, sh.vessel_name AS vesselname, sh.voyage AS voyage
	FROM app_outer_port AS cnt
	JOIN app_ships AS sh ON cnt.vessel_id = sh.ship_id
	WHERE cnt.row_id = :id AND cnt.origin = 1
	LIMIT 1
';

$list = $outer->getFirstMember($sql, ['id' => $id]);

echo json_encode($list);
