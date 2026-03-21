<?php
require_once __DIR__ . '/../config/includes.php';

$famesa = new famesa();

$sql  = "SELECT DISTINCT DATE(arrival_date_port) AS fecha FROM app_famesa GROUP BY DATE(arrival_date_port) ORDER BY fecha DESC";
$list = $famesa->findAllStatic($sql);

if ($list->length()) {
  $fechas = [];

  foreach ($list->getCollection() as $row) {
    $fechas[] = $row['fecha'];
  }
}

header('Content-Type: application/json');
echo json_encode($fechas);
