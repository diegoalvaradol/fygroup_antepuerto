<?php

declare(strict_types=1);

date_default_timezone_set('America/Santiago');

/* Aplicación actual */
if (!defined('APP_MODE')) {
    define('APP_MODE', 'FYGROUP');
}

/* Configuración de estados */
require_once __DIR__ . '/system_status_config.php';

/**
 * Indica si un período está activo.
 *
 * Si está habilitado y no tiene fechas,
 * queda activo indefinidamente.
 */
function isPeriodActive(bool $enabled, ?string $start = null, ?string $end = null): bool
{
    if (!$enabled) {
        return false;
    }

    if (empty($start) || empty($end)) {
        return true;
    }

    $now = new DateTime();

    return $now >= new DateTime($start) && $now <= new DateTime($end);
}

/**
 * Obtiene información del período.
 */
function getPeriodInfo(?string $start, ?string $end): array
{
    if (empty($start) || empty($end)) {
        return [
            'has_dates' => false,

            'remaining_days' => null,
            'remaining_hours' => null,
            'remaining_minutes' => null,
            'remaining_seconds' => null,

            'remaining_text' => 'Activo indefinidamente',

            'progress' => 0,
            'progress_text' => '0%',

            'status_text' => 'Activo',
            'status_class' => 'warning',
        ];
    }

    $now = new DateTime();
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);

    $totalSeconds = max(1, $endDate->getTimestamp() - $startDate->getTimestamp());
    $elapsed = max(0, min($totalSeconds, $now->getTimestamp() - $startDate->getTimestamp()));
    $remaining = max(0, $endDate->getTimestamp() - $now->getTimestamp());

    // Tiempo restante
    $days = intdiv($remaining, 86400);
    $hours = intdiv($remaining % 86400, 3600);
    $minutes = intdiv($remaining % 3600, 60);
    $seconds = $remaining % 60;

    // Duración
    $duration = $endDate->diff($startDate);

    // Progreso
    $progress = round(($elapsed / $totalSeconds) * 100, 1);
    $progress = min(100, max(0, $progress));

    // Estado
    $isStarted = $now >= $startDate;
    $isFinished = $now >= $endDate;
    $isRunning = $isStarted && !$isFinished;

    if ($isFinished) {
        $statusText = 'Finalizado';
        $statusClass = 'success';
    } elseif ($isRunning) {
        $statusText = 'En curso';
        $statusClass = 'warning';
    } else {
        $statusText = 'Programado';
        $statusClass = 'info';
    }

    return [
        'has_dates' => true,

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

/* Validación según aplicación */

$status = SYSTEM_STATUS[APP_MODE] ?? null;

if ($status === null) {
    throw new RuntimeException(
        'APP_MODE no válido: ' . APP_MODE
    );
}

/* Modo Mantenimiento */
if (isPeriodActive($status['maintenance'], $status['maintenance_start'] ?? null, $status['maintenance_end'] ?? null)) {
    $period = getPeriodInfo($status['maintenance_start'] ?? null, $status['maintenance_end'] ?? null);

    require_once __DIR__ . '/../maintenance.php';

    exit;
}

/* Sistema Cerrado */
if (isPeriodActive($status['closed'], $status['closed_start'] ?? null, $status['closed_end'] ?? null)) {
    $period = getPeriodInfo($status['closed_start'] ?? null, $status['closed_end'] ?? null);

    require_once __DIR__ . '/../closed.php';

    exit;
}
