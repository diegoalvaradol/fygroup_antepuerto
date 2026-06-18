<?php

declare(strict_types=1);

/* Muestreo de errores PHP */
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once __DIR__ . '/config/includes.php';

$pag = $_GET['pag'] ?? '';
$area = $_GET['area'] ?? 'myFY';
$mkey = $_GET['mkey'] ?? '';

/* Áreas permitidas */
$allowedAreas = ['dev', 'myFY', 'myPortal'];

if (!in_array($area, $allowedAreas, true)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

/* Carpeta accedida desde la URL */
$uriParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$folderFromUrl = esLocalhost()
    ? ($uriParts[1] ?? '') // localhost/ssl-chile/myFY
    : ($uriParts[0] ?? ''); // dominio.com/myFY

/* Valida que la URL contenga el mkey */
if ($mkey === '') {
    http_response_code(401);
    require __DIR__ . '/mkey_error.php';
    exit;
}

/* Valida que el área coincida con la carpeta de la URL */
if ($folderFromUrl !== $area) {
    http_response_code(403);
    require __DIR__ . '/error.php';
    exit;
}

/* Vista por defecto */
if ($pag === '') {
    require __DIR__ . '/dashboard.php';
    exit;
}

/* Protección básica contra path traversal */
if (str_contains($pag, '..') || str_contains($pag, '\\') || str_starts_with($pag, '/')) {
    http_response_code(403);
    require __DIR__ . '/error.php';
    exit;
}

/* Construir ruta */
$filePath = __DIR__ . "/{$area}/{$pag}.php";

/* Verificar que exista y sea archivo */
if (is_file($filePath)) {
    require $filePath;
} else {
    http_response_code(404);
    require __DIR__ . '/404.php';
}
