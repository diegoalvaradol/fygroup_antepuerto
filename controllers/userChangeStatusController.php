<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new user();
    $user->run = $_POST['run'];
    $user->isactive = $_POST['status'];
    $user->lastupdate = date('Y-m-d H:i:s');

    if ($user->changeStatus()) {
        echo 'OK';
    } else {
        echo 'NOOK';
    }
}
