<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$dateStart = $_POST['date'];
$shifts = $_POST['shifts'];

/* Verifica el horario de los turnos */
list($inicio, $fin) = array_map('trim', explode(' - ', $shifts));

/* Confecciona las fechas e indica si se suma un dia para cuando es 3° turno */
if ($fin === '08:00') {
    $dateEnd = date('Y-m-d', strtotime($dateStart . ' +1 day'));
} else {
    $dateEnd = $dateStart;
}

echo $outerPort->shiftsReport($shifts, $dateStart, $dateEnd);
