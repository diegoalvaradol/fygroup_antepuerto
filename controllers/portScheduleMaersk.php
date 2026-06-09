<?php

declare(strict_types=1);

$fromDate = $_POST['fromDate'] ?? date('Y-m-d');
$toDate = $_POST['toDate'] ?? date('Y-m-d', strtotime('+30 days'));
$port = $_POST['port'];

$url = 'https://api.maersk.com/synergy/schedules/port-calls?' . http_build_query([
    'portCode' => $port,
    'fromDate' => $fromDate,
    'toDate' => $toDate,
    'carrierCodes' => 'MAEU',
]);

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'Consumer-Key: uXe7bxTHLY0yY0e8jnS6kotShkLuAAqG',
    ],
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$hoy = new DateTime();

$html = '
    <style>
    .card-programado{
        border-left:5px solid #4e73df !important;
    }

    .card-zarpado{
        border-left:5px solid #1cc88a !important;
        background:#ecfdf5;
    }

    .card-zarpado .text-muted{
        color:#065f46 !important;
    }

    .card-zarpado .font-weight-bold{
        color:#065f46 !important;
    }
    </style>
';

foreach (($data['portCalls'] ?? []) as $row) {
    $fechaLlegada = new DateTime($row['arrivalTime']);
    $fechaSalida = new DateTime($row['departureTime']);

    $eta = $fechaLlegada->format('d M Y H:i');
    $etd = $fechaSalida->format('d M Y H:i');

    $claseCard = ($fechaSalida < $hoy) ? 'card-zarpado' : 'card-programado';

    $html .= '<div class="card shadow-sm mb-3 ' . $claseCard . '">';
    $html .= '    <div class="card-body">';
    $html .= '        <div class="row">';

    /* Motonave / Viaje */
    $html .= '            <div class="col-lg-3 col-md-6 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Motonave / Viaje</div>';
    $html .= '                <div class="font-weight-bold text-dark">';
    $html .= htmlspecialchars($row['vesselName'] ?? '');
    $html .= '                </div>';
    $html .= '                <div class="text-primary">';
    $html .= htmlspecialchars($row['departureVoyageNumber'] ?? '');

    if (!empty($row['arrivalVoyageNumber'])) {
        $html .= ' | ' . htmlspecialchars($row['arrivalVoyageNumber']);
    }

    $html .= '                </div>';
    $html .= '            </div>';

    /* Terminal (POL) */
    $html .= '            <div class="col-lg-2 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Terminal <em>(POL)</em></div>';
    $html .= '                <div>';
    $html .= htmlspecialchars($row['marineContainerTerminalName'] ?? '');
    $html .= '                </div>';
    $html .= '            </div>';

    /* ETA */
    $html .= '            <div class="col-lg-2 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Arrivo <em>(ETA)</em></div>';
    $html .= '                <div>';
    $html .= $eta;
    $html .= '                </div>';
    $html .= '            </div>';

    /* ETD */
    $html .= '            <div class="col-lg-2 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Salida <em>(ETD)</em></div>';
    $html .= '                <div>';
    $html .= $etd;
    $html .= '                </div>';
    $html .= '            </div>';

    /* Servicio */
    $html .= '            <div class="col-lg-1 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Servicio</div>';
    $html .= '                <div>';
    $html .= htmlspecialchars($row['departureServiceName'] ?? '');
    $html .= '                </div>';
    $html .= '            </div>';

    /* Destino (POD) */
    $html .= '            <div class="col-lg-1 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Destino <em>(POD)</em></div>';
    $html .= '                <div>Balboa - Panamá</div>';
    $html .= '            </div>';

    /* Destino Final */
    $html .= '            <div class="col-lg-1 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Destino Final</div>';
    $html .= '                <div>';
    $html .= htmlspecialchars($row['departureServiceCode'] ?? '');
    $html .= '                </div>';
    $html .= '            </div>';

    $html .= '        </div>';
    $html .= '    </div>';
    $html .= '</div>';
}

echo $html;
