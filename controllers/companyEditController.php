<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

$company = new company();
$id = $_POST['id'];

$sql = 'SELECT * FROM app_company WHERE id = :id LIMIT 1';
$list = $company->getFirstMember($sql, ['id' => $id]);

echo json_encode($list);
