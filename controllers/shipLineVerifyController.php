<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  exit;
}

$shipLine = new shipLine();
$nameForm = trim($_POST['name']);
$name     = "%{$nameForm}%";

$sql  = "SELECT 1 FROM app_ship_lines WHERE name LIKE :name LIMIT 1";
$list = $shipLine->getFirstMember($sql, ['name' => $name]);

if ($list > 0) {
  echo "NOOK";
}
