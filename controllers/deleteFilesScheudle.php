<?php
/* Archivo para eliminar archivos desde la vista de respaldo de archivos (load_schedule.php) */

$uploadDir = realpath(__DIR__ . '/../shipping_planning') . DIRECTORY_SEPARATOR;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['file'])) {
  $file = basename($_POST['file']); // Previene path traversal

  $filePath = $uploadDir . $file;

  if (file_exists($filePath)) {
    if (unlink($filePath)) {
      echo 'OK';
    } else {
      http_response_code(500);
      echo 'No se pudo eliminar el archivo.';
    }
  } else {
    http_response_code(404);
    echo 'Archivo no encontrado.';
  }
} else {
  http_response_code(400);
  echo 'Parámetros inválidos.';
}
