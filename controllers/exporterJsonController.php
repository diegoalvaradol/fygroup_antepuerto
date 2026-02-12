<?php
require_once __DIR__ . '/../config/includes.php';

$outer      = new outerPort();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$search     = "%{$searchForm}%";

$sql  = "SELECT * FROM app_outer_port WHERE exporter LIKE :search GROUP BY exporter LIMIT 10";
$list = $outer->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    "id"   => "-",
    "text" => "Seleccione un exportador..."
  ]
];

foreach ($list->getCollection() as $info) {
  $data[] = [
    "id"   => $info['exporter'],
    "text" => $info['exporter']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
