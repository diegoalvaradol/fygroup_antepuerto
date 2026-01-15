<?php
require_once __DIR__ . '/../config/includes.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
  exit("ID de motonave no válido.");
}

$id       = intval($_POST['id']);
$exporter = $_POST['exporter'] ?? null;

// Si viene '-', se trata como null (sin filtro)
if ($exporter === '-' || $exporter === '') {
  $exporter = null;
}

$db = (new Database())->getConnection();

// Consulta base
$sql = "SELECT
          v.vessel_name AS nave,
          v.eta AS eta,
          v.etd AS etd,
          v.voyage AS viaje,
          pod.city AS ciudad,
          pod.country AS pais,
          l.name AS linea,
          a.exporter,
          a.container,
          a.pallets_quantity,
          a.guide_number,
          a.comodity
        FROM app_outer_port a
        JOIN app_ships v ON v.ship_id = a.vessel_id
        JOIN app_ports pol ON pol.port_id = v.pol
        JOIN app_ports pod ON pod.port_id = v.pod
        JOIN app_ship_lines l ON l.line_id = v.ship_line
        WHERE a.vessel_id = :id";

if ($exporter !== null) {
  $sql .= " AND a.exporter = :exporter";
}

$sql .= " ORDER BY a.exporter, a.container";

$stmt = $db->prepare($sql);
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
if ($exporter !== null) {
  $stmt->bindParam(":exporter", $exporter, PDO::PARAM_STR);
}
$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
  echo '<div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
          <div>
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <strong> Atención:</strong> No hay información disponible para generar la liquidación de esta nave.
          </div>
        </div>';
} else {
  $url = "../controllers/exportReportPDF.php?id=" . $id;
  if ($exporter !== null) {
    $url .= "&exporter=" . urlencode($exporter);
  }

  echo '<div style="text-align: center; margin-bottom: 1rem;">
          <a href="' . $url . '" download class="btn btn-mn btn-success">
            <i class="fa-solid fa-file-pdf"></i> Descargar PDF de Liquidación
          </a>
        </div>';
}
