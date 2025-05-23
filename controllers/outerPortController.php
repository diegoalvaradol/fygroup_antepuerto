<?php
require_once __DIR__ . '/../models/class.outerPort.php';
require_once __DIR__ . '/../config/database.php';
date_default_timezone_set('America/Santiago');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $database = new Database();
  $db       = $database->getConnection();

  $rawDateInf = str_replace('T', ' ', $_POST['datein']);
  $dateIn     = DateTime::createFromFormat('Y-m-d H:i', $rawDateInf);
  $created    = DateTime::createFromFormat('Y-m-d H:i', $rawDateInf);

  $port                  = new outerPort($db);
  $port->vessel          = $_POST["vessel"];
  $port->carplate        = strtoupper($_POST["carplate"]);
  $port->guide           = $_POST["guidenumber"];
  $port->container       = isset($_POST["container"]) ? $_POST["container"] : 'N/A';
  $port->seal            = isset($_POST["sealnumber"]) ? strtoupper($_POST["sealnumber"]) : 'N/A';
  $port->exporter        = strtoupper($_POST["exporter"]);
  $port->agency          = isset($_POST["agency"]) ? strtoupper($_POST["agency"]) : 'N/A';
  $port->pallets         = $_POST["palletsquantity"];
  $port->cellphonedriver = isset($_POST["cellphonedriver"]) ? $_POST["cellphonedriver"] : '000000000';
  $port->arrivaldate     = $dateIn ? $dateIn->format('Y-m-d H:i:s') : null;
  $port->departuredate   = null;
  $port->comodity        = strtoupper($_POST["comodity"]);
  $port->booking         = strtoupper($_POST["booking"]);
  $port->stay            = strtoupper($_POST["stay"]);
  $port->observations    = strtoupper($_POST["observations"]);
  $port->origin          = $_POST["origin"]; /* [1 => Contenedores, 2 => Termos] */
  $port->created         = $created;
  $port->createdby       = $_POST["createdby"];

  if ($port->save()) {
    echo $_POST["origin"] == 1 ? "OKC" : "OKT";
  } else {
    echo $_POST["origin"] == 1 ? "NOOKC" : "NOOKT";
  }
}
