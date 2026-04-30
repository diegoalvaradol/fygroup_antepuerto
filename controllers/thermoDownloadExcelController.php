<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$thermo = new outerPort();

$nave = $_POST['nave'] ?? '';
$patente = $_POST['patente'] ?? '';
$guia = $_POST['guia'] ?? '';
$division = $_POST['division'] ?? '';
$cliente = $_POST['cliente'] ?? '';

$thermo->tableThermoExcel($nave, $patente, $guia, $division, $cliente);
