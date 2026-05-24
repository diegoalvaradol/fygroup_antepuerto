<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();

$date = $_GET['date'] ?? null;
$shifts = $_GET['shifts'] ?? null;
$excel = $_GET['excel'] ?? null;
$pdf = $_GET['pdf'] ?? null;
$dateStart = $date;
$dateEnd = $date;

if (!$date || !$shifts) {
    exit('Datos inválidos');
}

if ($excel === '1') {
    $outerPort->shiftsReportExcel($shifts, $dateStart, $dateEnd);
} elseif ($pdf === '1') {
    $outerPort->shiftsReportPDF($shifts, $dateStart, $dateEnd);
} else {
    exit('Formato de exportación no válido');
}
