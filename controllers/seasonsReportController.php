<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$periods = get::arraySeasons();
$selectedIndex = $_POST['seasons'] ?? '';

/* Muestra el total de todas las temporadas */
if ($selectedIndex === 'all') {
    echo $outerPort->seasonsReportAll();
    exit;
}

if (!ctype_digit((string) $selectedIndex) || !isset($periods[(int) $selectedIndex])) {
    http_response_code(422);
    exit('Temporada inválida.');
}

$selectedPeriod = $periods[(int) $selectedIndex];

$season = $selectedPeriod['season']; // summer | citrus
$start = $selectedPeriod['start'];  // 2025-05-01
$end = $selectedPeriod['end'];    // 2025-08-15
$label = $selectedPeriod['label'];  // Cítricos 2025

echo $outerPort->seasonsReport($start, $end, $season, $label);
