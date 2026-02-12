<?php
require_once __DIR__ . '/../config/includes.php';

$shipLine = new shipLine();
$id       = $_POST['id'];

$sql  = "SELECT * FROM app_ship_lines WHERE line_id = :id LIMIT 1";
$list = $shipLine->getFirstMember($sql, ['id' => $id]);

echo json_encode($list);
