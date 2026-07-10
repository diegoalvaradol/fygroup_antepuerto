<?php

declare(strict_types=1);

date_default_timezone_set('America/Santiago');

/* Aplicación actual */
if (!defined('APP_MODE')) {
    define('APP_MODE', 'FYGROUP');
}

/* Modo Mantenimiento */
define('MAINTENANCE_MODE_FYGROUP', false);
define('MAINTENANCE_MODE_PORTALCLIENTE', false);
define('MAINTENANCE_MODE_DEV', false);

define('MAINTENANCE_START', '2026-07-10 08:30:00');
define('MAINTENANCE_END', '2026-07-10 19:00:00');

/* Sistema Cerrado Temporalmente */
define('CLOSED_MODE_FYGROUP', true);
define('CLOSED_MODE_PORTALCLIENTE', true);
define('CLOSED_MODE_DEV', false);

define('CLOSED_START', '2026-07-10 00:00:00');
define('CLOSED_END', '2026-12-20 23:59:59');

/* Funciones */
/**
 * Indica si un período está activo.
 *
 * Si el modo está habilitado y las fechas están vacías,
 * permanecerá activo indefinidamente.
 */
function isPeriodActive(bool $enabled, string $start = '', string $end = ''): bool
{
    if (!$enabled) {
        return false;
    }

    if (trim($start) === '' || trim($end) === '') {
        return true;
    }

    $now = new DateTime();

    return $now >= new DateTime($start) && $now <= new DateTime($end);
}

/**
 * Obtiene toda la información del período.
 */
function getPeriodInfo(string $start, string $end): array
{
    $now = new DateTime();
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);

    $totalSeconds = max(1, $endDate->getTimestamp() - $startDate->getTimestamp());

    $elapsed = max(0, min(
        $totalSeconds,
        $now->getTimestamp() - $startDate->getTimestamp()
    ));

    $remaining = max(0, $endDate->getTimestamp() - $now->getTimestamp());

    // Tiempo restante
    $days = intdiv($remaining, 86400);
    $hours = intdiv($remaining % 86400, 3600);
    $minutes = intdiv($remaining % 3600, 60);
    $seconds = $remaining % 60;

    // Duración total
    $duration = $endDate->diff($startDate);

    // Progreso
    $progress = round(($elapsed / $totalSeconds) * 100, 1);
    $progress = min(100, max(0, $progress));

    // Estado
    $isStarted = $now >= $startDate;
    $isFinished = $now >= $endDate;
    $isRunning = $isStarted && !$isFinished;

    if ($isFinished) {
        $statusText = 'Mantención finalizada';
        $statusClass = 'success';
    } elseif ($isRunning) {
        $statusText = 'Mantención en curso';
        $statusClass = 'warning';
    } else {
        $statusText = 'Mantención programada';
        $statusClass = 'info';
    }

    return [

        // Objetos
        'start_object' => $startDate,
        'end_object' => $endDate,
        'current_object' => $now,

        // Fechas
        'start' => $startDate->format('d/m/Y H:i:s'),
        'end' => $endDate->format('d/m/Y H:i:s'),
        'current' => $now->format('d/m/Y H:i:s'),

        'start_date' => $startDate->format('d/m/Y'),
        'start_time' => $startDate->format('H:i:s'),

        'end_date' => $endDate->format('d/m/Y'),
        'end_time' => $endDate->format('H:i:s'),

        // Tiempo restante
        'remaining_days' => $days,
        'remaining_hours' => $hours,
        'remaining_minutes' => $minutes,
        'remaining_seconds' => $seconds,

        'remaining_total_seconds' => $remaining,

        'remaining_text' => sprintf(
            '%d día(s), %02d hora(s), %02d minuto(s) y %02d segundo(s)',
            $days,
            $hours,
            $minutes,
            $seconds
        ),

        // Duración
        'duration_days' => $duration->days,
        'duration_hours' => $duration->h,
        'duration_minutes' => $duration->i,

        'duration_text' => sprintf(
            '%d día(s), %02d hora(s) y %02d minuto(s)',
            $duration->days,
            $duration->h,
            $duration->i
        ),

        // Progreso
        'progress' => $progress,
        'progress_text' => number_format($progress, 0) . '%',

        'elapsed_seconds' => $elapsed,
        'elapsed_text' => sprintf(
            '%d%% completado',
            round($progress)
        ),

        'remaining_progress' => round(100 - $progress, 1),

        // Estado
        'is_started' => $isStarted,
        'is_finished' => $isFinished,
        'is_running' => $isRunning,

        'status_text' => $statusText,
        'status_class' => $statusClass,
    ];
}

/*
|--------------------------------------------------------------------------
| Validación según aplicación
|--------------------------------------------------------------------------
*/

switch (APP_MODE) {
    /* FYGROUP */
    case 'FYGROUP':

        if (isPeriodActive(MAINTENANCE_MODE_FYGROUP, MAINTENANCE_START, MAINTENANCE_END)) {
            $period = getPeriodInfo(MAINTENANCE_START, MAINTENANCE_END);

            require_once __DIR__ . '/../maintenance.php';
            exit;
        }

        if (isPeriodActive(CLOSED_MODE_FYGROUP, CLOSED_START, CLOSED_END)) {
            $period = getPeriodInfo(CLOSED_START, CLOSED_END);

            require_once __DIR__ . '/../closed.php';
            exit;
        }

        break;

        /* PORTAL CLIENTE*/
    case 'PORTALCLIENTE':
        if (isPeriodActive(MAINTENANCE_MODE_PORTALCLIENTE, MAINTENANCE_START, MAINTENANCE_END)) {
            $period = getPeriodInfo(MAINTENANCE_START, MAINTENANCE_END);

            require_once __DIR__ . '/../maintenance.php';
            exit;
        }

        if (isPeriodActive(CLOSED_MODE_PORTALCLIENTE, CLOSED_START, CLOSED_END)) {
            $period = getPeriodInfo(CLOSED_START, CLOSED_END);

            require_once __DIR__ . '/../closed.php';
            exit;
        }

        break;

        /* DEV */
    case 'DEV':
        if (isPeriodActive(MAINTENANCE_MODE_DEV, MAINTENANCE_START, MAINTENANCE_END)) {
            $period = getPeriodInfo(MAINTENANCE_START, MAINTENANCE_END);

            require_once __DIR__ . '/../maintenance.php';
            exit;
        }

        if (isPeriodActive(CLOSED_MODE_DEV, CLOSED_START, CLOSED_END)) {
            $period = getPeriodInfo(CLOSED_START, CLOSED_END);

            require_once __DIR__ . '/../closed.php';
            exit;
        }

        break;
}
