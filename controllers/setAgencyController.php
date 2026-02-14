<?php
require_once __DIR__ . '/../config/includes.php';

$outerPort = new outerPort();
$exporter  = $_POST['exporter'];

$sql  = "SELECT agency FROM app_outer_port WHERE exporter = :exporter AND origin = 1 LIMIT 1";
$list = $outerPort->getFirstMember($sql, ['exporter' => $exporter]);

if ($list > 0) {
  echo $list['agency'];
}
