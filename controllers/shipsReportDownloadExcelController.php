<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$shipReport = new outerPort();

$nave = $_POST['nave'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$desde = $_POST['desde'] ?? '';
$hasta = $_POST['hasta'] ?? '';

$shipReport->shipReportExcel($nave, $tipo, $desde, $hasta);
