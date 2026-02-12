<?php
require_once __DIR__ . '/../config/includes.php';

$thermo = new outerPort();

$nave     = $_POST['nave'] ?? '';
$patente  = $_POST['patente'] ?? '';
$guia     = $_POST['guia'] ?? '';
$division = $_POST['division'] ?? '';
$cliente  = $_POST['cliente'] ?? '';

$thermo->downloadTableThermoExcel($nave, $patente, $guia, $division, $cliente);
