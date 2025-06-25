<?php
require_once __DIR__ . '/../config/includes.php';

$db       = (new Database())->getConnection();
$exporter = $_POST['exporter'];

$query = "SELECT * FROM app_outer_port WHERE exporter = :exporter AND origin = 1 LIMIT 1";
$stmt  = $db->prepare($query);
$stmt->bindParam(":exporter", $exporter, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result !== []) {
  echo $result['agency'];
}
