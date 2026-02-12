<?php
require_once __DIR__ . '/../config/includes.php';

$ship      = new ship();
$outerPort = new outerPort();

/* Helpers */
function post($key, $default = null)
{
  return $_POST[$key] ?? $default;
}

$searchForm       = trim(post('search', ''));
$vesselId         = (int) post('vessel', 0);
$fieldsId         = trim(post('field', ''));
$searchLikeVessel = "%{$searchForm}%";
$searchLikeField  = "%{$fieldsId}%";

/* CARGA DE NAVES */
if (!post('trucks')) {
  $conditions = [];
  $params     = [':search' => $searchLikeVessel];

  $conditions[] = "vessel_name LIKE :search";

  if (post('current') == 1) {
    $conditions[] = "finished = 0";
  }

  if (post('finished') == 1) {
    $conditions[] = "finished = 1";
  }

  if (post('all') == 1) {
    $conditions[] = "(finished = 0 OR finished = 1)";
  }

  $where = implode(' AND ', $conditions);

  $sql = "
    SELECT ship_id, vessel_name, voyage
    FROM app_ships
    WHERE $where
    ORDER BY vessel_name ASC
    LIMIT 10
  ";

  $stmt = $ship->getDb()->prepare($sql);
  $stmt->execute($params);

  $data = [[
    'id'   => '-',
    'text' => 'Seleccione una motonave...'
  ]];

  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $data[] = [
      'id'   => $row['ship_id'],
      'text' => "{$row['vessel_name']} (Viaje: {$row['voyage']})"
    ];
  }

/* CARGA DE CAMIONES */
} else {
  $sql = "
    SELECT row_id, car_plate, container, counter_vessel, origin
    FROM app_outer_port
    WHERE vessel_id = :vessel AND (row_id LIKE :field OR car_plate LIKE :field)
    ORDER BY row_id ASC
    LIMIT 10
  ";

  $stmt = $outerPort->getDb()->prepare($sql);
  $stmt->bindValue(':vessel', $vesselId, PDO::PARAM_INT);
  $stmt->bindValue(':field', $searchLikeField, PDO::PARAM_STR);
  $stmt->execute();

  $data = [[]];

  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $container = $row['container'] != 'N/A' ? $row['container'] : 'N/A';
    $origin    = $row['origin'] == 1 ? 'C' : 'T';

    $data[] = [
      'id'   => $row['row_id'],
      'text' => "Posición: {$row['counter_vessel']}-{$origin} | Patente: {$row['car_plate']} | Contenedor: {$container}"
    ];
  }
}

header('Content-Type: application/json');
echo json_encode($data);
