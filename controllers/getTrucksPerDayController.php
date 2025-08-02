<?php
require_once __DIR__ . '/../config/includes.php';
$db = (new Database())->getConnection();

$input  = json_decode(file_get_contents("php://input"), true);
$inicio = $input['fechaInicio'];
$fin    = $input['fechaFin'];

$port = new outerPort($db); // usa tu clase real si tiene otro nombre
echo $port->trucksInOutPerDay($inicio, $fin);
