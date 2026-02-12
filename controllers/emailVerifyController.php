<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user  = new user();
  $email = $_POST['email'];

  $query = "SELECT * FROM app_users WHERE email = :email";
  $stmt  = $user->getDb()->prepare($query);
  $stmt->bindParam(":email", $email, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK";
  }
}
