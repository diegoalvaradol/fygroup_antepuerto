<?php
require_once __DIR__ . '/../config/includes.php';

$db         = (new Database())->getConnection();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';

$search = "%{$searchForm}%";

$query = "SELECT * FROM app_outer_port WHERE agency LIKE :search AND agency != 'N/A' GROUP BY agency LIMIT 10";
$stmt  = $db->prepare($query);
$stmt->bindParam(":search", $search, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];

foreach ($result as $info) {
  $data[] = [
    "id"   => $info['agency'],
    "text" => $info['agency']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
