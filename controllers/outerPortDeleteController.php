<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $outerPort = new outerPort();
    $id = $_POST['id'];

    /* Verifica si el camión tiene una hora de salida registrada */
    $sql = 'SELECT * FROM app_outer_port WHERE row_id = :id AND departure_date IS NOT NULL';
    $list = $outerPort->getFirstMember($sql, ['id' => $id]);

    if ($list > 0) {
        echo 'NOOK2';
    } else {
        $outerPort = new outerPort();
        $outerPort->id = $_POST['id'];

        if ($outerPort->delete()) {
            echo 'OK';
        } else {
            echo 'NOOK';
        }
    }
}
