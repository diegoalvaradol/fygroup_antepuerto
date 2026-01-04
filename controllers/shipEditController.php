<?php
require_once __DIR__ . '/../config/includes.php';

$db = (new Database())->getConnection();
$id = $_POST['id'];

$query = "SELECT *
FROM app_ships
JOIN app_ports p ON app_ships.port_discharge = p.port_id
JOIN app_ship_lines sl ON app_ships.ship_line = sl.line_id
WHERE ship_id = :id
LIMIT 1";

$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($data);
