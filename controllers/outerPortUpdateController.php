<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rawDateOut = str_replace('T', ' ', $_POST['dateout']);
    $dateOut = DateTime::createFromFormat('Y-m-d H:i', $rawDateOut);

    $outerPort = new outerPort();
    $outerPort->id = $_POST['rowId'];
    $outerPort->origin = $_POST['originId'];
    $outerPort->departuredate = $dateOut ? $dateOut->format('Y-m-d H:i:s') : null;

    if ($outerPort->updateDepartureDate()) {
        echo 'OK';
    } else {
        echo 'NOOK';
    }
}
