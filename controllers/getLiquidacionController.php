<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    exit('ID de motonave no válido.');
}

$exporter = trim($_POST['exporter'] ?? '');
$exporter = ($exporter === '' || $exporter === '-') ? null : $exporter;

$outer = new outerPort();
$alerts = new UIComponents();

$sql = 'SELECT
    v.vessel_name   AS nave,
    v.eta,
    v.etd,
    v.voyage        AS viaje,
    pol.city        AS ciudadPOL,
    pol.country     AS paisPOL,
    pod.city        AS ciudadPOD,
    pod.country     AS paisPOD,
    l.name          AS linea,
    a.exporter,
    a.container,
    a.seal_number,
    a.booking,
    a.pallets_quantity,
    a.guide_number,
    a.comodity,
    a.arrival_date,
    a.departure_date
  FROM app_outer_port a
  INNER JOIN app_ships v       ON v.ship_id   = a.vessel_id
  INNER JOIN app_ports pol     ON pol.port_id = v.pol
  INNER JOIN app_ports pod     ON pod.port_id = v.pod
  INNER JOIN app_ship_lines l  ON l.line_id   = v.ship_line
  WHERE a.vessel_id = :id
';

$params = [':id' => $id];

if ($exporter !== null) {
    $sql .= ' AND a.exporter = :exporter';
    $params[':exporter'] = $exporter;
}

$sql .= ' ORDER BY a.exporter, a.container';

$stmt = $outer->getDb()->prepare($sql);
$stmt->execute($params);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    echo $alerts->customAlert('warning', 'Atención', 'No hay información disponible para generar la liquidación de esta nave.');

    exit;
}

$query = http_build_query(array_filter([
  'id' => $id,
  'exporter' => $exporter,
]));

echo '
    <div class="text-center mb-3">
        <button type="button" class="btn btn-success" onclick="window.location.href=\'../controllers/exportReportPDF.php?' . $query . '\'"">
            <i class="fa-solid fa-file-pdf"></i> Descargar PDF
        </button>

        <button type="button" class="btn btn-success" onclick="exportExcel(' . $id . ')">
            <i class="fa-solid fa-file-excel"></i> Descargar Excel
        </button>
    </div>
';
