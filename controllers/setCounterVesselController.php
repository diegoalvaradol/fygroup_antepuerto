<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/includes.php';

if (isset($_POST['id'], $_POST['origin'])) {
    $outerPort = new outerPort();
    $id = $_POST['id'];
    $origin = $_POST['origin'];

    $sql = 'SELECT * FROM app_outer_port WHERE vessel_id = :id AND origin = :origin ORDER BY row_id DESC LIMIT 1';
    $list = $outerPort->getFirstMember($sql, ['id' => $id, 'origin' => $origin]);
    $count = $list['counter_vessel'] ?? 0;

    $counter = htmlspecialchars($count + 1);

    echo $counter;
}
