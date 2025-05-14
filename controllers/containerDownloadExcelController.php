<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/class.outerPort.php';

$db           = (new Database())->getConnection();
$contenedores = new outerPort($db);

$nave       = $_POST['nave'] ?? '';
$condicion  = $_POST['condicion'] ?? '';
$exportador = $_POST['exportador'] ?? '';

$contenedores->downloadTableContainerExcel($nave, $condicion, $exportador);
