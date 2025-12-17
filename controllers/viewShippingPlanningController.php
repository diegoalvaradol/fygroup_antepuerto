<?php
$dir      = __DIR__ . "/../shipping_planning";
$archivos = glob($dir . '/*.pdf');

if (!$archivos) {
  header('Content-Type: text/html; charset=utf-8');
  echo '<p style="text-align:center;margin-top:50px;">No existen archivos para mostrar.</p>';
  exit;
}

// último PDF por fecha de modificación
usort($archivos, fn($a, $b) => filemtime($b) <=> filemtime($a));
$ultimo = basename($archivos[0]);
$ruta   = "../shipping_planning/" . $ultimo;

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
html,body{
    margin:0;
    height:100%;
}
iframe{
    width:100%;
    height:100vh;
    border:0;
}
</style>
</head>
<body>

<iframe src="<?=htmlspecialchars($ruta)?>"></iframe>

</body>
</html>
<?php
exit;
