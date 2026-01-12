<?php
require_once __DIR__ . '/../config/includes.php';

$db     = (new Database())->getConnection();
$thermo = new outerPort($db);

$nave     = $_POST['nave'] ?? '';
$patente  = $_POST['patente'] ?? '';
$guia     = $_POST['guia'] ?? '';
$division = $_POST['division'] ?? '';
$cliente  = $_POST['cliente'] ?? '';

$thermo->downloadTableThermoExcel($nave, $patente, $guia, $division, $cliente);
