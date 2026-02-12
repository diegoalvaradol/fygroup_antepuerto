<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  exit;
}

$run      = $_POST['run'];
$password = $_POST['password'];
$division = $_POST['division'];

$user              = new user();
$user->run         = $run;
$user->password    = $password;
$user->division    = $division;
$user->lastsession = date('Y-m-d H:i:s');

$query = "SELECT run, division, is_active FROM app_users WHERE run = :run LIMIT 1";
$stmt  = $user->getDb()->prepare($query);
$stmt->bindParam(':run', $run, PDO::PARAM_STR);
$stmt->execute();

$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
  echo 'NOOK';
  exit;
}

if ((int) $data['is_active'] === 0) {
  echo 'NOOK3';
  exit;
}

if ($data['division'] !== $division) {
  echo 'NOOK2';
  exit;
}

if ($userData = $user->login()) {
  $_SESSION['user'] = $userData;
  echo 'OK';
  exit;
}

echo 'NOOK';
