<?php
/* Archivo para cargar archivos desde la vista de respaldo de archivos (load_schedule.php) */

$targetDir           = "../shipping_planning/";
$shppingPlanningName = $_POST['shppingPlanningName'] ?? '';
$originalExt         = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);

/* Usa el nombre personalizado con la extensión original */
$newFileName = $shppingPlanningName !== '' ? $shppingPlanningName . '.' . $originalExt : basename($_FILES['archivo']['name']);
$targetFile  = $targetDir . $newFileName;

if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $targetFile)) {
  echo 'OK';
} else {
  echo 'NOOK';
}
