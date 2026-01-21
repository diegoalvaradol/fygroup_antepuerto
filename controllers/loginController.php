<?php
session_start();
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  exit;
}

$db = (new Database())->getConnection();

$run      = $_POST['run'];
$password = $_POST['password'];
$division = $_POST['division'];

$user              = new User($db);
$user->run         = $run;
$user->password    = $password;
$user->division    = $division;
$user->lastsession = date('Y-m-d H:i:s');

$stmt = $db->prepare("SELECT run, division, is_active FROM app_users WHERE run = :run LIMIT 1");
$stmt->bindParam(':run', $run);
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
