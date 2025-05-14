<?php
require_once __DIR__ . '/../config/database.php';

$db         = (new Database())->getConnection();
$searchForm = isset($_POST['search']) ? $_POST['search'] : '';

$search = "%{$searchForm}%";

$query = "SELECT * FROM app_ports WHERE city LIKE :search GROUP BY city LIMIT 20";
$stmt  = $db->prepare($query);
$stmt->bindParam(":search", $search, PDO::PARAM_STR);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = []; // Asegura que $data esté definido antes de usarlo

foreach ($result as $info) {
  $data[] = [
    "id"   => $info['port_id'],
    "text" => $info['city'] . ' - ' . $info['country']
  ];
}

header('Content-Type: application/json');
echo json_encode($data);
