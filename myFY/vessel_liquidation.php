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
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <link rel="manifest" href="../favicon/site.webmanifest">
    <title>FYGroup | Liquidación de Nave</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
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
                    <h1 class="h3 mb-1 text-gray-800">Liquidación de Nave</h1>
                    <p class="mb-4">Acá podrás obtener la liquidación de carga por nave y/o exportador.</p>

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
                                    <form class="form-container" id="vesselReportForm">
                                        <div class="form-group row">
                                            <!-- Exportador -->
                                            <div class="col-md-3">
                                                <label for="exporter" class="text-gray-800 font-weight-bold">Exportador <em>(Opcional)</em></label>
                                                <select class="form-control select2" id="exporter" name="exporter">
                                                    <option value="-">Seleccione un exportador...</option>
                                                </select>
                                            </div>

                                            <!-- Motonave -->
                                            <div class="col-md-3">
                                                <label for="vessel" class="text-gray-800 font-weight-bold">Motonave</label>
                                                <select class="form-control select2" id="vessel" name="vessel">
                                                    <option value="-">Seleccione una motonave...</option>
                                                </select>
                                            </div>

                                            <!-- Información Motonave -->
                                            <div class="col-md-3">
                                                <label class="text-gray-800 font-weight-bold">Información de Motonave</label>
                                                <small id="info-vessel"></small>
                                            </div>

                                            <!-- Liquidación y Excel-->
                                            <div class="col-md-3">
                                                <label class="text-gray-800 font-weight-bold d-block">Liquidación Motonave</label>
                                                <div id="btnPdfVesselLiquidation"></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
    <script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
var exportExcel = function(nave) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../controllers/shipsReportDownloadExcelController.php';
  form.style.display = 'none';

  const fields = {
    nave: nave
  };

  for (const key in fields) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = key;
    input.value = fields[key];
    form.appendChild(input);
  }

  document.body.appendChild(form);
  form.submit();
}

$(document).ready(function() {
  $('#vessel').select2({
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/vesselJsonController.php',
      method: 'POST',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          search: params.term,
          finished: 1
        };
      },
      processResults: function (data) {
        return { results: data };
      },
      cache: true
    }
  });

  $('#exporter').select2({
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/exporterJsonController.php',
      method: 'POST',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          search: params.term
        };
      },
      processResults: function (data) {
        return { results: data };
      },
      cache: true
    }
  });

  $('#vessel').on('change', cargarLiquidacion);
  $('#exporter').on('change', cargarLiquidacion);

  function cargarLiquidacion() {
    const vessel   = $('#vessel').val();
    const exporter = $('#exporter').val();

    if (!vessel || vessel === '-') {
      $('#info-vessel').html('');
      $('#btnPdfVesselLiquidation').html('');

      return;
    }

    $.ajax({
      url: '../controllers/vesselInfoController.php',
      method: 'POST',
      data: { id: vessel },
      success: function (response) {
        $('#info-vessel').html(response);
      },
      error: function () {
        $('#info-vessel').html('<div class="text-danger">Error al obtener la información.</div>');
      }
    });

    $.ajax({
      url: '../controllers/getLiquidacionController.php',
      method: 'POST',
      data: {
        id: vessel,
        exporter: exporter
      },
      success: function (response) {
        $('#btnPdfVesselLiquidation')
          .html(response)
          .css('margin', '0 auto')
          .css('display', 'inline-block');
      },
      error: function () {
        $('#btnPdfVesselLiquidation').html(`
          <div class="alert alert-warning">
            No se pudo generar la liquidación.
          </div>
        `);
      }
    });
  }
})
</script>
