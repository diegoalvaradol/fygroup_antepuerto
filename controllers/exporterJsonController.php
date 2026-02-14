<?php
require_once __DIR__ . '/../config/includes.php';

$company    = new company();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$search     = "%{$searchForm}%";

$sql  = "SELECT name FROM app_company WHERE name LIKE :search AND exporter = 1";
$list = $company->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    "id"   => "-",
    "text" => "Seleccione un exportador..."
  ]
];

foreach ($list->getCollection() as $info) {
  $data[] = [
    "id"   => $info['name'],
    "text" => $info['name']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
