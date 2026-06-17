<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

$port = new port();
$shipLine = new shipLine();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* Validación de naves/ */
    $sql = 'SELECT vessel_name, voyage FROM app_ships WHERE vessel_name = :vessel AND voyage = :voyage';
    $list = (new ship())->getFirstMember($sql, ['vessel' => $_POST['vessel'], 'voyage' => $_POST['voyage']]);
    if (!empty($list)) {
        exit('Motonave: ' . htmlspecialchars($_POST['vessel']) . ' | Viaje: ' . htmlspecialchars($_POST['voyage']) . ' ya se encuentra creado en el sistema.');
    }

    if (($_POST['api'] ?? '') === 'API_MAERSK') {
        $eta = new DateTime($_POST['eta']) ;
        $etd = new DateTime($_POST['etd']) ;

        $line = $shipLine->getIdByName($_POST['line']);
        $pol = $port->getIdByName($_POST['pol']);
        $pod = $port->getIdByName($_POST['pod']);

        if (empty($line)) {
            exit('La naviera ' . htmlspecialchars($_POST['line']) . ' no existe en el sistema.');
        }

        if (empty($pol)) {
            exit('El puerto POL ' . htmlspecialchars($_POST['pol']) . ' no existe en el sistema.');
        }

        if (empty($pod)) {
            exit('El puerto POD ' . htmlspecialchars($_POST['pod']) . ' no existe en el sistema.');
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
