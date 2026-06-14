<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $outerPort = new outerPort();
    $id = $_POST['id'];

    /* Verifica si la motonave se encuentra asociado a un ingreso de contenedor o termo */
    $sql = 'SELECT * FROM app_outer_port WHERE vessel_id = :id';
    $list = $outerPort->getFirstMember($sql, ['id' => $id]);

    if ($list > 0) {
        echo 'NOOK2';
    } else {
        $ship = new ship();
        $ship->id = $_POST['id'];

        if ($ship->delete()) {
            echo 'OK';
        } else {
            echo 'NOOK';
        }
    }
}
