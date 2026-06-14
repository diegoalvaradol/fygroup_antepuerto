<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acceso no permitido');
}

$thermo = new outerPort();

/* Sanitizar */
$nave = isset($_POST['nave']) ? trim($_POST['nave']) : '';
$patente = isset($_POST['patente']) ? trim($_POST['patente']) : '';
$guia = isset($_POST['guia']) ? trim($_POST['guia']) : '';
$division = isset($_POST['division']) ? trim($_POST['division']) : '';
$cliente = isset($_POST['cliente']) ? trim($_POST['cliente']) : '';

/* Normalizar */
$nave = $nave === '-' ? '' : $nave;
$patente = $patente === '-' ? '' : $patente;

/* Ejecutar */
$thermo->tableThermoExcel(
    $nave,
    $patente,
    $guia,
    $division,
    $cliente
);
