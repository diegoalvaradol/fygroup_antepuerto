<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$user = new user();
$run = $_POST['run'];

$sql = 'SELECT run FROM app_users WHERE run = :run LIMIT 1';
$list = $user->getFirstMember($sql, ['run' => $run]);

if ($list > 0) {
    echo 'NOOK';
}
