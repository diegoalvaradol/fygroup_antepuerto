<?php

declare(strict_types=1);

require_once __DIR__ . '/config/includes.php';

/* Obtener módulo desde GET o desde la URL */
$pag = $_GET['pag'] ?? '';

if ($pag === '') {
    $pag = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
}

$pag = preg_replace('/\.php$/', '', $pag);
$t = $_GET['t'] ?? '';
$ttl = $_GET['ttl'] ?? '';
$sig = $_GET['sig'] ?? '';

/* Detectar área por dominio */
$host = strtolower($_SERVER['HTTP_HOST']);

$hostAreas = [
    'antepuerto.fygroup.cl' => 'myFY',
    'www.antepuerto.fygroup.cl' => 'myFY',

    'portalcliente.fygroup.cl' => 'myPortal',
    'www.portalcliente.fygroup.cl' => 'myPortal',

    'dev.fygroup.cl' => 'dev',
    'www.dev.fygroup.cl' => 'dev',
];

if (esLocalhost()) {
    $area = $_GET['area'] ?? 'myFY';
} elseif (isset($hostAreas[$host])) {
    $area = $hostAreas[$host];
} else {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

/* Página principal */
if ($pag === '') {
    $pag = 'dashboard';
}

/* Protección */
if (
    str_contains($pag, '..') ||
    str_contains($pag, '\\') ||
    str_starts_with($pag, '/')
) {
    http_response_code(403);
    require __DIR__ . '/error.php';
    exit;
}

/* Validar firma (excepto login si así lo deseas) */
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

/* Cargar archivo */
$filePath = __DIR__ . "/{$area}/{$pag}.php";

if (is_file($filePath)) {
    require $filePath;
} else {
    http_response_code(404);
    require __DIR__ . '/404.php';
}
