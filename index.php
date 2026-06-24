<?php

declare(strict_types=1);

require_once __DIR__ . '/config/includes.php';

$pag = $_GET['pag'] ?? '';
$area = $_GET['area'] ?? 'myFY';

$t = $_GET['t'] ?? '';
$ttl = $_GET['ttl'] ?? '';
$sig = $_GET['sig'] ?? '';

/* Áreas permitidas */
$allowedAreas = ['dev', 'myFY', 'myPortal'];

if (!in_array($area, $allowedAreas, true)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

/* Carpeta desde URL */
$uriParts = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

$folderFromUrl = esLocalhost()
    ? ($uriParts[1] ?? '')
    : ($uriParts[0] ?? '');

/* validar firma obligatoria */
if ($sig === '' || $t === '' || $ttl === '') {
    http_response_code(401);
    require __DIR__ . '/mkey_error.php';
    exit;
}

/* validar área vs URL */
if ($folderFromUrl !== $area) {
    http_response_code(403);
    require __DIR__ . '/error.php';
    exit;
}

/* validar expiración */
if ((time() - (int) $t) > (int) $ttl) {
    http_response_code(401);
    require __DIR__ . '/mkey_error.php';
    exit;
}

/* validar firma */
$secret = 'FYGROUP_DIEGO_2026_0517';

$data = $pag . '|' . $area . '|' . $t . '|' . $ttl;

$expectedSig = hash_hmac('sha256', $data, $secret);

if (!hash_equals($expectedSig, $sig)) {
    http_response_code(403);
    require __DIR__ . '/error.php';
    exit;
}

/* vista por defecto */
if ($pag === '') {
    require __DIR__ . '/dashboard.php';
    exit;
}

/* protección path traversal */
if (
    str_contains($pag, '..') ||
    str_contains($pag, '\\') ||
    str_starts_with($pag, '/')
) {
    http_response_code(403);
    require __DIR__ . '/error.php';
    exit;
}

/* ruta final */
$filePath = __DIR__ . "/{$area}/{$pag}.php";

if (is_file($filePath)) {
    require $filePath;
} else {
    http_response_code(404);
    require __DIR__ . '/404.php';
}
