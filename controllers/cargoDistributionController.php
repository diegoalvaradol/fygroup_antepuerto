<?php

require_once __DIR__ . '/../controllers/dashboardResponse.php';

dashboardResponse(function ($db) {
    $sql = "
        SELECT
            CASE
                WHEN origin = 1 THEN 'Contenedores'
                WHEN origin = 2 THEN 'Termos / Pallets'
                ELSE 'Otros'
            END AS tipo,
            COUNT(*) AS total
        FROM app_outer_port
        GROUP BY origin
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
});
