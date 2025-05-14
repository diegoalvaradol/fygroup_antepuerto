<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/class.outerPort.php';

$db     = (new Database())->getConnection();
$thermo = new outerPort($db);

$nave       = $_POST['nave'] ?? '';
$condicion  = $_POST['condicion'] ?? '';
$exportador = $_POST['exportador'] ?? '';

$thermo->downloadTableThermoExcel($nave, $condicion, $exportador);
