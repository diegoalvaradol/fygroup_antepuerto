<?php
require_once __DIR__ . '/../config/includes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $port     = new port();
  $cityForm = trim($_POST['city']);
  $city     = "%{$cityForm}%";

  $sql  = "SELECT 1 FROM app_ports WHERE city LIKE :city LIMIT 1";
  $list = $port->getFirstMember($sql, ['city' => $city]);

  if ($list > 0) {
    echo "NOOK";
  }
}
