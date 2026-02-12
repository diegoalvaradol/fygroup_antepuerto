<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/includes.php';

use Dompdf\Dompdf;

session_start();

/* ========= USUARIO ========= */
$user = $_SESSION['user'] ?? null;
if (!$user) {
  exit('Sesión no válida');
}

$usuario = sprintf(
  '%s %s (%s)',
  $user['name'],
  $user['last_name'],
  $user['run']
);

/* ========= VALIDACIÓN ========= */
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
  exit('ID no válido');
}

$exporter = trim($_GET['exporter'] ?? '');
$exporter = ($exporter === '' || $exporter === '-') ? null : $exporter;

/* ========= DB ========= */
$port = new outerPort();

/* ========= CONSULTA ========= */
$sql = "
SELECT
  v.vessel_name AS nave,
  v.eta,
  v.etd,
  v.voyage AS viaje,
  pol.city AS ciudadPOL,
  pol.country AS paisPOL,
  pod.city AS ciudadPOD,
  pod.country AS paisPOD,
  l.name AS linea,
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
";

$params = [':id' => $id];

if ($exporter !== null) {
  $sql .= " AND a.exporter = :exporter";
  $params[':exporter'] = $exporter;
}

$stmt = $outer->getDb()->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
  exit('Sin datos');
}

/* ========= PROCESO ========= */
$invalidos = ['N/A', 'NA', 'NO APLICA', 'SIN', 'SIN CONTENEDOR'];

$resumen            = [];
$detalle            = [];
$contenedoresGlobal = [];

foreach ($rows as $r) {
  $exp = $r['exporter'];

  $resumen[$exp] ??= ['pallets' => 0, 'containers' => []];
  $resumen[$exp]['pallets'] += (int) $r['pallets_quantity'];

  $raw = strtoupper(trim((string) $r['container']));
  if ($raw !== '' && !in_array($raw, $invalidos, true)) {
    foreach (preg_split('/[,\-\/]+/', $raw) as $c) {
      $c = trim($c);
      if ($c !== '' && !in_array($c, $invalidos, true)) {
        $resumen[$exp]['containers'][$c] = true;
        $contenedoresGlobal[$c]          = true;
      }
    }
  }

  $detalle[$exp][] = $r;
}

/* ========= BASE ========= */
$base = $rows[0];

$fmt = fn($d) => $d ? date('d-m-Y H:i', strtotime($d)) : 'N/A';

$nave  = $base['nave'];
$viaje = $base['viaje'];
$linea = $base['linea'];
$pol   = "{$base['ciudadPOL']} - {$base['paisPOL']}";
$pod   = "{$base['ciudadPOD']} - {$base['paisPOD']}";
$eta   = $fmt($base['eta']);
$etd   = $fmt($base['etd']);

/* ========= HTML ========= */
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
body { font-family: Calibri, sans-serif; font-size:12px; }
table { width:100%; border-collapse:collapse; margin-top:10px; }
th, td { border:1px solid #4e73df; padding:6px; }
th { background:#4e73df; color:#fff; }
.sin { color:red; font-weight:bold; }
.footer { position:fixed; bottom:0; width:100%; text-align:center; font-size:10px; }
h2,h3 { text-align:center; }
</style>
</head>
<body>

<h2>Liquidación de Nave</h2>

<table>
<tr>
  <th>Nave</th><td><?= $nave ?></td>
  <th>Viaje</th><td><?= $viaje ?></td>
  <th>Línea</th><td><?= $linea ?></td>
  <th>ETA</th><td><?= $eta ?></td>
</tr>
<tr>
  <th>POL</th><td><?= $pol ?></td>
  <th>POD</th><td><?= $pod ?></td>
  <th>ETD</th><td><?= $etd ?></td>
  <th>Liquidación</th><td><?= date('d/m/Y H:i') ?></td>
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
  <th>Llegada</th>
  <th>Salida</th>
</tr>

<?php foreach ($detalle as $exp => $items): ?>
<tr>
  <td colspan="8" style="background:#ddd;font-weight:bold">Exportador: <?= $exp ?></td>
</tr>
<?php foreach ($items as $r): ?>
<tr>
  <td><?= $r['guide_number'] ?></td>
  <td><?= $r['comodity'] ?></td>
  <td>
    <?=
($r['container'] && !in_array(strtoupper($r['container']), $invalidos, true))
? strtoupper($r['container'])
: '<span class="sin">SIN CONTENEDOR</span>';
?>
  </td>
  <td><?= $r['seal_number'] ?></td>
  <td><?= $r['booking'] ?></td>
  <td><?= $r['pallets_quantity'] ?></td>
  <td><?= $fmt($r['arrival_date']) ?></td>
  <td><?= $fmt($r['departure_date']) ?></td>
</tr>
<?php endforeach;endforeach; ?>
</table>

<div style="page-break-before:always;">
<h3>Resumen por Exportador</h3>
<table>
<tr>
  <th>Exportador</th>
  <th>Pallets</th>
  <th>Contenedores</th>
</tr>

<?php $tp = 0;foreach ($resumen as $exp => $r): ?>
<tr>
  <td><?= $exp ?></td>
  <td><?= $r['pallets'] ?></td>
  <td><?= count($r['containers']) ?></td>
</tr>
<?php $tp += $r['pallets'];endforeach; ?>

<tr>
  <td><strong>Total</strong></td>
  <td><strong><?= $tp ?></strong></td>
  <td><strong><?= count($contenedoresGlobal) ?></strong></td>
</tr>
</table>
</div>

<div class="footer">Generado por <?= $usuario ?> - <?= date('d/m/Y H:i') ?></div>
</body>
</html>
<?php
$html = ob_get_clean();

/* ========= PDF ========= */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Liquidacion_Nave_{$nave}_{$viaje}.pdf", ['Attachment' => true]);
