<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/class.user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db = (new Database())->getConnection();

  $email = $_POST['email'];

  $query = "SELECT * FROM app_users WHERE email = :email";
  $stmt  = $db->prepare($query);
  $stmt->bindParam(":email", $email);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result > 0) {
    echo "NOOK";
  }
}
