<?php
require_once __DIR__ . '/../config/database.php';

$internatonal = new internationalChargue();
$searchForm   = isset($_POST['search']) ? $_POST['search'] : '';
$search       = "%{$searchForm}%";

$sql = "SELECT *
  FROM app_international_chargue AS ic
  JOIN app_ships AS sh ON ic.vessel_id = sh.ship_id
  WHERE container LIKE :search
  LIMIT 10
";

$list = $outerPort->findAllStatic($sql, ['search' => $search]);

$data = [
  [
    "id"   => "-",
    "text" => "Seleccione un contenedor..."
  ]
];

foreach ($list->getCollection() as $info) {
  $data[] = [
    "id"   => $info['row_id'],
    "text" => $info['container'] . ' (' . $info['vessel_name'] . ' - ' . $info['voyage'] . ')'
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
