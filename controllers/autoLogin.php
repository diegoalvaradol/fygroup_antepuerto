<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

if (!isset($_GET['id'])) {
    die('Usuario no válido');
}

$user = new user();

$sql = 'SELECT * FROM app_users WHERE user_id = :id AND is_active = 1';
$list = $user->getFirstMember($sql, ['id' => $_GET['id']]);

if ($list == 0) {
    die('Usuario no encontrado');
}

$_SESSION['user'] = [
    'id' => $list['user_id'],
    'run' => $list['run'],
    'name' => $list['name'],
    'lastname' => $list['last_name'],
    'email' => $list['email'],
    'division' => $list['division'],
    'is_admin' => $list['is_admin'],
    'is_active' => $list['is_active'],
];

header('Location: ../myPortal/loginDataUser.php');
exit;
