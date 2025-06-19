<?php
require_once __DIR__ . '/../config/includes.php';

$db            = (new Database())->getConnection();
$international = new internationalChargue($db);

$nave    = $_POST['nave'] ?? '';
$patente = $_POST['patente'] ?? '';
$guia    = $_POST['guia'] ?? '';

$international->downloadTableInternationalChargueExcel($nave, $patente, $guia);