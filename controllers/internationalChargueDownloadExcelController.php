<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$international = new internationalChargue();

$nave = $_POST['nave'] ?? '';
$patente = $_POST['patente'] ?? '';
$guia = $_POST['guia'] ?? '';

$international->downloadTableInternationalChargueExcel($nave, $patente, $guia);
