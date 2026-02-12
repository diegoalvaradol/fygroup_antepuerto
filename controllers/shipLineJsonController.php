<?php
require_once __DIR__ . '/../config/includes.php';

$shipLine   = new shipLine();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$search     = "%{$searchForm}%";

$sql  = "SELECT * FROM app_ship_lines WHERE name LIKE :search LIMIT 10";
$list = $shipLine->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    "id"   => "-",
    "text" => "Seleccione una línea..."
  ]
];

foreach ($list->getCollection() as $info) {
  $data[] = [
    "id"   => $info['line_id'],
    "text" => $info['name']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
