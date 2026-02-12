<?php
require_once __DIR__ . '/../config/includes.php';

$outerPort  = new outerPort();
$searchForm = $_POST['search'] ?? '';
$search     = "%{$searchForm}%";

$sql  = "SELECT * FROM app_outer_port WHERE agency LIKE :search AND agency != 'N/A' GROUP BY agency LIMIT 10";
$list = $outerPort->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    "id"   => "-",
    "text" => "Seleccione una agencia..."
  ]
];

foreach ($list->getCollection() as $info) {
  $data[] = [
    "id"   => $info['agency'],
    "text" => $info['agency']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
