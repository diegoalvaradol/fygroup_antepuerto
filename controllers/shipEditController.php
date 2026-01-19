<?php
require_once __DIR__ . '/../config/includes.php';

$db = (new Database())->getConnection();
$id = $_POST['id'];

$query = "SELECT
	s.ship_id AS id,
	s.vessel_name as vesselName,
	s.voyage AS voyage,
	s.eta AS eta,
	s.etd AS etd,
	s.ship_line AS shipLine,
	sl.name AS nameLine,
	s.pol AS pol,
	pol.city AS polCity,
	pol.country AS polCountry,
	s.pod AS pod,
	pod.city AS podCity,
	pod.country AS podCountry
FROM app_ships s
JOIN app_ports pol ON s.pol = pol.port_id
JOIN app_ports pod ON s.pod = pod.port_id
JOIN app_ship_lines sl ON s.ship_line = sl.line_id
WHERE ship_id = :id
LIMIT 1";

$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($data);
