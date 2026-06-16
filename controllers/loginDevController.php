<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$run = trim($_POST['run'] ?? '');
$password = $_POST['password'] ?? '';

if ($run === '' || $password === '') {
    echo 'NOOK';
    exit;
}

$isdev = 1;

$user = new user();
$user->run = $run;
$user->password = $password;
$user->isdev = $isdev;
$user->lastsession = date('Y-m-d H:i:s');

$sql = 'SELECT run, is_dev, is_active FROM app_users WHERE run = :run LIMIT 1';
$list = $user->getFirstMember($sql, ['run' => $run]);

if (!$list) {
    echo 'NOOK';
    exit;
}

if ((int) $list['is_active'] === 0) {
    echo 'NOOK3';
    exit;
}

if ((int) $list['is_dev'] !== $isdev) {
    echo 'NOOK2';
    exit;
}

$userData = $user->loginDev();

if ($userData) {
    session_regenerate_id(true);
    $_SESSION['user'] = $userData;

    echo 'OK';
    exit;
}

echo 'NOOK';
