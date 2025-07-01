<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/includes.php';

use Dompdf\Dompdf;

/* Usuario que genera el PDF */
session_start();
$usuario = $_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"] . ' (' . $_SESSION["user"]["run"] . ')';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
  exit("ID no válido");
}

$db = (new Database())->getConnection();

$sql = "SELECT
          v.vessel_name AS nave,
          v.eta AS eta,
          v.etd AS etd,
          v.voyage AS viaje,
          p.city AS ciudad,
          p.country AS pais,
          l.name AS linea,
          a.exporter,
          a.container,
          a.pallets_quantity,
          a.guide_number,
          a.comodity
        FROM app_outer_port a
        JOIN app_ships v ON v.ship_id = a.vessel_id
        JOIN app_ports p ON p.port_id = v.port_discharge
        JOIN app_ship_lines l ON l.line_id = v.ship_line
        WHERE a.vessel_id = :id AND a.origin = 1
        ORDER BY a.exporter, a.container";

$stmt = $db->prepare($sql);
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Agrupar pallets por exportador */
$resumen = [];
foreach ($rows as $r) {
  $exp           = $r['exporter'];
  $resumen[$exp] = ($resumen[$exp] ?? 0) + (int) $r['pallets_quantity'];
}

$nave    = $rows[0]['nave'] ?? 'N/A';
$viaje   = $rows[0]['viaje'] ?? 'N/A';
$destino = $rows[0]['ciudad'] . ' - ' . $rows[0]['pais'] ?? 'N/A';
$linea   = $rows[0]['linea'] ?? 'N/A';
$eta     = $rows[0]['eta'] ?? 'N/A';
$etd     = $rows[0]['etd'] ?? 'N/A';

/* HTML */
$html = "
<style>
  body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; }
  h1, h2, h3 { text-align: center; margin: 10px 0; }
  table { width: 100%; border-collapse: collapse; margin-top: 15px; }
  th, td { border: 1px solid #666; padding: 6px; text-align: left; }
  th { background-color: #f0f0f0; }
  .logo { text-align: center; margin-bottom: 10px; }
</style>
";

$html .= "<h1><strong>Liquidación de Nave</strong></h1>";

$html .= "<h3>Información de Viaje</h3>
<table style='margin-top: 10px;'>
  <tr>
    <th>Nave</th><td>" . htmlspecialchars($nave) . "</td>
    <th>Viaje</th><td>" . htmlspecialchars($viaje) . "</td>
    <th>Línea</th><td>" . htmlspecialchars($linea) . "</td>
  </tr>
  <tr>
    <th>Destino</th><td>" . htmlspecialchars($destino) . "</td>
    <th>ETA</th><td>" . htmlspecialchars(date("d-m-Y H:i", strtotime($eta))) . "</td>
    <th>ETD</th><td>" . htmlspecialchars(date("d-m-Y H:i", strtotime($etd))) . "</td>
  </tr>
</table>
";

$html .= "<h3>Detalle de Carga</h3>";
$html .= "<table>
  <thead>
    <tr>
      <th>Nave</th>
      <th>Viaje</th>
      <th>Exportador</th>
      <th>N° Guía</th>
      <th>Condición</th>
      <th>Contenedor</th>
      <th>Pallets</th>
    </tr>
  </thead>
  <tbody>";

foreach ($rows as $r) {
  $html .= "<tr>
    <td>" . htmlspecialchars($r['nave']) . "</td>
    <td>" . htmlspecialchars($r['viaje']) . "</td>
    <td>" . htmlspecialchars($r['exporter']) . "</td>
    <td>" . htmlspecialchars($r['guide_number']) . "</td>
    <td>" . htmlspecialchars($r['comodity']) . "</td>
    <td>" . htmlspecialchars($r['container']) . "</td>
    <td>" . htmlspecialchars($r['pallets_quantity']) . "</td>
  </tr>";
}

$html .= "</tbody></table>";

/* Resumen */
$html .= "<h3>Resumen por Exportador</h3>
<table>
  <thead>
    <tr><th>Exportador</th><th>Total Pallets</th></tr>
  </thead>
  <tbody>";

$totalGeneral = 0;
foreach ($resumen as $exp => $total) {
  $html .= "<tr><td>" . htmlspecialchars($exp) . "</td><td>$total</td></tr>";
  $totalGeneral += $total;
}

$html .= "<tr><td><strong>Total General</strong></td><td><strong>$totalGeneral</strong></td></tr>";
$html .= "</tbody></table>";

/* Pie de página */
$html .= "<div style='position: fixed; bottom: 20px; width: 100%; text-align: center; font-size: 10px; color: #888;'>
  Generado por: " . htmlspecialchars($usuario) . " con fecha " . date("d/m/Y H:i") . "
</div>";

/* Render PDF */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Liquidacion_de_Nave_' . $nave . '_Viaje_' . $viaje . '.pdf', ["Attachment" => true]);
