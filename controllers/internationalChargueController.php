<?php
require_once __DIR__ . '/../config/includes.php';
date_default_timezone_set("America/Santiago");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $db       = (new Database())->getConnection();

  $chargue             			= new internationalChargue($db);
	$chargue->countervessel 	= $_POST["countervessel"];
	$chargue->vessel 					= $_POST["vessel"];
	$chargue->carplate 				= strtoupper($_POST["carplate"]);
	$chargue->guide 					= $_POST["guidenumber"];
	$chargue->container 			= $_POST["container"];
	$chargue->seal 						= strtoupper($_POST["sealnumber"]);
	$chargue->exporter 				= strtoupper($_POST["exporter"]);
	$chargue->pallets 				= $_POST["palletsquantity"];
	$chargue->namedriver 			= strtoupper($_POST["drivername"]);
	$chargue->cellphonedriver = $_POST["cellphonedriver"];
	$chargue->digitedby 			= $_POST["digitedby"];
	$chargue->created 				= date('Y-m-d H:i:s');
	$chargue->lastupdate 			= date('Y-m-d H:i:s');

  if ($chargue->save()) {
		$tracking 						= new tracking($db);
		$tracking->chargueid  = $chargue->id; /* Asigna el id de la carga internacional */
		$tracking->status     = 0; /* Inicializa el estado en 0 */
		$tracking->statusdate = date('Y-m-d H:i:s');
		$tracking->created    = date('Y-m-d H:i:s');
		$tracking->save();

    echo "OK";
  } else {
    echo "NOOK";
  }
}