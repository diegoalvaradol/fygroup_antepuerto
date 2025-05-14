<?php
require_once __DIR__ . '/../config/database.php';

$db         = (new Database())->getConnection();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';

$search = "%{$searchForm}%";

$query = "SELECT * FROM app_ships WHERE vessel_name LIKE :search LIMIT 10";
$stmt  = $db->prepare($query);
$stmt->bindParam(":search", $search, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];

foreach ($result as $info) {
  $data[] = [
    "id"   => $info['ship_id'],
    "text" => $info['vessel_name'] . ' (Viaje: ' . $info['voyage'] . ')'
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
