<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$search = "%{$searchForm}%";

$sql = 'SELECT car_plate FROM app_outer_port WHERE car_plate LIKE :search GROUP BY car_plate LIMIT 10';
$list = $outerPort->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    'id' => '-',
    'text' => 'Seleccione un patente...',
  ],
];

foreach ($list->getCollection() as $info) {
    $data[] = [
      'id' => $info['car_plate'],
      'text' => $info['car_plate'],
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
