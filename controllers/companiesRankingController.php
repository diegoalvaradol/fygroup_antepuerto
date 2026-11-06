<?php

require_once __DIR__ . '/../controllers/dashboardResponse.php';

dashboardResponse(function ($db) {
    $sql = "
        SELECT
            COALESCE(
                NULLIF(exporter, ''),
                NULLIF(agency, ''),
                'Sin empresa'
            ) AS empresa,
            COUNT(*) AS total
        FROM app_outer_port
        GROUP BY empresa
        ORDER BY total DESC
        LIMIT 10
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
});
