<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $outerPort = new outerPort();
    $clausule = null;

    $id = $_POST['id'];
    $name = $_POST['name'];
    $exporter = $_POST['exporter'];
    $agency = $_POST['agency'];

    if ($exporter && !$agency) {
        $clausule = " exporter = '$name'";
    }

    if ($agency && !$exporter) {
        $clausule = " agency = '$name'";
    }

    /* Verifica si la linea naviera se encuentra asociado a una motonave registrada */
    $sql = "SELECT * FROM app_outer_port WHERE $clausule";
    $list = $outerPort->getFirstMember($sql);

    if ($list > 0) {
        echo 'NOOK2';
    } else {
        $company = new company();
        $company->id = $id;

        if ($company->delete()) {
            echo 'OK';
        } else {
            echo 'NOOK';
        }
    }
}
