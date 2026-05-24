<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $exporter = $_POST['exporter'] ?? '0';
  $agency   = $_POST['agency'] ?? '0';

  $company             = new company();
  $company->name       = strtoupper($_POST["company"]);
  $company->exporter   = $exporter;
  $company->agency     = $agency;
  $company->created    = date('Y-m-d H:i:s');
  $company->lastupdate = date('Y-m-d H:i:s');

  if ($company->save()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
