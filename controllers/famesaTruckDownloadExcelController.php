<?php
require_once __DIR__ . '/../config/includes.php';

$famesa = new famesa();

$nave    = $_POST['nave'] ?? '';
$patente = $_POST['patente'] ?? '';
$guia    = $_POST['guia'] ?? '';

$famesa->downloadTableTrucksFamesaExcel($nave, $patente, $guia);
