<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$outerPort = new outerPort();

$fromVessel = (int) ($_POST['fromvessel'] ?? 0);
$toVessel = (int) ($_POST['tovessel'] ?? 0);
$rowId = $_POST['rowId'] ?? [];

if (!$fromVessel || !$toVessel || empty($rowId)) {
    echo 'ERROR';

    exit;
}

/* Origen nave */
$sql = 'SELECT origin FROM app_outer_port WHERE vessel_id = :vessel LIMIT 1';
$originFrom = $outerPort->getFirstMember($sql, ['vessel' => $fromVessel]);
$originTo = $outerPort->getFirstMember($sql, ['vessel' => $toVessel]);

if (!$originFrom || !$originTo || $originFrom !== $originTo) {
    echo 'ERROR';

    exit;
}

if ($outerPort->vesselTransfer($fromVessel, $toVessel, $rowId)) {
    echo 'OK';
} else {
    echo 'NOOK';
}
