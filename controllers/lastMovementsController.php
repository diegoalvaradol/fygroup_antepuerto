<?php
require_once __DIR__ . '/../controllers/dashboardResponse.php';

dashboardResponse(function ($db) {
    $sql = "
        SELECT
            car_plate AS patente,
            container AS contenedor,

            CASE
                WHEN origin = 1 THEN 'Contenedor'
                WHEN origin = 2 THEN 'Termo / Pallets'
                ELSE 'Otro'
            END AS tipo_carga,

            COALESCE(
                NULLIF(exporter, ''),
                NULLIF(agency, ''),
                'Sin empresa'
            ) AS empresa,

            arrival_date AS ingreso,
            departure_date AS salida

        FROM app_outer_port

        ORDER BY row_id DESC
        LIMIT 10
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    $movements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($movements as &$item) {
        $item['ingreso'] = formatDate($item['ingreso']);
        $item['salida'] = !empty($item['salida']) ? formatDate($item['salida']) : 'Pendiente';
        $item['patente'] = formatCarPlate($item['patente']);
    }

    unset($item);

    return $movements;
});
