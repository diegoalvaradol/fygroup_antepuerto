<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/includes.php';

use Dompdf\Dompdf;

session_start();

/* ================= USUARIO ================= */
$usuario = $_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"] . ' (' . $_SESSION["user"]["run"] . ')';

/* ================= VALIDACIÓN ================= */
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
  exit('Id no válido');
}

/* ================= CONEXIÓN DB ================= */
$db = (new Database())->getConnection();

/* ================= CONSULTA ================= */
$sql = "
SELECT
    v.vessel_name AS nave,
    v.voyage AS viaje,
    v.eta,
    v.etd,
    pod.city AS ciudad,
    pod.country AS pais,
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
JOIN app_ports pol ON pol.port_id = v.pol
JOIN app_ports pod ON pod.port_id = v.pod
JOIN app_ship_lines l ON l.line_id = v.ship_line
WHERE a.vessel_id = :id
ORDER BY a.exporter, a.container
";

$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ================= PROCESO ================= */
$resumen = [];
$detalle = [];

foreach ($rows as $r) {
  $exp = $r['exporter'];
  if (!isset($resumen[$exp])) {
    $resumen[$exp] = [
      'pallets'    => 0,
      'containers' => []
    ];
  }
  $resumen[$exp]['pallets'] += (int) $r['pallets_quantity'];
  if (!empty($r['container'])) {
    $resumen[$exp]['containers'][$r['container']] = true;
  }
  $detalle[$exp][] = $r;
}

/* ================= DATOS BASE ================= */
$base    = $rows[0] ?? [];
$nave    = $base['nave'] ?? 'N/A';
$viaje   = $base['viaje'] ?? 'N/A';
$destino = ($base['ciudad'] ?? '') . ' - ' . ($base['pais'] ?? '');
$linea   = $base['linea'] ?? 'N/A';
$eta     = $base['eta'] ? date('d-m-Y H:i', strtotime($base['eta'])) : 'N/A';
$etd     = $base['etd'] ? date('d-m-Y H:i', strtotime($base['etd'])) : 'N/A';

/* ================= HTML ================= */
$html = "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<style>
body { font-family: Arial, sans-serif; font-size: 12px; margin:0; padding:0; }
.header { text-align:center; margin-bottom:15px; }
table { width:100%; border-collapse:collapse; margin-top:10px; }
th, td { border:1px solid #555; padding:6px; text-align:left; }
th { background:#eee; }
.sin { color:red; font-weight:bold; }
.footer { position:fixed; bottom:0; width:100%; text-align:center; font-size:10px; color:#777; }
h2, h3 { text-align:center; margin:5px 0; }
</style>
</head>
<body>

<div class='header'>
  <h2>Liquidación de Nave</h2>
</div>

<table>
<tr>
  <th>Nave</th><td>$nave</td>
  <th>Viaje</th><td>$viaje</td>
  <th>Línea</th><td>$linea</td>
</tr>
<tr>
  <th>Destino</th><td>$destino</td>
  <th>ETA</th><td>$eta</td>
  <th>ETD</th><td>$etd</td>
</tr>
</table>

<h3>Desglose de Carga</h3>
<table>
<tr>
  <th>Guía</th>
  <th>Condición</th>
  <th>Contenedor</th>
  <th>Sello</th>
  <th>Booking</th>
  <th>Pallets</th>
</tr>
";

foreach ($detalle as $exp => $items) {
  $html .= "<tr><td colspan='6' style='background:#ddd; font-weight:bold;'>Exportador: $exp</td></tr>";

  foreach ($items as $r) {
    $cont = $r['container'] ?: "<span class='sin'>SIN CONTENEDOR</span>";
    $html .= "
        <tr>
          <td>{$r['guide_number']}</td>
          <td>{$r['comodity']}</td>
          <td>$cont</td>
          <td>{$r['seal_number']}</td>
          <td>{$r['booking']}</td>
          <td>{$r['pallets_quantity']}</td>
        </tr>";
  }
}

$html .= "</table>

<h3>Resumen por Exportador</h3>
<table>
<tr>
  <th>Exportador</th>
  <th>Pallets</th>
  <th>Contenedores</th>
</tr>
";

$tp = $tc = 0;
foreach ($resumen as $exp => $r) {
  $p = $r['pallets'];
  $c = count($r['containers'] ?? []);
  $html .= "
    <tr>
      <td>$exp</td>
      <td>$p</td>
      <td>$c</td>
    </tr>";
  $tp += $p;
  $tc += $c;
}

$html .= "
<tr>
  <td><strong>Total</strong></td>
  <td><strong>$tp</strong></td>
  <td><strong>$tc</strong></td>
</tr>
</table>

<div class='footer'>Generado por $usuario - " . date('d/m/Y H:i') . "</div>

</body>
</html>
";

/* ================= PDF ================= */
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', true); // necesario para file://
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('Liquidacion_de_Nave_' . $nave . '_Viaje_' . $viaje . '.pdf', ["Attachment" => true]);
