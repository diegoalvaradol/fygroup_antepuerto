<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

/* Validación de URL */
$module = $_GET['pag'] ?? '';
$time = $_GET['t'] ?? '';
$ttl = $_GET['ttl'] ?? '';
$sig = $_GET['sig'] ?? '';

if (!validateSecureLink($module, $time, $ttl, $sig)) {
    die('Acceso inválido o expirado');
}

$user = new user();
$dev = $user->isDev($_SESSION['user']['run']);
$footer = menu::footerSSL();

/* Validar desarrollador */
if (!$dev) {
    $usuario = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . ' (' . $_SESSION['user']['run'] . ')';
    $pag = basename(__FILE__);
    $url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    mostrarAccesoDenegado($usuario, $pag, $url);
}
?>

<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <link rel="manifest" href="../favicon/site.webmanifest">
    <title>FYGroup | Respaldo de Archivos</title>

    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .content-wrapper {
            flex: 1;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <div class="container py-5">
            <div class="text-center mb-4">
                <img src="../logos/new-logo-fygroup-bg-removed.png" class="img-fluid" style="max-width: 180px;">
                <h3 class="mt-3">Respaldo de Archivos</h3>

                <div class="card shadow mx-auto" style="max-width: 600px;">
                    <!-- Breadcrumb -->
                    <?= menu::breadcrumb(); ?>
                </div>
            </div>

            <div class="card shadow mx-auto" style="max-width: 600px;">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-upload mr-2"></i>Subir archivo</h6>
                </div>

                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Archivo</label>
                            <input type="file" name="archivo" id="archivo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre personalizado</label>
                            <input type="text" name="customName" id="customName" class="form-control" placeholder="Ej: respaldo_2025">
                        </div>

                        <div class="text-center">
                            <button type="button" onclick="uploadFile()" class="btn btn-primary w-100">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>Subir Archivo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow mt-4 mx-auto" style="max-width: 600px;">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-folder-open mr-2"></i>Archivos cargados</h6>
                </div>

                <div class="card-body">
                    <?php $dir = __DIR__ . '/../uploads/'; ?>
                    <?php $archivos = array_diff(scandir($dir), ['.', '..']); ?>

                    <?php if (empty($archivos)): ?>
                        <p class="text-muted text-center">No hay archivos cargados.</p>
                    <?php else: ?>
                        <ul class="list-group" id="fileList">
                            <?php foreach ($archivos as $archivo): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center" data-file="<?=htmlspecialchars($archivo)?>">
                                    <?=htmlspecialchars($archivo)?>
                                    <div>
                                        <a href="../controllers/downloadFiles.php?file=<?=urlencode($archivo)?>" class="btn btn-sm btn-success me-1" title="Descargar">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        <button class="btn btn-sm btn-danger btn-delete" title="Eliminar" data-file="<?=htmlspecialchars($archivo)?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>Volver al Inicio
                </a>
            </div>
        </div>
    </div>

    <?=$footer;?>

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="../assets/vendor/jquery/jquery.min.js"></script>
	<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
	<script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
</html>

<script>
function uploadFile() {
    const archivo = document.getElementById("archivo").files[0];
    const nombre = document.getElementById("customName").value.trim();

    if (!archivo) {
        Swal.fire('Error', 'Debes seleccionar un archivo', 'error');
        return;
    }

    if (archivo.size > 5 * 1024 * 1024) {
        Swal.fire('Error', 'El archivo supera los 5MB', 'error');
        return;
    }

    const formData = new FormData(document.getElementById("uploadForm"));
    $.ajax({
        url: "../controllers/uploadFiles.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
            if (res === "OK") {
                Swal.fire('Éxito', 'Archivo subido correctamente', 'success').then(() => {
                    window.location.href = "<?=generateSecureLink('files_backup');?>";
                });
            } else {
                Swal.fire('Error', 'No se pudo subir el archivo', 'error');
            }
        },
        error: () => Swal.fire('Error', 'Ocurrió un error de red', 'error')
    });
}

$(document).ready(function() {
    $('.btn-delete').click(function() {
        const file = $(this).data('file');
        const listItem = $(this).closest('li');

        Swal.fire({
        title: '¿Eliminar archivo?',
        text: "No podrás revertir esta acción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                url: '../controllers/deleteFiles.php',
                method: 'POST',
                data: { file: file },
                success: function(response) {
                    if (response === 'OK') {
                    Swal.fire('Eliminado', 'El archivo fue eliminado.', 'success');
                    listItem.remove();
                    if ($('#fileList li').length === 0) {
                        $('#fileList').html('<p class="text-muted text-center">No hay archivos cargados.</p>');
                    }
                    } else {
                    Swal.fire('Error', response, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Error en la comunicación con el servidor.', 'error');
                }
                });
            }
        });
    });
});
</script>
