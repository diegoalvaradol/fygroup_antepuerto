<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$shipLine = new shipLine();
$rut = $_POST['rut'];

$sql = 'SELECT 1 FROM app_ship_lines WHERE rut = :rut LIMIT 1';
$list = $shipLine->getFirstMember($sql, ['rut' => $rut]);

if ($list > 0) {
    echo 'NOOK';
}
