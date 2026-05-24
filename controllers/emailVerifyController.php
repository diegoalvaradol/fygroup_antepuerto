<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user  = new user();
  $email = $_POST['email'];

  $sql  = "SELECT * FROM app_users WHERE email = :email";
  $list = $user->getFirstMember($sql, ['email' => $email]);

  if ($list > 0) {
    echo "NOOK";
  }
}
