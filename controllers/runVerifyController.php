<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db = (new Database())->getConnection();

  $run = $_POST['run'];

  $query = "SELECT * FROM app_users WHERE run = :run";
  $stmt  = $db->prepare($query);
  $stmt->bindParam(":run", $run);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK";
  }
}
