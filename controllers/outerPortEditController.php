<?php
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$id        = $_POST['id'];

$sql  = "SELECT * FROM app_outer_port WHERE row_id = :id LIMIT 1";
$list = $outerPort->getFirstMember($sql, ['id' => $id]);

echo json_encode($list);
