<?php
require_once __DIR__ . '/../config/includes.php';

$port = new port();
$id   = $_POST['id'];

$sql  = "SELECT * FROM app_ports WHERE port_id = :id LIMIT 1";
$list = $port->getFirstMember($sql, ['id' => $id]);

echo json_encode($list);
