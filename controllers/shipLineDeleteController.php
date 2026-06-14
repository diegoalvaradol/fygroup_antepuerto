<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ship = new ship();
    $id = $_POST['id'];

    /* Verifica si la linea naviera se encuentra asociado a una motonave registrada */
    $sql = 'SELECT * FROM app_ships WHERE ship_line = :id';
    $list = $ship->getFirstMember($sql, ['id' => $id]);

    if ($list > 0) {
        echo 'NOOK2';
    } else {
        $line = new shipLine();
        $line->id = $id;

        if ($line->delete()) {
            echo 'OK';
        } else {
            echo 'NOOK';
        }
    }
}
