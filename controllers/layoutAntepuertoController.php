<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['action']) || $_GET['action'] !== 'data') {
    echo json_encode(['error' => 'invalid action']);
    exit;
}

$layout = new outerPort();
$data = $layout->layoutAntepuerto();

echo json_encode($data);
exit;
