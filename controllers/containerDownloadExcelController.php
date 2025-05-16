<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/class.outerPort.php';

$db           = (new Database())->getConnection();
$contenedores = new outerPort($db);

$nave    = $_POST['nave'] ?? '';
$patente = $_POST['patente'] ?? '';
$guia    = $_POST['guia'] ?? '';

$contenedores->downloadTableContainerExcel($nave, $patente, $guia);
