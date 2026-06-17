<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

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
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>FYGroup | Itinerarios Puerto Maersk</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/fygroup.css" rel="stylesheet">

    <!-- Custom styles FYGroup-->
    <link rel="stylesheet" href="../assets/css/app.css">
    <script src="../assets/js/sidebar.js"></script>
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
                    <h1 class="h3 mb-1 text-gray-800">Itinerarios Puerto Maersk</h1>
                    <p class="mb-4">Itinerario de naves con recaladas confirmadas en puerto. <em>(Itinerario sujeto a cambios)</em></p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Formulario de Búsqueda</h6>
                                </div>

                                <div class="card-body">
                                    <!-- Custom Text Color Utilities -->
                                    <form class="form-container" id="portScheduleForm">
                                        <div class="form-group row justify-content-center">
                                            <!-- Exportador -->
                                            <div class="col-sm-2">
                                                <label for="dateFrom" class="text-gray-800 font-weight-bold">Desde</label>
                                                <input type="date" class="form-control form-control-user" id="dateFrom" name="dateFrom">
                                                <small class="text-danger" id="error-dateFrom"></small>
                                            </div>

                                            <div class="col-sm-2">
                                                <label for="dateTo" class="text-gray-800 font-weight-bold">Hasta</label>
                                                <input type="date" class="form-control form-control-user"id="dateTo" name="dateTo">
                                                <small class="text-danger" id="error-dateTo"></small>
                                            </div>

                                            <div class="col-sm-2">
                                                <label for="port" class="text-gray-800 font-weight-bold">Puerto</label>
                                                <select class="form-control select2 form-control-user" id="port" name="port">
                                                    <option value="-" selected>Seleccione un puerto...</option>
                                                    <option value="3PL6KRQMXKB5Q">Coquimbo</option>
                                                    <option value="2CBOYMUSVJHJT">Valparaíso</option>
                                                </select>
                                                <small class="text-danger" id="error-port"></small>
                                            </div>

                                            <div class="col-sm-2" style="margin-top: 30px;">
                                                <button type="button" class="btn btn-primary btn-user" id="btnBuscar" onclick="loadPortSchedules()">
                                                    <i class="fas fa-search"></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <div id="loader" style="display:none; text-align:center; padding:20px;">
                                        <i class="fas fa-spinner fa-spin fa-3x" style="color: #4e73df;"></i></br> Buscando itinerarios...
                                    </div>

                                    <!-- Tabla Puertos Confirmados -->
                                    <div id="maersk-port-schedules"></div>
                                </div>
                            </div>

                            <div class="text-center mb-4">
                                <img src="../logos/logo-maersk.png" class="logo-responsive">
                                <h6 class="m-0 font-weight-bold text-center small text-primary">
                                    Powered by Maersk.
                                </h6>
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

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/fygroup.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
var loadPortSchedules = function() {
  const $btn = $('#btnBuscar');
  const $div = $('#maersk-port-schedules');
  const $loader = $('#loader');

  const form = document.getElementById('portScheduleForm');
  const formData = new FormData(form);

  const dateFrom = $('#dateFrom').val();
  const dateTo = $('#dateTo').val();
  const port = $('#port').val();

  let hasError = false;

  $('small.text-danger').text('');
  $('.form-control-user').removeClass('is-invalid');
  $('.select2-container').removeClass('select2-error');

  for (const [key, value] of formData.entries()) {

    const input = form.querySelector(`[name="${key}"]`);
    const error = document.getElementById(`error-${key}`);

    const isSelect2 = input && $(input).hasClass('select2-hidden-accessible');
    const isEmpty = !String(value).trim() || value === '-';

    if (isEmpty) {
      if (error) {
        error.textContent = 'Este campo es obligatorio.';
      }

      if (isSelect2) {
        $(input).data('select2').$container.addClass('select2-error');
      } else if (input) {
        input.classList.add('is-invalid');
      }

      hasError = true;
    }
  }

  if (hasError) {
    return;
  }

  $.ajax({
    url: '../controllers/portScheduleMaersk.php',
    type: 'POST',
    data: {
      fromDate: dateFrom,
      toDate: dateTo,
      port: port
    },

    beforeSend() {
      $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Buscando...');
      $loader.show();
      $div.hide().empty();
    },

    success(html) {
      $div.html(html).show();
    },

    error(xhr, status, error) {
      console.error(error);

      $div.html(`
        <div class="alert alert-danger">
            Error al cargar los itinerarios.
        </div>
      `).show();
    },

    complete() {
      $loader.hide();
      $btn.prop('disabled', false).html('<i class="fas fa-search"></i> Buscar');
    }
  });
};

var bookVesselSystem = function(vessel, line, voyage, eta, etd, pol, pod, api) {
  Swal.fire({
    title: "¿Estás seguro de realizar esta acción?",
    text: "Crear nave de manera automática en el sistema a partir de los datos entregados por Itinerario de Maersk.",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#4CAF50",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, crear",
    cancelButtonText: "No, cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../controllers/shipController.php',
        type: 'POST',
        data: {
          vessel: vessel,
          line: line,
          voyage: voyage,
          eta: eta,
          etd: etd,
          pol: pol,
          pod: pod,
          api: api
        }
      }).done(function(x) {
        if (x === 'OK') {
          Swal.fire({
            title: '¡Éxito!',
            text: '¡Motonave creada con éxito!',
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          });
        } else if (x === 'NOOK') {
          Swal.fire({
            title: 'Oops...',
            text: 'Error al crear la nave.',
            icon: 'error'
          });
        } else {
          Swal.fire({
            title: 'Validación',
            text: x,
            icon: 'warning'
          });
        }
      });
    } else {
      Swal.fire({
        title: 'Cancelado',
        text: 'Operación cancelada por el usuario.',
        icon: 'info'
      });
    }
  });
}

var saveNewGoals = function() {
  $.ajax({
    url: '../controllers/configSaveController.php',
    data: $('#addGoalForm').serialize(),
    type: 'POST',
  }).done(function(x) {
    if(x == 'OK'){
      Swal.fire({
        title: '¡Éxito!',
        text: '¡Ocupación actualizada con éxito!',
        icon: 'success',
        confirmButtonColor: '#4CAF50'
      }).then((result) => {
        window.location = '<?php echo generateMkey('port_schedule_maersk'); ?>';
      });
    } else {
      Swal.fire({
        title: 'Oops...',
        text: 'Error al actualizar la ocupación.',
        icon: 'error',
        cancelButtonColor: '#d33',
      });
    }
  });
}
</script>
