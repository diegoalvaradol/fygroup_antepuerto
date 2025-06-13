<?php
require_once __DIR__ . '/../config/includes.php';

if (isset($_POST['id'], $_POST['origin'])) {
  $db = (new Database())->getConnection();

  $id     = $_POST['id'];
  $origin = $_POST['origin'];

  $query = "SELECT * FROM app_outer_port WHERE vessel_id = :id AND origin = :origin ORDER BY row_id DESC LIMIT 1";
  $stmt  = $db->prepare($query);
  $stmt->bindParam(":id", $id, PDO::PARAM_INT);
  $stmt->bindParam(":origin", $origin, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $count  = $result['counter_vessel'] ?? 0;

  $counter = htmlspecialchars($count + 1);

  echo $counter;
}
