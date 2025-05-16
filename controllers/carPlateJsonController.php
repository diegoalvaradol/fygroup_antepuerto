<?php
require_once __DIR__ . '/../config/database.php';

$db         = (new Database())->getConnection();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';

$search = "%{$searchForm}%";

$query = "SELECT * FROM app_outer_port WHERE car_plate LIKE :search GROUP BY car_plate LIMIT 10";
$stmt  = $db->prepare($query);
$stmt->bindParam(":search", $search, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];

foreach ($result as $info) {
  $data[] = [
    "id"   => $info['car_plate'],
    "text" => $info['car_plate']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
