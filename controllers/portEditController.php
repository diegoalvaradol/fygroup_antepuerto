<?php
require_once __DIR__ . '/../config/includes.php';

$port = new port();
$id   = $_POST['id'];

$query = "SELECT * FROM app_ports WHERE port_id = :id LIMIT 1";
$stmt  = $port->getDb()->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($data);
