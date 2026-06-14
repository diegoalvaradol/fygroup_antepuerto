<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/includes.php';

session_start();

/* Usuario */
$user = $_SESSION['user'] ?? null;
if (!$user) {
    exit('Sesión no válida');
}

$usuario = sprintf(
    '%s %s',
    $user['name'],
    $user['last_name']
);

/* Validación */
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    exit('ID no válido');
}

$exporter = trim($_GET['exporter'] ?? '');
$exporter = ($exporter === '' || $exporter === '-') ? null : $exporter;

$outer = new outerPort();

$sql = 'SELECT
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
';

$params = [':id' => $id];

if ($exporter !== null) {
    $sql .= ' AND a.exporter = :exporter';
    $params[':exporter'] = $exporter;
}

$stmt = $outer->getDb()->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    exit('Sin datos');
}

/* Proceso */
$invalidos = ['N/A', 'NA', 'NO APLICA', 'SIN', 'SIN CONTENEDOR'];

$resumen = [];
$detalle = [];
$contenedoresGlobal = [];

foreach ($rows as $r) {
    $stayTime = 'No disponible';
    $exp = $r['exporter'];

    $resumen[$exp] ??= ['pallets' => 0, 'containers' => []];
    $resumen[$exp]['pallets'] += (int) $r['pallets_quantity'];

    $raw = strtoupper(trim((string) $r['container']));
    if ($raw !== '' && !in_array($raw, $invalidos, true)) {
        foreach (preg_split('/[,\-\/]+/', $raw) as $c) {
            $c = trim($c);

            if ($c !== '' && !in_array($c, $invalidos, true)) {
                $resumen[$exp]['containers'][$c] = true;
                $contenedoresGlobal[$c] = true;
            }
        }
    }

    if (!empty($r['arrival_date']) && $r['arrival_date'] !== '0000-00-00 00:00:00' && !empty($r['departure_date']) && $r['departure_date'] !== '0000-00-00 00:00:00') {
        $arrivalDate = new DateTime($r['arrival_date']);
        $departureDate = new DateTime($r['departure_date']);

        $interval = $arrivalDate->diff($departureDate);

        $days = $interval->days;
        $hours = $interval->h;
        $minutes = $interval->i;

        $stayTime = "{$days}d {$hours}h {$minutes}m";
    }

    $detalle[$exp][] = $r;
}

/* Base */
$base = $rows[0];

$fmt = fn ($d) => $d ? date('d-m-Y H:i', strtotime($d)) : 'N/A';

$nave = $base['nave'];
$viaje = $base['viaje'];
$linea = $base['linea'];
$pol = "{$base['ciudadPOL']} - {$base['paisPOL']}";
$pod = "{$base['ciudadPOD']} - {$base['paisPOD']}";
$eta = $fmt($base['eta']);
$etd = $fmt($base['etd']);
$fecha = date('d-m-Y H:i:s');

/* HTML */
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 11px;
                color: #333;
            }

            h2 {
                text-align: center;
                margin-bottom: 5px;
            }

            h3 {
                margin-top: 20px;
                color: #4e73df;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            th {
                background: #4e73df;
                color: #fff;
                font-weight: bold;
                font-size: 11px;
            }

            td {
                border: 1px solid #ddd;
                padding: 5px;
            }

            tr:nth-child(even) {
                background: #f9f9f9;
            }

            .sin {
                color: red;
                font-weight: bold;
            }

            .header-box {
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .header-box img {
                position: absolute;
                left: 0;
                height: 70px;
            }

            .header-box h2 {
                margin: 0;
            }

            .signature {
                position: fixed;
                bottom: 35px;
                left: 0;
                width: 100%;
                text-align: center;
            }

            .signature-logo {
                height: 75px;
                display: block;
                margin: 0 auto 8px auto;
                opacity: 0.95;
                transform: rotate(-15deg);
            }

            .signature-text {
                font-size: 10px;
                color: #000;
                line-height: 1.4;
            }

            .footer {
                position: fixed;
                bottom: 15px;
                left: 0;
                width: 100%;
                text-align: center;
                font-size: 10px;
                color: #000;
            }
        </style>
    </head>

    <body>
        <div class="header-box">
            <img src="../logos/logo-fygroup-bg-removed.png" style="height:50px;">
            <h2>Liquidación de Nave</h2>
        </div>

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
            <?php foreach ($detalle as $exp => $items): ?>
                <!-- Exportador -->
                <tr>
                    <td colspan="9" style="background:#d9d9d9; font-weight:bold; font-size:13px; text-align:left; padding:8px; border:1px solid #ccc;">
                        <?= strtoupper($exp) ?>
                    </td>
                </tr>

                <!-- Columnas -->
                <tr>
                    <th style="text-align:left;">Guía</th>
                    <th style="text-align:left;">Condición</th>
                    <th style="text-align:left;">Contenedor</th>
                    <th style="text-align:left;">Sello</th>
                    <th style="text-align:left;">Booking</th>
                    <th style="text-align:left;">Pallets</th>
                    <th style="text-align:left;">Llegada</th>
                    <th style="text-align:left;">Salida</th>
                    <th style="text-align:left;">Estadía</th>
                </tr>

                <?php foreach ($items as $r): ?>
                    <?php
                    $stayTime = 'No disponible';

                    if (!empty($r['arrival_date']) && $r['arrival_date'] !== '0000-00-00 00:00:00' && !empty($r['departure_date']) && $r['departure_date'] !== '0000-00-00 00:00:00') {
                        $arrivalDate = new DateTime($r['arrival_date']);
                        $departureDate = new DateTime($r['departure_date']);

                        $interval = $arrivalDate->diff($departureDate);

                        $days = $interval->days;
                        $hours = $interval->h;
                        $minutes = $interval->i;

                        $stayTime = "{$days}d {$hours}h {$minutes}m";
                    }
                    ?>

                    <tr>
                        <td><?= $r['guide_number'] ?></td>
                        <td><?= $r['comodity'] ?></td>
                        <td><?= ($r['container'] && !in_array(strtoupper($r['container']), $invalidos, true)) ? strtoupper($r['container']) : '<span class="sin">SIN CONTENEDOR</span>'; ?></td>
                        <td><?= $r['seal_number'] ?></td>
                        <td><?= $r['booking'] ?></td>
                        <td><?= $r['pallets_quantity'] ?></td>
                        <td><?= $fmt($r['arrival_date']) ?></td>
                        <td><?= $fmt($r['departure_date']) ?></td>
                        <td><?= $stayTime ?></td>
                    </tr>
                <?php endforeach; ?>

                <!-- Espacio entre exportadores -->
                <tr>
                    <td colspan="9" style="border:none; height:14px; background:#fff; padding:0;"></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div style="page-break-before:always;">
            <h3>Resumen por Exportador</h3>
            <table>
                <tr>
                    <th>Exportador</th>
                    <th>Pallets</th>
                    <th>Contenedores</th>
                </tr>

                <?php $tp = 0;?>
                <?php foreach ($resumen as $exp => $r): ?>
                    <tr>
                        <td><?= $exp ?></td>
                        <td><?= number_format($r['pallets'], 0, ',', '.') ?></td>
                        <td><?= count($r['containers']) ?></td>
                    </tr>
                <?php $tp += $r['pallets'];?>
                <?php endforeach; ?>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong><?= number_format($tp, 0, ',', '.') ?></strong></td>
                    <td><strong><?= count($contenedoresGlobal) ?></strong></td>
                </tr>
            </table>
        </div>

        <div class="signature">
            <img src="../images/timbre-fygroup-bg-removed.png" alt="Firma" class="signature-logo">

            <div class="signature-text">
                <div style="margin: 1px auto; width: 70px; border-top: 1px solid #000;"></div>
                <b><em>Firma</em></b>
            </div>
        </div>

        <div class="footer">
            <b><em>Generado por <?= $usuario ?> - <?= date('d/m/Y H:i') ?></em></b>
        </div>
    </body>
</html>
<?php
$html = ob_get_clean();

/* Generar PDF */
$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4-L',
    'tempDir' => __DIR__ . '/../tmp',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
]);

$mpdf->WriteHTML($html);
$mpdf->Output("Liquidacion_Nave_{$nave}_{$viaje}_{$fecha}.pdf", 'D');
