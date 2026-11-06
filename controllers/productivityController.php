<?php
require_once __DIR__ . '/../controllers/dashboardResponse.php';

dashboardResponse(function ($db) {
    $sql = "
        SELECT
            (
                SELECT COUNT(*)
                FROM app_outer_port
                WHERE arrival_date >= DATE_SUB(
                    NOW(),
                    INTERVAL 7 DAY
                )
            ) AS avgEntries,

            (
                SELECT COUNT(*)
                FROM app_outer_port
                WHERE departure_date >= DATE_SUB(
                    NOW(),
                    INTERVAL 7 DAY
                )
            ) AS avgDepartures,

            (
                SELECT SEC_TO_TIME(
                    AVG(
                        TIMESTAMPDIFF(
                            SECOND,
                            arrival_date,
                            departure_date
                        )
                    )
                )
                FROM app_outer_port
                WHERE arrival_date IS NOT NULL
                  AND departure_date IS NOT NULL
                  AND departure_date >= DATE_SUB(
                      NOW(),
                      INTERVAL 7 DAY
                  )
            ) AS avgCycle
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
});
