<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/class.shipLine.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db = (new Database())->getConnection();

  $name = $_POST['name'];

  $query = "SELECT * FROM app_ship_lines WHERE name = :name";
  $stmt  = $db->prepare($query);
  $stmt->bindParam(":name", $name);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK";
  }
}
