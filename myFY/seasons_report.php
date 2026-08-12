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

$cfg = new cfg();
$user = new user();
$port = new outerPort();

$infoCfg = json_decode($cfg->getInfo(1), true);
$admin = $user->isAdmin($_SESSION['user']['run']);
$releasedTime = new DateTime($infoCfg['released_date']);
$updateTime = new DateTime($infoCfg['update_date']);
$arrayDivision = get::getDivisionName();
$sideBarSSL = menu::sideBarSSL();
$mainTapBarSSL = menu::mainTapBarSSL();
$footer = menu::footerSSL();
$top = UIComponents::scrollToTopButton();
$modals = new Modals($infoCfg, $arrayDivision, $releasedTime, $updateTime);

/* Validar superadmin */
if (!$admin) {
    $usuario = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . ' (' . $_SESSION['user']['run'] . ')';
    $pag = basename(__FILE__);
    $url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    mostrarAccesoDenegado($usuario, $pag, $url);
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../favicon/fygroup.ico" rel="icon">
    <link href="../favicon/fygroup-256x256.png" rel="apple-touch-icon">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <title>FYGroup | Reporte de Temporada</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php echo $sideBarSSL; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php echo $mainTapBarSSL; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid-custom">
                    <!-- Breadcrumb -->
                    <?= menu::breadcrumb(); ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Reporte de Temporada</h1>
                    <p class="mb-4">Acá puedes visualizar la carga movilizada en cada temporada.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <!-- Custom Text Color Utilities -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Formulario de Búsqueda</h6>
                                </div>

                                <div class="card-body">
                                    <form class="form-container" id="seasonsReportForm">
                                        <div class="form-group row justify-content-center">
                                            <div class="col-sm-2">
                                                <label for="seasons" class="text-gray-800 font-weight-bold">Temporada</label>
                                                <select class="form-control select2 form-control-user" id="seasons" name="seasons">
                                                    <option value="">Seleccione una temporada...</option>
                                                    <option value="all">Todas las temporadas</option>
                                                    <?php foreach (get::arraySeasons() as $index => $period): ?>
                                                        <option value="<?= $index ?>">
                                                            <?= htmlspecialchars($period['label']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <small class="text-danger" id="error-seasons"></small>
                                            </div>

                                            <div class="col-sm-4" style="margin-top: 30px;">
                                                <button type="button" class="btn btn-primary btn-user" id="btnBuscar" onclick="loadSeasonsReport()">
                                                    <i class="fas fa-search mr-2"></i>Buscar
                                                </button>

                                                <button type="button" class="btn btn-success btn-user" id="btnExcel" onclick="exportSeason()" disabled>
                                                    <i class="fas fa-download"></i> <i class="fas fa-file-excel mr-2"></i>Excel
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Div de contenido Dinamico -->
                            <div class="d-flex justify-content-center mt-3 mb-3">
                                <div id="seasonCardMini" style="border:1px solid #e5e7eb; border-left:4px solid #2563eb; border-radius:8px; padding:8px 16px; background:#f9fafb; display:none">
                                    <div style="margin-bottom:6px;">
                                        <b style="color:#2563eb;">Información:</b>
                                        <span id="seasonTextMini" style="margin:0 6px; color:#9ca3af;"></span>
                                    </div>
                                </div>
                            </div>

                            <div id="loader" style="display:none; text-align:center; padding:20px;">
                                <i class="fas fa-spinner fa-spin fa-3x mr-2" style="color: #4e73df;"></i>Cargando información...
                            </div>

                            <!-- Tabla Reporte de Temporadas -->
                            <div id="seasonsDiv"></div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php echo $footer; ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button -->
    <?php echo $top; ?>

    <!-- Modales -->
    <?php echo $modals->render();?>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
var loadSeasonsReport = function() {
  const $btn = $('#btnBuscar');
  const $div = $('#seasonsDiv');
  const seasons = $('#seasons').val();
  const textSeasons = $('#seasons option:selected').text();

  const MIN_TIME = 2000;
  let startTime = Date.now();

  if (seasons === '-' || !seasons) {
    Swal.fire({
      title: 'Datos incompletos',
      text: 'Debe seleccionar una temporada.',
      icon: 'warning'
    });

    return;
  }

  $.ajax({
    url: '../controllers/seasonsReportController.php',
    type: 'POST',
    dataType: 'html',
    data: { seasons },

    beforeSend() {
      $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Buscando...');
      $('#loader').show();
      $div.hide();
    },

    success(response) {
      const clean = response.trim();

      if (clean.length > 0) {
        $div.html(clean);
        $('#seasonTextMini').html(`</br> Temporada: ${textSeasons}`).css('font-size', '14px').css('font-weight', 'bold');
        $('#seasonCardMini').fadeIn(150);
        $('#btnPrintSeasonsReport').prop('disabled', false);
        $('#btnExcel').prop('disabled', false);
      } else {
        $div.hide().empty();
        $('#seasonCardMini').fadeOut(150);
        $('#btnPrintSeasonsReport').prop('disabled', true);
        $('#btnExcel').prop('disabled', true);

        Swal.fire({
          icon: 'warning',
          title: 'Sin resultados',
          text: 'No se encontraron registros para la temporada seleccionado.'
        }).then((result) => {
          $('#loader').hide();
          $div.hide();
        });
      }
    },

    error(xhr) {
      console.error(xhr.responseText);
      $('#btnPrintSeasonsReport').prop('disabled', true);

      Swal.fire({
        title: 'Error',
        text: 'Error al consultar la información.',
        icon: 'error'
      }).then((result) => {
        $('#loader').hide();
        $div.hide();
      });
    },

    complete() {
      const elapsed = Date.now() - startTime;
      const remaining = Math.max(MIN_TIME - elapsed, 0);

      setTimeout(function () {
        $btn.prop('disabled', false).html('<i class="fas fa-search mr-2"></i>Buscar');
        $('#loader').hide();
        $div.show();
      }, remaining);
    }
  });
}

var exportSeason = function() {
  const seasons = $('#seasons').val();

  if (seasons === '-' || !seasons) return;
  window.location = `../controllers/seasonsReportExportController.php?seasons=${seasons}`;
}
</script>
