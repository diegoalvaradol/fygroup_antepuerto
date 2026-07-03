<?php

declare(strict_types=1);
require_once '../config/includes.php';

header('Content-Type: application/json');

$cfg = new cfg();

echo json_encode([
    'time' => date('H:i:s'),

    'system' => [
        'php' => phpversion(),
        'server' => $_SERVER['SERVER_SOFTWARE'],
        'os' => PHP_OS_FAMILY,
        'uptime' => shell_exec('uptime') ?: null,
    ],

    'database' => [
        'status' => true,
        'version' => $cfg->getMysqlVersion(),
        'tables' => $cfg->getTotalTables(),
        'size' => $cfg->getDatabaseSize(),
    ],

    'php' => [
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'loaded_extensions' => count(get_loaded_extensions()),
    ],

    'disk' => $cfg->getDiskUsage(),

    'services' => $cfg->getServicesStatus(),
]);
