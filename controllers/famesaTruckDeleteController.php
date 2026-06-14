<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $famesa = new famesa();
    $id = $_POST['id'] ?? null;

    if (!$id) {
        echo 'NOID';
        exit;
    }

    $sql = 'SELECT * FROM app_famesa  WHERE row_id = :id AND (departure_date_port IS NOT NULL  OR arrival_date_deposit IS NOT NULL OR departure_date_deposit IS NOT NULL)';
    $list = $famesa->getFirstMember($sql, ['id' => $id]);

    if (!empty($list)) {
        echo 'NOOK2'; // tiene fechas → no borrar
    } else {
        $famesa->id = $id;

        if ($famesa->delete()) {
            echo 'OK';
        } else {
            echo 'NOOK';
        }
    }
}
