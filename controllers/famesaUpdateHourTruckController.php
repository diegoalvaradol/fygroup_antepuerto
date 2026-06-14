<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$famesa = new famesa();

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['status' => 'error', 'msg' => 'id vacío']);
    exit;
}

$famesa->id = $id;
$fields = ['departure_date_port', 'arrival_date_deposit', 'departure_date_deposit'];

foreach ($fields as $field) {
    if (!empty($_POST[$field])) {
        $ok = $famesa->updateDateTruck($field, $_POST[$field]);

        if ($ok) {
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'fallo update']);
        }

        exit;
    }
}

echo json_encode(['status' => 'error', 'msg' => 'sin campo válido']);
