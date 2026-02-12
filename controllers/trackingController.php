<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $tracking             = new tracking();
  $tracking->chargueid  = $_POST["chargueId"];
  $tracking->status     = $_POST["itemId"];
  $tracking->statusdate = date('Y-m-d H:i:s');
  $tracking->created    = date('Y-m-d H:i:s');

  if ($tracking->save()) {
    $track            = new tracking();
    $track->chargueid = $_POST["chargueId"];
    $track->delete();

    echo "OK";
  } else {
    echo "NOOK";
  }
}
