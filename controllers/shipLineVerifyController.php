<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $shipLine = new shipLine();
  $name     = $_POST['name'];

  $query = "SELECT * FROM app_ship_lines WHERE name = :name";
  $stmt  = $shipLine->getDb()->prepare($query);
  $stmt->bindParam(":name", $name, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK";
  }
}
