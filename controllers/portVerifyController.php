<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/class.port.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db = (new Database())->getConnection();

  $city = $_POST['city'];

  $query = "SELECT * FROM app_ports WHERE city = :city";
  $stmt  = $db->prepare($query);
  $stmt->bindParam(":city", $city);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK";
  }
}
