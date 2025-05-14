<?php
require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();
$id = $_POST['id'];

$query = "SELECT * FROM app_ships JOIN app_ports AS p ON app_ships.port_discharge = p.port_id JOIN app_ship_lines AS sl ON app_ships.ship_line = sl.line_id WHERE ship_id = $id LIMIT 1";
$stmt  = $db->prepare($query);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($data);
