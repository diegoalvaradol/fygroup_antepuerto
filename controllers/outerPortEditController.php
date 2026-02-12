<?php
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$id        = $_POST['id'];

$query = "SELECT * FROM app_outer_port WHERE row_id = :id LIMIT 1";
$stmt  = $outerPort->getDb()->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);
