<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$famesa = new famesa();
$id = $_POST['id'];

$sql = 'SELECT *, sh.vessel_name AS vesselname, sh.voyage AS voyage
	FROM app_famesa AS f
	JOIN app_ships AS sh ON f.vessel_id = sh.ship_id
	WHERE f.row_id = :id
	LIMIT 1
';

$list = $famesa->getFirstMember($sql, ['id' => $id]);

echo json_encode($list);
