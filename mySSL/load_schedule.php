<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$user   = new user();
$admin  = $user->isAdmin($_SESSION["user"]["run"]);
$footer = menu::footerSSL();

if (!$admin) {
  mostrarAccesoDenegado($_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"], basename(__FILE__), $_SERVER['REQUEST_URI']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Vista Formulario de Registro de Nuevo Usuario" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>SSL | Carga Planificación</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

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
        <img src="../images/ssl-logo-azul.png" class="img-fluid" style="max-width: 180px;">
        <h3 class="mt-3">Carga Planificación Naviera</h3>
      </div>

      <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
          <h6 class="mb-0"><i class="fas fa-upload"></i> Cargar Planificación</h6>
        </div>
        <div class="card-body">
          <form id="uploadForm" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Archivo Planificación</label>
              <input type="file" name="archivo" id="archivo" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Nombre Planificación</label>
              <input type="text" name="shppingPlanningName" id="shppingPlanningName" class="form-control" placeholder="Ej: Planificación_14-12-2025">
            </div>
            <div class="text-center">
              <button type="button" onclick="uploadFile()" class="btn btn-primary w-100">
                <i class="fas fa-cloud-upload-alt"></i> Subir Planificación
              </button>
            </div>
          </form>
        </div>
      </div>

			<div class="card shadow mt-4 mx-auto" style="max-width: 600px;">
				<div class="card-header bg-success text-white">
					<h6 class="mb-0"><i class="fas fa-folder-open"></i> Planificaciones Cargadas</h6>
				</div>
				<div class="card-body">
					<?php $dir      = __DIR__ . "/../shipping_planning/"; ?>
					<?php $archivos = array_diff(scandir($dir), ['.', '..']); ?>

					<?php if (empty($archivos)): ?>
							<p class="text-muted text-center">No hay archivos cargados.</p>
					<?php else: ?>
					<table class="table table-bordered table-hover">
							<thead style="background-color:#4e73df; color:white;">
									<tr>
											<th style="width:5%">#</th>
											<th>Nombre</th>
											<th style="width:20%" class="text-center">Acciones</th>
									</tr>
							</thead>
							<tbody>
									<?php foreach ($archivos as $i => $archivo): ?>
											<tr data-file="<?=htmlspecialchars($archivo)?>">
													<td><?=$i + 1?></td>
													<td><?=htmlspecialchars($archivo)?></td>
													<td class="text-center">
															<a href="../controllers/downloadFilesSchedule.php?file=<?=urlencode($archivo)?>"class="btn btn-sm btn-success me-1" title="Descargar"><i class="fas fa-download"></i></a>
															<button class="btn btn-sm btn-danger btn-delete"title="Eliminar" data-file="<?=htmlspecialchars($archivo)?>"><i class="fas fa-trash"></i></button>
													</td>
											</tr>
									<?php endforeach; ?>
							</tbody>
					</table>
					<?php endif; ?>
				</div>
			</div>

      <div class="text-center mt-4">
				<a href="dashboard.php" class="btn btn-sm btn-primary">
					<i class="fas fa-arrow-left"></i> Volver al Inicio
				</a>
			</div>
    </div>
  </div>

  <?=$footer;?>

  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Bootstrap core JavaScript-->
  <script src="../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../assets/js/sb-admin-2.min.js"></script>

  <!-- Bootstrap JS (necesario para popover) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>

<script>
	function uploadFile() {
		const archivo = document.getElementById("archivo").files[0];
		const nombre = $('#shppingPlanningName').val();

		if (!archivo) {
			Swal.fire('Error', 'Debes seleccionar un archivo.', 'error');
			return;
		}

		if (nombre == '') {
			Swal.fire('Error', 'Debes asignar un nombre al archivo.', 'error');
			return;
		}

		if (archivo.size > 5 * 1024 * 1024) {
			Swal.fire('Error', 'El archivo supera los 5MB.', 'error');
			return;
		}

		const formData = new FormData(document.getElementById("uploadForm"));
		$.ajax({
			url: "../controllers/uploadFilesSchedule.php",
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
			success: function (res) {
				if (res === "OK") {
					Swal.fire('Éxito', 'Archivo subido correctamente', 'success').then(() => {
						window.location.href = "<?=generateMkey('load_schedule');?>";
					});
				} else {
					Swal.fire('Error', 'No se pudo subir el archivo.', 'error');
				}
			},
			error: () => Swal.fire('Error', 'Ocurrió un error de red.', 'error')
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
            url: '../controllers/deleteFilesScheudle.php',
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