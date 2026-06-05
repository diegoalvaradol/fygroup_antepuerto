<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/includes.php';
require_once __DIR__ . '/../vendor/autoload.php';

$ship = new ship();
$port = new port();

$vesselId = $_REQUEST['vessel'] ?? 0;
$exporter = $_REQUEST['exporter'] ?? '';
$agency = $_REQUEST['agency'] ?? '';

$sql = 'SELECT vessel_name, voyage, pod, eta, etd FROM app_ships WHERE ship_id = :id';
$list = $ship->getFirstMember($sql, ['id' => $vesselId]);

if (!$list) {
    die('No se encontró la nave.');
}

$vessel = $list['vessel_name'];
$voyage = $list['voyage'];
$pod = $port->getPortName($list['pod']);
$eta = (new DateTime($list['eta']))->format('d-m-Y H:i');
$etd = (new DateTime($list['etd']))->format('d-m-Y H:i');

$logoExporter = '';

switch ($exporter) {
    case 'EXPORTADORA UNIFRUTTI TRADERS SPA':
        $logoExporter = realpath(__DIR__ . '/../logos/logo-unifrutti.jpeg');
        break;

    case 'AGRICOLA EL CALVARIO S.A':
        $logoExporter = realpath(__DIR__ . '/../logos/logo-agricola-calvario.jpeg');
        break;

    case 'FGF TRAPANI S.A':
        $logoExporter = realpath(__DIR__ . '/../logos/logo-trapani.jpeg');
        break;
}

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .logo {
            text-align: center;
            margin-top: 20px;
            height: 160px;
        }

        .logo img {
            width: auto;
            height: 160px;
            object-fit: contain;
            background: #fff;
        }

        .content {
            text-align: center;
            margin-top: 250px;
        }

        .vessel {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        .voyage, .eta, .etd {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .pod {
            font-size: 24px;
            font-weight: bold;
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
        }


        .footer {
            position: fixed;
            bottom: 15px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: #000;
        }
    </style>
</head>
<body>
    <?php if (!empty($logoExporter) && file_exists($logoExporter)): ?>
        <div class="logo">
            <img src="<?= $logoExporter ?>">
        </div>
    <?php endif; ?>

    <div class="content">
        <div class="vessel"><?= htmlspecialchars($vessel) ?></div>
        <div class="voyage">Voyage: <?= htmlspecialchars($voyage) ?></div>
        <div class="eta">ETA: <?= htmlspecialchars($eta) ?></div>
        <div class="etd">ETD: <?= htmlspecialchars($etd) ?></div>
        <div class="pod"><?= htmlspecialchars($pod) ?></div>
    </div>

    <div class="signature">
        <img src="<?= realpath(__DIR__ . '/../logos/logo-fygroup-bg-removed.png') ?>" class="signature-logo">
    </div>

    <div class="footer">
        <b><em>Sistema Integral FYGroup</em></b>
    </div>
</body>
</html>
<?php

$html = ob_get_clean();

while (ob_get_level()) {
    ob_end_clean();
}

$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'tempDir' => __DIR__ . '/../tmp',
    'margin_top' => 15,
    'margin_bottom' => 15,
    'margin_left' => 15,
    'margin_right' => 15,
]);

$mpdf->WriteHTML($html);
$filename = "Portada_{$vessel}_{$exporter}.pdf";
$mpdf->Output($filename, 'I');

exit;
