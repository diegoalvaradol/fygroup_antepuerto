<?php
/* Muestreo de errores PHP */
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once __DIR__ . '/config/includes.php';

$pag  = $_GET['pag'] ?? '/';
$area = $_GET['area'] ?? 'mySSL';
$mkey = $_GET['mkey'] ?? '';

/* Carpeta accedida desde la URL */
$uriParts      = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$folderFromUrl = esLocalhost() ? ($uriParts[1] ?? '') : ($uriParts[0] ?? ''); /* Después de ssl-chile */

/* Valida que la url contenga el mkey */
if ($mkey === '') {
  http_response_code(401);
  require __DIR__ . '/mkey_error.php';
  exit;
}

/* Valida el area con el directorio */
if ($folderFromUrl !== $area) {
  http_response_code(403);
  require __DIR__ . '/error.php';
  exit;
}

/* Vista especial para raíz */
if ($pag === '/') {
  require __DIR__ . '/dashboard.php';
  exit;
}

/* Solo permitir estas carpetas */
$allowedAreas = ['mySSL', 'myPortal'];

if (!in_array($area, $allowedAreas)) {
  http_response_code(404);
  require __DIR__ . '/404.php';
  exit;
}

/* Construir ruta */
$filePath = __DIR__ . "/{$area}/{$pag}.php";

/* Verificar que exista solo en esa carpeta */
if (file_exists($filePath)) {
  require $filePath;
} else {
  http_response_code(404);
  require __DIR__ . '/404.php';
}
