<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

header('Content-Type: text/plain; charset=UTF-8');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$origin = filter_input(INPUT_POST, 'origin', FILTER_UNSAFE_RAW);

if ($id === false || $id === null || empty($origin)) {
    http_response_code(400);
    exit('Parámetros inválidos');
}

try {
    $outerPort = new outerPort();

    $sql = '
        SELECT counter_vessel
        FROM app_outer_port
        WHERE vessel_id = :id
          AND origin = :origin
        ORDER BY row_id DESC
        LIMIT 1
    ';

    $row = $outerPort->getFirstMember($sql, ['id' => $id,'origin' => trim($origin),]);
    $counter = ((int) ($row['counter_vessel'] ?? 0)) + 1;

    echo $counter;
} catch (Throwable $e) {
    http_response_code(500);
    exit('Error interno');
}
