<?php

declare(strict_types=1);
/* Archivo para descarga de archivos desde la vista de respaldo de archivos (load_schedule.php) */

$file = basename($_GET['file']);
$path = '../shipping_planning/' . $file;

if (file_exists($path)) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    header('Content-Length: ' . filesize($path));
    readfile($path);

    exit;
} else {
    echo 'Archivo no encontrado.';
}
