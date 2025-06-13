<?php
require

$db       = (new Database())->getConnection();
$tracking = new tracking($db);

$id = $_POST['container'] ?? '';

$tracking->getTableTracking($id);