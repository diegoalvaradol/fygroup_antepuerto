<?php
require_once __DIR__ . '/../config/includes.php';

$shipLine = new shipLine();
$id       = $_POST['id'];

$query = "SELECT * FROM app_ship_lines WHERE line_id = :id LIMIT 1";
$stmt  = $shipLine->getDb()->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);
