<?php
require_once __DIR__ . '/../controllers/dashboardResponse.php';

dashboardResponse(function ($db) {
    $sql = "
        SELECT
            car_plate AS patente,
            vessel_id AS nave,
            exporter AS empresa,
            arrival_date AS ingreso,
            TIMESTAMPDIFF(
                MINUTE,
                arrival_date,
                NOW()
            ) AS minutos,
            SEC_TO_TIME(
                TIMESTAMPDIFF(
                    SECOND,
                    arrival_date,
                    NOW()
                )
            ) AS permanencia
        FROM app_outer_port
        WHERE departure_date IS NULL
          AND arrival_date IS NOT NULL
          AND TIMESTAMPDIFF(
                MINUTE,
                arrival_date,
                NOW()
              ) > 240
        ORDER BY arrival_date ASC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $criticalTrucks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($criticalTrucks as &$item) {
        $item['ingreso'] = formatDate($item['ingreso']);
        $item['patente'] = formatCarPlate($item['patente']);
    }

    unset($item);

    return $criticalTrucks;
});
