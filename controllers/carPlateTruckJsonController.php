<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$famesa = new famesa();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$search = "%{$searchForm}%";

$sql = 'SELECT car_plate_truck FROM app_famesa WHERE car_plate_truck LIKE :search GROUP BY car_plate_truck LIMIT 10';
$list = $famesa->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    'id' => '-',
    'text' => 'Seleccione un patente...',
  ],
];

foreach ($list->getCollection() as $info) {
    $data[] = [
      'id' => $info['car_plate_truck'],
      'text' => $info['car_plate_truck'],
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
