<?php
require_once __DIR__ . '/../controllers/dashboardResponse.php';

dashboardResponse(function ($db) {
    $sql = "
        SELECT
            DATE(m.fecha) AS fecha,
            SUM(m.ingresos) AS ingresos,
            SUM(m.despachos) AS despachos
        FROM (
            SELECT
                DATE(arrival_date) AS fecha,
                COUNT(*) AS ingresos,
                0 AS despachos
            FROM app_outer_port
            WHERE arrival_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(arrival_date)

            UNION ALL

            SELECT
                DATE(departure_date) AS fecha,
                0 AS ingresos,
                COUNT(*) AS despachos
            FROM app_outer_port
            WHERE departure_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY DATE(departure_date)
        ) m
        GROUP BY DATE(m.fecha)
        ORDER BY fecha ASC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
});
