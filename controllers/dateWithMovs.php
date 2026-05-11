<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$fechas = [];

$sql = 'SELECT DISTINCT DATE(arrival_date) AS fecha FROM app_outer_port GROUP BY DATE(arrival_date) ORDER BY fecha DESC';
$list = $outerPort->findAllStatic($sql);

if ($list->length()) {
    foreach ($list->getCollection() as $row) {
        $fechas[] = $row['fecha'];
    }
}

header('Content-Type: application/json');
echo json_encode($fechas);
