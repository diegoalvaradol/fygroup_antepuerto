<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $port = new port();
  $city = $_POST['city'];

  $query = "SELECT * FROM app_ports WHERE city = :city";
  $stmt  = $port->getDb()->prepare($query);
  $stmt->bindParam(":city", $city, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK";
  }
}
