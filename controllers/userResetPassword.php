<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['userRun']) || empty($_POST['newPassword'])) {
        echo 'EMPTY_PASSWORD';
        exit;
    }

    $user = new user();
    $user->run = $_POST['userRun'];
    $user->password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
    $user->lastupdate = date('Y-m-d H:i:s');

    if ($user->update()) {
        echo 'OK';
    } else {
        echo 'NOOK';
    }
}
