<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = new user();

  $run = $_POST['run'];

  $query = "SELECT * FROM app_users WHERE run = :run";
  $stmt  = $user->getDb()->prepare($query);
  $stmt->bindParam(":run", $run, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK";
  }
}
