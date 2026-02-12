<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = new user();
  $run  = $_POST['run'];

  $sql  = "SELECT * FROM app_users WHERE run = :run";
  $list = $user->getFirstMember($sql, ['run' => $run]);

  if ($list > 0) {
    echo "NOOK";
  }
}
