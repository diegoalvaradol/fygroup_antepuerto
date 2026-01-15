<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/includes.php';

use Dompdf\Dompdf;

/* Usuario que genera el PDF */
session_start();
$usuario = $_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"] . ' (' . $_SESSION["user"]["run"] . ')';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
  exit("Id no válido.");
}

$exporter = $_GET['exporter'] ?? null;

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
          a.comodity,
          a.seal_number,
          a.booking
        FROM app_outer_port a
        JOIN app_ships v ON v.ship_id = a.vessel_id
        JOIN app_ports p ON p.port_id = v.port_discharge
        JOIN app_ship_lines l ON l.line_id = v.ship_line
        WHERE a.vessel_id = :id";

// Si exporter es válido, se agrega al filtro
if (!empty($exporter) && $exporter !== '-') {
  $sql .= " AND a.exporter = :exporter";
}

$sql .= " ORDER BY a.exporter, a.container";

$stmt = $db->prepare($sql);
$stmt->bindParam(":id", $id, PDO::PARAM_INT);

if (!empty($exporter) && $exporter !== '-') {
  $stmt->bindParam(":exporter", $exporter, PDO::PARAM_STR);
}

$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Agrupar pallets y contenedores por exportador */
$resumen = [];
foreach ($rows as $r) {
  $exp                                            = $r['exporter'];
  $resumen[$exp]['pallets']                       = ($resumen[$exp]['pallets'] ?? 0) + (int) $r['pallets_quantity'];
  $resumen[$exp]['contenedores'][$r['container']] = true; // evitar duplicados
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
  @page { margin-bottom: 60px; }
  .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #888; }
</style>
";

$html .= '<html>';
$html .= '<body>';
$html .= '<h1><strong>Liquidación de Nave</strong></h1>';

if (!empty($exporter) && $exporter !== '-') {
  $html .= '<h2 style="text-align: center; font-size: 14px;">Exportador: ' . htmlspecialchars($exporter) . '</h2>';
}

$html .= "<h3>Información de Viaje</h3>";
$html .= "<table style='margin-top: 10px;'>";
$html .= "<tr>";
$html .= "<th>Nave:</th><td>" . htmlspecialchars($nave) . "</td>";
$html .= "<th>Viaje:</th><td>" . htmlspecialchars($viaje) . "</td>";
$html .= "<th>Línea:</th><td>" . htmlspecialchars($linea) . "</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<th>Destino:</th><td>" . htmlspecialchars($destino) . "</td>";
$html .= "<th>ETA:</th><td>" . htmlspecialchars(date("d-m-Y H:i", strtotime($eta))) . "</td>";
$html .= "<th>ETD:</th><td>" . htmlspecialchars(date("d-m-Y H:i", strtotime($etd))) . "</td>";
$html .= "</tr>";
$html .= "</table>";

$html .= "<h3>Desgloce de Carga</h3>";
$html .= "<table>";
$html .= "<thead>";
$html .= "<tr>";
$html .= "<th>Guía(s)</th>";
$html .= "<th>Condición</th>";
$html .= "<th>Contenedor</th>";
$html .= "<th>Sello</th>";
$html .= "<th>Booking</th>";
$html .= "<th>Pallets</th>";
$html .= "</tr>";
$html .= "</thead>";
$html .= "<tbody>";

$agrupado = [];
foreach ($rows as $r) {
  $agrupado[$r['exporter']][] = $r;
}

foreach ($agrupado as $exportador => $items) {
  $html .= "<tr><td colspan='6' style='background-color: #ddd; font-weight: bold;'>Exportador: " . htmlspecialchars($exportador) . "</td></tr>";
  foreach ($items as $r) {
    $html .= "<tr>";
    $html .= "<td>" . htmlspecialchars($r['guide_number']) . "</td>";
    $html .= "<td>" . htmlspecialchars($r['comodity']) . "</td>";
    $html .= "<td>" . htmlspecialchars($r['container']) . "</td>";
    $html .= "<td>" . htmlspecialchars($r['seal_number']) . "</td>";
    $html .= "<td>" . htmlspecialchars($r['booking']) . "</td>";
    $html .= "<td>" . htmlspecialchars($r['pallets_quantity']) . "</td>";
    $html .= "</tr>";
  }
}
$html .= "</tbody></table>";

/* Resumen */
$html .= "<h3>Resumen por Exportador</h3>";
$html .= "<table>";
$html .= "<thead>";
$html .= "<tr><th>Exportador</th><th>Total Pallets</th><th>Total Contenedores</th></tr>";
$html .= "</thead>";
$html .= "<tbody>";

$totalGeneralPallets      = 0;
$totalGeneralContenedores = 0;

foreach ($resumen as $exp => $data) {
  $totalPallets      = $data['pallets'];
  $totalContenedores = count($data['contenedores']);

  $html .= "<tr><td>" . htmlspecialchars($exp) . "</td><td>$totalPallets</td><td>$totalContenedores</td></tr>";

  $totalGeneralPallets += $totalPallets;
  $totalGeneralContenedores += $totalContenedores;
}

$html .= "<tr><td><strong>Total General</strong></td><td><strong>$totalGeneralPallets</strong></td><td><strong>$totalGeneralContenedores</strong></td></tr>";
$html .= "</tbody></table>";

/* Pie de página */
$html .= "<div class='footer'>";
$html .= "Generado por: " . htmlspecialchars($usuario) . " con fecha " . date("d/m/Y H:i");
$html .= "</div>";

/* Render PDF */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Liquidacion_de_Nave_' . $nave . '_Viaje_' . $viaje . '.pdf', ["Attachment" => true]);
