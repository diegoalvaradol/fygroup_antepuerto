<?php

declare(strict_types=1);
session_start();

/* ===============================
Base path automático
=============================== */
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$basePath = str_replace(['/dev', '/myFY', '/myPortal'], '', $basePath);

/* ===============================
Validar carpeta permitida
=============================== */
$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$allowedRoots = ['/dev', '/myFY', '/myPortal'];

$isAllowedRoot = false;

foreach ($allowedRoots as $root) {
    $endsWithRoot = substr($uriPath, -strlen($root)) === $root;

    if (strpos($uriPath, $root . '/') !== false || $endsWithRoot) {
        $isAllowedRoot = true;
        break;
    }
}

if (!$isAllowedRoot) {
    http_response_code(403);
    exit('Acceso no permitido.');
}

/* ===============================
Validar sesión
=============================== */
if (!isset($_SESSION['user'])) {
    header("Location: {$basePath}/login.php");
    exit();
}

/* ===============================
Variables
=============================== */
$division = $_SESSION['user']['division'] ?? '';
$seconds = 5;
$redirect = "{$basePath}/login.php";
$forbidden = false;

/* ===============================
Reglas de acceso
=============================== */
if (strpos($uriPath, '/myPortal/') !== false) {
    if ($division !== 'terminal' && $division !== 'shipper') {
        $redirect = "{$basePath}/myPortal/login.php";
        $forbidden = true;
    }
}

if (strpos($uriPath, '/myFY/') !== false) {
    if ($division !== 'fy') {
        $redirect = "{$basePath}/myFY/login.php";
        $forbidden = true;
    }
}

if (strpos($uriPath, '/dev/') !== false) {
    if ($division !== 'fy') {
        $redirect = "{$basePath}/dev/login.php";
        $forbidden = true;
    }
}

/* ===============================
Vista 403
=============================== */
if ($forbidden) {
    http_response_code(403);

    $_SESSION['redirect_after_403'] = $redirect;
    $_SESSION['redirect_seconds_403'] = $seconds;

    require_once __DIR__ . '/../error.php';
    exit();
}
