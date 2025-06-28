<?php
require_once __DIR__ . '/../config/database.php';

$db         = (new Database())->getConnection();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';
$search     = "%{$searchForm}%";

$query = "SELECT * FROM app_international_chargue AS ic JOIN app_ships AS sh ON ic.vessel_id = sh.ship_id WHERE container LIKE :search LIMIT 10";
$stmt  = $db->prepare($query);
$stmt->bindParam(":search", $search, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [
  [
    "id"   => "-",
    "text" => "Seleccione un contenedor..."
  ]
];

foreach ($result as $info) {
  $data[] = [
    "id"   => $info['row_id'],
    "text" => $info['container'] . ' (' . $info['vessel_name'] . ' - ' . $info['voyage'] . ')'
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
