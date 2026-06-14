<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$fechas = [];

$sql = '
    SELECT fecha
    FROM (
        SELECT DATE(arrival_date) AS fecha
        FROM app_outer_port
        WHERE arrival_date IS NOT NULL

        UNION

        SELECT DATE(departure_date) AS fecha
        FROM app_outer_port
        WHERE departure_date IS NOT NULL
    ) t
    ORDER BY fecha DESC
';

$list = $outerPort->findAllStatic($sql);

if ($list->length() > 0) {
    foreach ($list->getCollection() as $row) {
        $fechas[] = $row['fecha'];
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($fechas, JSON_UNESCAPED_UNICODE);
