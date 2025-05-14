<?php
require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();
$id = $_POST['id'];

$query = "SELECT * FROM app_ports WHERE port_id = $id LIMIT 1";
$stmt  = $db->prepare($query);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($data);
