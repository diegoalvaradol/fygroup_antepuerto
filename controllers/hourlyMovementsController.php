<?php
require_once __DIR__ . '/../controllers/dashboardResponse.php';

dashboardResponse(function ($db) {
    $sql = "
        SELECT
            HOUR(arrival_date) AS hora,
            COUNT(*) AS total
        FROM app_outer_port
        WHERE DATE(arrival_date) = CURDATE()
        GROUP BY HOUR(arrival_date)
        ORDER BY hora ASC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
});
