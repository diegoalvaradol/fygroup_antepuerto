<?php
require_once __DIR__ . '/../config/includes.php';

$famesa     = new famesa();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$search     = "%{$searchForm}%";

$sql  = "SELECT * FROM app_famesa WHERE car_plate_ramp LIKE :search GROUP BY car_plate_ramp LIMIT 10";
$list = $famesa->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    "id"   => "-",
    "text" => "Seleccione un patente..."
  ]
];

foreach ($list->getCollection() as $info) {
  $data[] = [
    "id"   => $info['car_plate_ramp'],
    "text" => $info['car_plate_ramp']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
