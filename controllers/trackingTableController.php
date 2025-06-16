<?php
require_once __DIR__ . '/../config/includes.php';

$db       = (new Database())->getConnection();
$tracking = new tracking($db);

$id = $_POST['container'] ?? '';

$tracking->getTableTracking($id);