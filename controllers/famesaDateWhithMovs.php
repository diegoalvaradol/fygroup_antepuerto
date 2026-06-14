<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$famesa = new famesa();
$fechas = [];

$sql = 'SELECT DISTINCT DATE(arrival_date_port) AS fecha FROM app_famesa GROUP BY DATE(arrival_date_port) ORDER BY fecha DESC';
$list = $famesa->findAllStatic($sql);

if ($list->length()) {
    foreach ($list->getCollection() as $row) {
        $fechas[] = $row['fecha'];
    }
}

header('Content-Type: application/json');
echo json_encode($fechas);
