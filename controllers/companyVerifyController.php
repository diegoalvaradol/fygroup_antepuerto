<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$company = new company();
$nameForm = trim($_POST['name']);
$name = "%{$nameForm}%";

$sql = 'SELECT 1 FROM app_company WHERE name LIKE :name LIMIT 1';
$list = $company->getFirstMember($sql, ['name' => $name]);

if ($list > 0) {
    echo 'NOOK';
}
