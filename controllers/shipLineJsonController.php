<?php
require_once __DIR__ . '/../config/database.php';

$db         = (new Database())->getConnection();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';

$search = "%{$searchForm}%";

$query = "SELECT * FROM app_ship_lines WHERE name LIKE :search LIMIT 10";
$stmt  = $db->prepare($query);
$stmt->bindParam(":search", $search, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = []; // Asegura que $data esté definido antes de usarlo

foreach ($result as $info) {
  $data[] = [
    "id"   => $info['line_id'],
    "text" => $info['name']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
