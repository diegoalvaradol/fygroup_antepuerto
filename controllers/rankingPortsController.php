<?php
require_once '../config/includes.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $ship = new ship();
    $data = $ship->rankingPortsByVessel();

    echo json_encode($data,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener el ranking de puertos.'
    ], JSON_UNESCAPED_UNICODE);
}
