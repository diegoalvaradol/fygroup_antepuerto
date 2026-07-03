<?php

declare(strict_types=1);

require_once __DIR__ . '/config/includes.php';

/* Página solicitada */
$pag = $_GET['pag'] ?? '';

if ($pag === '') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $pag = trim($path, '/');
}

$pag = preg_replace('/\.php$/', '', $pag);

/* Parámetros */
$t = $_GET['t'] ?? '';
$ttl = $_GET['ttl'] ?? '';
$sig = $_GET['sig'] ?? '';

/* Detecta área */
$host = strtolower($_SERVER['HTTP_HOST']);
$uri = strtolower($_SERVER['REQUEST_URI']);

$area = null;

/* PRODUCCIÓN POR DOMINIO */
if (str_contains($host, 'fygroup.cl')) {
    $hostAreas = [
        'antepuerto.fygroup.cl' => 'myFY',
        'portalcliente.fygroup.cl' => 'myPortal',
        'dev.fygroup.cl' => 'dev',
    ];

    $area = $hostAreas[$host] ?? null;

    /* LOCAL POR RUTA */
} else {
    if (str_contains($uri, '/ssl-chile/myfy')) {
        $area = 'myFY';
    } elseif (str_contains($uri, '/ssl-chile/myportal')) {
        $area = 'myPortal';
    } elseif (str_contains($uri, '/ssl-chile/dev')) {
        $area = 'dev';
    }
}

/* Fallback obligatorio */
if (!$area) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

/* Default */
if ($pag === '') {
    $pag = 'dashboard';
}

/* Seguridad */
if (
    str_contains($pag, '..') ||
    str_contains($pag, '\\') ||
    str_starts_with($pag, '/')
) {
    http_response_code(403);
    require __DIR__ . '/error.php';
    exit;
}

/* Firma */
if ($pag !== 'login') {
    if ($sig === '' || $t === '' || $ttl === '') {
        http_response_code(401);
        require __DIR__ . '/mkey_error.php';
        exit;
    }

    if ((time() - (int) $t) > (int) $ttl) {
        http_response_code(401);
        require __DIR__ . '/mkey_error.php';
        exit;
    }

    $secret = 'FYGROUP_DIEGO_2026_0517';

    $data = $pag . '|' . $t . '|' . $ttl;

    $expectedSig = hash_hmac('sha256', $data, $secret);

    if (!hash_equals($expectedSig, $sig)) {
        http_response_code(403);
        require __DIR__ . '/error.php';
        exit;
    }
}

/* Carga Final */
$filePath = __DIR__ . "/{$area}/{$pag}.php";

if (is_file($filePath)) {
    require $filePath;
} else {
    http_response_code(404);
    require __DIR__ . '/404.php';
}
