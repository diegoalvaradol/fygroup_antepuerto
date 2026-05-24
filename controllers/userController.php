<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new user();
    $user->run = $_POST['run'];
    $user->name = $_POST['name'];
    $user->lastname = $_POST['lastname'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $user->division = $_POST['division'];
    $user->isadmin = $_POST['is_admin'];
    $user->isadminedit = $_POST['is_admin_edit'];
    $user->isactive = 1;
    $user->lastsession = date('Y-m-d H:i:s');
    $user->created = date('Y-m-d H:i:s');
    $user->lastupdate = date('Y-m-d H:i:s');

    if ($user->save()) {
        echo 'OK';
    } else {
        echo 'NOOK';
    }
}
