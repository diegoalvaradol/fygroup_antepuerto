<?php
$dir      = __DIR__ . "/../shipping_planning";
$archivos = glob($dir . '/*.pdf');

if (!$archivos) {
  header('Content-Type: text/html; charset=utf-8');
  echo '<p style="text-align:center;margin-top:50px;">No existen archivos para mostrar.</p>';

  exit;
}

// último PDF por fecha de modificación
usort($archivos, fn($a, $b) => filemtime($b) - filemtime($a));
$ultimo = $archivos[0];

header('Content-Type: application/pdf');
header('Content-Disposition: inline');
readfile($ultimo);

exit;
