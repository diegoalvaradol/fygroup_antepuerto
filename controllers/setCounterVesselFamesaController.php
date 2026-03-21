<?php
require_once __DIR__ . '/../config/includes.php';

if (isset($_POST['id'])) {
  $famesa = new famesa();
  $id     = $_POST['id'];

  $sql   = "SELECT * FROM app_famesa WHERE vessel_id = :id  ORDER BY row_id DESC LIMIT 1";
  $list  = $famesa->getFirstMember($sql, ['id' => $id]);
  $count = $list['counter_vessel'] ?? 0;

  $counter = htmlspecialchars($count + 1);

  echo $counter;
}
