<?php
require_once __DIR__ . '/../config/includes.php';

$port       = new port();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';

$search = "%{$searchForm}%";

$query = "SELECT * FROM app_ports WHERE city LIKE :search GROUP BY city LIMIT 20";
$stmt  = $port->getDb()->prepare($query);
$stmt->bindParam(":search", $search, PDO::PARAM_STR);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [
  [
    "id"   => "-",
    "text" => "Seleccione un puerto..."
  ]
];

foreach ($result as $info) {
  $data[] = [
    "id"   => $info['port_id'],
    "text" => $info['city'] . ' - ' . $info['country']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
