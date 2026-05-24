<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set('America/Santiago');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $rawDateInf = str_replace('T', ' ', $_POST['arrivaldateport']);
  $dateIn     = DateTime::createFromFormat('Y-m-d H:i', $rawDateInf);
  $id         = isset($_POST["truckId"]) && $_POST["truckId"] != 0 ? $_POST["truckId"] : null;

  $famesa                       = new famesa();
  $famesa->id                   = $id;
  $famesa->countervessel        = (int) $_POST["countervessel"];
  $famesa->vessel               = (int) $_POST["vessel"];
  $famesa->carplatetruck        = strtoupper($_POST["carplatetruck"]);
  $famesa->carplateramp         = strtoupper($_POST["carplateramp"]);
  $famesa->guide                = $_POST["guidenumber"];
  $famesa->maxibags             = (int) $_POST["maxibagsquantity"];
  $famesa->category             = (int) $_POST["category"];
  $famesa->arrivaldateport      = $dateIn ? $dateIn->format('Y-m-d H:i:s') : null;
  $famesa->departuredateport    = ($_POST["departuredateport"] ?? null) ?: null;
  $famesa->arrivaldatedeposit   = ($_POST["arrivaldatedeposit"] ?? null) ?: null;
  $famesa->departuredatedeposit = ($_POST["departuredatedeposit"] ?? null) ?: null;
  $famesa->observations         = strtoupper($_POST["observations"]);
  $famesa->created              = date('Y-m-d H:i:s');
  $famesa->createdby            = $_POST["createdby"];

  if ($_POST["isUpdate"] == 1) {
    if ($famesa->update()) {
      echo "OKU";
    } else {
      echo "NOOKU";
    }
  } else {
    if ($famesa->save()) {
      echo "OK";
    } else {
      echo "NOOK";
    }
  }
}
