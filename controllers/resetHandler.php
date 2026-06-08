<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $pass = $_POST['password'];
    $confirm = $_POST['password2'];

    if ($pass !== $confirm) {
        die('Las contraseñas no coinciden.');
    }

    $user = new User();

    if ($user->resetPasswordToken($token, $pass)) {
        echo 'OK';
    } else {
        echo 'NOOK';
    }
}
