<?php
require_once __DIR__ . '/../controllers/dashboardResponse.php';

dashboardResponse(function ($db) {

    $sql = "
        SELECT
            car_plate AS patente,

            CASE
                WHEN TIMESTAMPDIFF(MINUTE, arrival_date, NOW()) > 240
                    THEN 'critical'
                ELSE 'warning'
            END AS tipo,

            CASE
                WHEN TIMESTAMPDIFF(MINUTE, arrival_date, NOW()) > 240
                    THEN 'Permanencia crítica'
                ELSE 'Permanencia elevada'
            END AS titulo,

            SEC_TO_TIME(
                TIMESTAMPDIFF(SECOND, arrival_date, NOW())
            ) AS permanencia

        FROM app_outer_port

        WHERE departure_date IS NULL
          AND arrival_date IS NOT NULL
          AND TIMESTAMPDIFF(MINUTE, arrival_date, NOW()) >= 120

        ORDER BY arrival_date ASC
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $operationalAlerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($operationalAlerts as &$item) {
        $item['patente'] = formatCarPlate($item['patente']);
        $item['descripcion'] = 'Camión ' . $item['patente'] . ' lleva ' . $item['permanencia'] . ' en el antepuerto.';
    }

    unset($item);

    return $operationalAlerts;
});
