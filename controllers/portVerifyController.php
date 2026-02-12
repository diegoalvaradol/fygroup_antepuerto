<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $port = new port();
  $city = $_POST['city'];

  $sql  = "SELECT * FROM app_ports WHERE city = :city";
  $list = $port->getFirstMember($sql, ['city' => $city]);

  if ($list > 0) {
    echo "NOOK";
  }
}
