<?php

declare(strict_types=1);
/* Archivo para cargar archivos desde la vista de respaldo de archivos (files_backup.php) */

$targetDir = '../uploads/';
$customName = $_POST['customName'] ?? '';
$originalExt = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);

/* Usa el nombre personalizado con la extensión original */
$newFileName = $customName !== '' ? $customName . '.' . $originalExt : basename($_FILES['archivo']['name']);
$targetFile = $targetDir . $newFileName;

if (move_uploaded_file($_FILES['archivo']['tmp_name'], $targetFile)) {
    echo 'OK';
} else {
    echo 'NOOK';
}
