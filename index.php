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
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$area = null;

/* ================================
 * PRODUCCIÓN (por dominio)
 * ================================ */
if (str_contains($host, 'fy-group.cl')) {
    $hostAreas = [
        'antepuerto.fy-group.cl' => 'myFY',
        'portalcliente.fy-group.cl' => 'myPortal',
        'dev.fy-group.cl' => 'dev',
    ];

    $area = $hostAreas[$host] ?? null;
} else {
    /* ================================
     * LOCALHOST (por carpeta)
     * Ej:
     * /fygroup-antepuerto/myFY/
     * /fygroup-antepuerto/myPortal/
     * /fygroup-antepuerto/dev/
     * ================================ */
    $segments = explode('/', $path);

    /*
        [0] fygroup-antepuerto
        [1] myFY
    */
    if (isset($segments[0], $segments[1]) && strtolower($segments[0]) === 'fygroup-antepuerto') {
        switch (strtolower($segments[1])) {
            case 'myfy':
                $area = 'myFY';
                break;

            case 'myportal':
                $area = 'myPortal';
                break;

            case 'dev':
                $area = 'dev';
                break;
        }
    }
}

/* Área inválida */
if ($area === null) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

/* Página por defecto */
if ($pag === '') {
    $pag = 'dashboard';
}

/* Seguridad */
if (str_contains($pag, '..') || str_contains($pag, '\\') || str_starts_with($pag, '/')) {
    http_response_code(403);
    require __DIR__ . '/error.php';
    exit;
}

/* Validación de firma */
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

/* Archivo solicitado */
$filePath = __DIR__ . DIRECTORY_SEPARATOR . $area . DIRECTORY_SEPARATOR . $pag . '.php';

if (is_file($filePath)) {
    require $filePath;
} else {
    http_response_code(404);
    require __DIR__ . '/404.php';
}
