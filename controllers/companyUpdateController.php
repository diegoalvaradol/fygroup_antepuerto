<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $isExporter = $_POST['isExporter'] ?? '0';
  $isAgency   = $_POST['isAgency'] ?? '0';

  $company             = new company();
  $company->id         = $_POST["companyId"];
  $company->name       = strtoupper($_POST["companyName"]);
  $company->exporter   = $isExporter;
  $company->agency     = $isAgency;
  $company->lastupdate = date('Y-m-d H:i:s');

  if ($company->update()) {
    echo "OK";
  } else {
    echo "NOOK";
  }
}
