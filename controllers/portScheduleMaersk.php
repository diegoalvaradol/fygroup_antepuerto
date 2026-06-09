<?php

declare(strict_types=1);

$url = 'https://api.maersk.com/synergy/schedules/port-calls?' . http_build_query([
    'portCode' => '3PL6KRQMXKB5Q',
    'fromDate' => '2026-06-01',
    'toDate' => '2026-09-30',
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

$html = '';

foreach (($data['portCalls'] ?? []) as $row) {
    $eta = (new DateTime($row['arrivalTime']))->format('d M Y H:i');
    $etd = (new DateTime($row['departureTime']))->format('d M Y H:i');

    $html .= '<div class="card shadow-sm mb-3 border-left-primary">';
    $html .= '    <div class="card-body">';
    $html .= '        <div class="row">';

    /* Motonave / Viaje */
    $html .= '            <div class="col-lg-3 col-md-6 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Motonave / Viaje</div>';
    $html .= '                <div class="font-weight-bold text-dark">';
    $html .= htmlspecialchars($row['vesselName']);
    $html .= '                </div>';
    $html .= '                <div class="text-primary">';
    $html .= htmlspecialchars($row['departureVoyageNumber']);

    if (!empty($row['arrivalVoyageNumber'])) {
        $html .= ' | ' . htmlspecialchars($row['arrivalVoyageNumber']);
    }

    $html .= '                </div>';
    $html .= '            </div>';

    /* Terminal (POD) */
    $html .= '            <div class="col-lg-2 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Terminal <em>(POD)</em></div>';
    $html .= '                <div>';
    $html .= htmlspecialchars($row['marineContainerTerminalName']);
    $html .= '                </div>';
    $html .= '            </div>';

    /* Arrivo (ETA) */
    $html .= '            <div class="col-lg-2 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Arrivo <em>(ETA)</em></div>';
    $html .= '                <div>';
    $html .= $eta;
    $html .= '                </div>';
    $html .= '            </div>';

    /* Zarpe (ETD) */
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
    $html .= htmlspecialchars($row['departureServiceName']);
    $html .= '                </div>';
    $html .= '            </div>';

    /* Destino (POL) */
    $html .= '            <div class="col-lg-1 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Destino <em>(POL)</em></div>';
    $html .= '                <div>';
    $html .= htmlspecialchars('Balboa - Panamá');
    $html .= '                </div>';
    $html .= '            </div>';

    /* Destino Final */
    $html .= '            <div class="col-lg-1 col-md-12 mb-3">';
    $html .= '                <div class="text-muted small font-weight-bold text-uppercase mb-1">Destino Final</div>';
    $html .= '                <div>';
    $html .= htmlspecialchars($row['departureServiceCode']);
    $html .= '                </div>';
    $html .= '            </div>';

    $html .= '        </div>';
    $html .= '    </div>';
    $html .= '</div>';
}

echo $html;
