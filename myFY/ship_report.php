<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$port = new outerPort();
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
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>FYGroup | Reporte de Naves</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
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
                    <h1 class="h3 mb-1 text-gray-800">Reporte por Nave</h1>

                    <!-- Tabla de Naves -->
                    <?php echo $port->shipReport(); ?>

                    <div id="loader" style="display:none; text-align:center; padding:20px;">
                        <i class="fas fa-spinner fa-spin fa-3x" style="color: #4e73df;"></i></br> Cargando...
                    </div>
                </div>
                <!-- container-fluid -->
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
var exportExcel = function(nave, tipo, desde, hasta) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../controllers/shipsReportDownloadExcelController.php';
  form.style.display = 'none';

  const fields = {
    nave: nave,
    tipo: tipo,
    desde: desde,
    hasta: hasta
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

function showShipReport() {
  const MIN_TIME = 2000;

  $('#loader').show();
  $('#divShipReportTable').hide();

  setTimeout(function () {
    $('#loader').hide();
    $('#divShipReportTable').fadeIn();
  }, MIN_TIME);
}

$(document).ready(function() {
  showShipReport();

  $('#nave').select2({
    placeholder: 'Seleccione una motonave...',
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
          search: params.term, /* Lo que escribe el usuario */
          all: 1 /* Muestra todas las naves del sistema */
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });
});
</script>
