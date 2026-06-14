<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cfg = new cfg();
    $cfg->id = 1;
    $cfg->goals = $_POST['goals'];
    $cfg->lastupdate = date('Y-m-d H:i:s');

    if ($cfg->updateGoals()) {
        echo 'OK';
    } else {
        echo 'NOOK';
    }
}
