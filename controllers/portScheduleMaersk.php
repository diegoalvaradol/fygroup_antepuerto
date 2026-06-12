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

ob_start();
?>

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

<?php foreach (($data['portCalls'] ?? []) as $row): ?>
    <?php
    $fechaLlegada = new DateTime($row['arrivalTime']);
    $fechaSalida = new DateTime($row['departureTime']);
    $claseCard = ($fechaSalida < $hoy) ? 'card-zarpado' : 'card-programado';
    $allowCreate = ($fechaSalida < $hoy) ? 0 : 1;

    $eta = $fechaLlegada->format('d-m-Y H:i');
    $etd = $fechaSalida->format('d-m-Y H:i');

    $pol = explode(' ', $row['marineContainerTerminalName'])[0];
    $pod = 'Balboa - Panamá';
    $podCode = explode(' - ', $pod)[0];
    ?>

    <div class="card shadow-sm mb-3 <?= $claseCard ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        Motonave / Viaje
                    </div>

                    <div class="font-weight-bold text-dark">
                        <?= htmlspecialchars($row['vesselName'] ?? '') ?>
                    </div>

                    <div class="text-primary">
                        <?= htmlspecialchars($row['departureVoyageNumber'] ?? '') ?>

                        <?php if (!empty($row['arrivalVoyageNumber'])): ?>
                            | <?= htmlspecialchars($row['arrivalVoyageNumber']) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-12 mb-3">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        Terminal <em>(POL)</em>
                    </div>

                    <div>
                        <?= htmlspecialchars($pol) ?>
                    </div>
                </div>

                <div class="col-lg-2 col-md-12 mb-3">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        Arrivo <em>(ETA)</em>
                    </div>

                    <div><?= $eta ?></div>
                </div>

                <div class="col-lg-2 col-md-12 mb-3">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        Salida <em>(ETD)</em>
                    </div>

                    <div><?= $etd ?></div>
                </div>

                <div class="col-lg-1 col-md-12 mb-3">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        Servicio
                    </div>

                    <div>
                        <?= htmlspecialchars($row['departureServiceName'] ?? '') ?>
                    </div>
                </div>

                <div class="col-lg-1 col-md-12 mb-3">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        Destino <em>(POD)</em>
                    </div>

                    <div>
                        <?= htmlspecialchars($pod) ?>
                    </div>
                </div>

                <div class="col-lg-1 col-md-12 mb-3">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        Destino Final
                    </div>

                    <div>
                        <?= htmlspecialchars($row['departureServiceCode'] ?? '') ?>
                    </div>
                </div>

                <div class="col-lg-1 col-md-12 mb-3">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        Añadir a Sistema
                    </div>

                    <div>
                        <?php if ($allowCreate) : ?>
                            <button
                                type="button"
                                class="btn btn-success btn-user"
                                onclick="bookVesselSystem(
                                    '<?= addslashes($row['vesselName']) ?>',
                                    'MAERSK LINE',
                                    '<?= addslashes($row['departureVoyageNumber']) ?>',
                                    '<?= $row['arrivalTime'] ?>',
                                    '<?= $row['departureTime'] ?>',
                                    '<?= $pol ?>',
                                    '<?= $podCode ?>',
                                    'API_MAERSK'
                                )">
                                <i class="fas fa-circle-plus"></i> Añadir
                            </button>
                        <?php else : ?>
                            <button type="button" class="btn btn-danger btn-user" disabled> <i class="fas fa-circle-xmark"></i> Añadir</button>
                        <?php endif ; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php endforeach; ?>

<?php
    echo ob_get_clean();
?>
