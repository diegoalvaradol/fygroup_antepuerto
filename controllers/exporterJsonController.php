<?php
require_once __DIR__ . '/../config/includes.php';

$outer      = new outerPort();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$search     = "%{$searchForm}%";

$query = "SELECT * FROM app_outer_port WHERE exporter LIKE :search GROUP BY exporter LIMIT 10";
$stmt  = $outer->getDb()->prepare($query);
$stmt->bindParam(":search", $search, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [
  [
    "id"   => "-",
    "text" => "Seleccione un exportador..."
  ]
];

foreach ($result as $info) {
  $data[] = [
    "id"   => $info['exporter'],
    "text" => $info['exporter']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
