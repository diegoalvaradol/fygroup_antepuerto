<?php

declare(strict_types=1);
session_start();
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$run = $_POST['run'];
$password = $_POST['password'];
$division = $_POST['division'];

$user = new user();
$user->run = $run;
$user->password = $password;
$user->division = $division;
$user->lastsession = date('Y-m-d H:i:s');

$sql = 'SELECT run, is_dev, division, is_active FROM app_users WHERE run = :run LIMIT 1';
$list = $user->getFirstMember($sql, ['run' => $run]);

if (!$list) {
    echo 'NOOK';

    exit;
}

if ((int) $list['is_dev'] === 1) {
    echo 'NOOK4';

    exit;
}

if ((int) $list['is_active'] === 0) {
    echo 'NOOK3';

    exit;
}

if ($list['division'] !== $division) {
    echo 'NOOK2';

    exit;
}

if ($userData = $user->login()) {
    $_SESSION['user'] = $userData;
    echo 'OK';

    exit;
}

echo 'NOOK';
