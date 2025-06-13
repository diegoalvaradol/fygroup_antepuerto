
<?php
require_once __DIR__ . '/../config/includes.php';

$db = (new Database())->getConnection();
$id = $_POST['id'];

$query = "SELECT * FROM app_outer_port WHERE row_id = $id LIMIT 1";
$stmt  = $db->prepare($query);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($data);
