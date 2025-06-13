<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $cfg             = new cfg($db);
  $cfg->id         = 1;
  $cfg->goals      = $_POST["goals"];
  $cfg->lastupdate = date('Y-m-d H:i:s');

  if ($cfg->updateGoals()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
