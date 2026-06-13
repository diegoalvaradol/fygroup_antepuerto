<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

$port = new port();
$shipLine = new shipLine();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['api'] ?? '') === 'API_MAERSK') {
        $eta = new DateTime($_POST['eta']) ;
        $etd = new DateTime($_POST['etd']) ;

        $line = $shipLine->getIdByName($_POST['line']);
        $pol = $port->getIdByName($_POST['pol']);
        $pod = $port->getIdByName($_POST['pod']);

        if (empty($line)) {
            exit('La naviera "' . $_POST['line'] . '" no existe en el sistema.');
        }

        if (empty($pol)) {
            exit('El puerto POL "' . $_POST['pol'] . '" no existe en el sistema.');
        }

        if (empty($pod)) {
            exit('El puerto POD "' . $_POST['pod'] . '" no existe en el sistema.');
        }
    } else {
        $rawEta = str_replace('T', ' ', $_POST['eta'] ?? '');
        $eta = DateTime::createFromFormat('Y-m-d H:i:s', $rawEta);

        $rawEtd = str_replace('T', ' ', $_POST['etd'] ?? '');
        $etd = DateTime::createFromFormat('Y-m-d H:i:s', $rawEtd);

        $line = $_POST['line'];
        $pol = $_POST['pol'];
        $pod = $_POST['pol'];
    }

    $ship = new ship();
    $ship->vessel = strtoupper(trim($_POST['vessel'] ?? ''));
    $ship->voyage = strtoupper(trim($_POST['voyage'] ?? ''));
    $ship->line = $line;
    $ship->pol = $pol;
    $ship->pod = $pod;
    $ship->eta = $eta ? $eta->format('Y-m-d H:i:s') : null;
    $ship->etd = $etd ? $etd->format('Y-m-d H:i:s') : null;
    $ship->created = date('Y-m-d H:i:s');
    $ship->lastupdate = date('Y-m-d H:i:s');

    if ($ship->save()) {
        echo 'OK';
    } else {
        echo 'NOOK';
    }
}
