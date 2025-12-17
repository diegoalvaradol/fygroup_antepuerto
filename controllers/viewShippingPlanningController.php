<?php
$dir      = __DIR__ . "/../shipping_planning";
$archivos = glob($dir . '/*.pdf');

if (!$archivos) {
  echo '<p style="text-align:center;margin-top:50px;">No existen archivos para mostrar.</p>';
  exit;
}

usort($archivos, fn($a, $b) => filemtime($b) <=> filemtime($a));
$ultimo = basename($archivos[0]);
$pdf    = "../shipping_planning/$ultimo";
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{margin:0}
canvas{width:100%}
</style>
</head>
<body>

<canvas id="pdf"></canvas>

<script src="../pdfjs/pdf.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc = '../pdfjs/pdf.worker.js';

pdfjsLib.getDocument('<?=$pdf?>').promise.then(pdf => {
    pdf.getPage(1).then(page => {
        const scale = window.innerWidth / page.getViewport({ scale: 1 }).width;
        const viewport = page.getViewport({ scale });

        const canvas = document.getElementById('pdf');
        const ctx = canvas.getContext('2d');

        canvas.height = viewport.height;
        canvas.width  = viewport.width;

        page.render({ canvasContext: ctx, viewport });
    });
});
</script>

</body>
</html>
