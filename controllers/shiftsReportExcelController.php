<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$port = new outerPort();

$date = $_GET['date'] ?? null;
$shifts = $_GET['shifts'] ?? null;

if (!$date || !$shifts) {
    exit('Datos inválidos');
}

$dateStart = $date;
$dateEnd = $date;

$port->shiftsReportExcel($shifts, $dateStart, $dateEnd);
