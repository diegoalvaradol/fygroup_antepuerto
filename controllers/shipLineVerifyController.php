<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $shipLine = new shipLine();
  $name     = $_POST['name'];

  $sql  = "SELECT * FROM app_ship_lines WHERE name = :name";
  $List = $shipLine->getFirstMember($sql, ['name' => $name]);

  if ($list > 0) {
    echo "NOOK";
  }
}
