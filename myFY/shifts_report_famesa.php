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
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>FYGroup | Reporte de Turnos</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
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
                    <h1 class="h3 mb-1 text-gray-800">Reporte Turnos Famesa</h1>
                    <p class="mb-4">Acá puedes visualizar la carga movilizada por cada turno.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <!-- Custom Text Color Utilities -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Reporte Turnos Famesa</h6>
                                </div>

                                <div class="card-body">
                                    <form class="form-container" id="shiftsReportForm">
                                        <div class="form-group d-flex flex-wrap align-items-end justify-content-center">
                                            <div class="col-12 col-md-auto me-md-4 mb-3">
                                                <label for="dateForm" class="text-gray-800 font-weight-bold">Fecha</label>
                                                <input type="text" class="form-control form-control-user" id="dateForm" name="dateForm">
                                                <small class="text-danger" id="error-dateForm"></small>
                                            </div>

                                            <div class="col-12 col-md-auto me-md-4 mb-3">
                                                <label for="shifts" class="text-gray-800 font-weight-bold">Turno</label>
                                                <select class="form-control select2 form-control-user" id="shifts" name="shifts">
                                                    <option value="-">Seleccione un turno...</option>
                                                    <?php foreach ((object) get::arrayShiftsFamesa() as $k => $v): ?>
                                                            <option value="<?= $k ?>"><?= $v ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <small class="text-danger" id="error-shifts"></small>
                                            </div>

                                            <div class="col-12 col-md-auto me-md-4 mb-3">
                                                <button type="button" class="btn btn-primary btn-user" id="btnBuscar" onclick="loadShiftsReportFamesa()">
                                                    <i class="fas fa-search"></i> Buscar
                                                </button>

                                                <button type="button" class="btn btn-success btn-user" id="btnPrintShiftsReportFamesa" onclick="printShiftsReportFamesa()" disabled>
                                                    <i class="fas fa-print"></i> Imprimir
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Div de contenido Dinamico -->
                                    <div class="d-flex justify-content-center mt-3">
                                        <div class="card border-left-primary shadow-sm" style="max-width:300px; display:none;" id="shiftCardMini">
                                            <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">
                                                <span class="small"><b>Información:</b></span>
                                                &nbsp;
                                                <span class="badge badge-primary" id="shiftTextMini"></span>
                                            </div>
                                        </div>
                                    </div>
                                    </br>

                                    <!-- Tabla Reporte de Turnos -->
                                    <div id="shiftsDiv"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
function loadShiftsReportFamesa() {
  const $btn = $('#btnBuscar');
  const $div = $('#shiftsDiv');
  const date = $('#dateForm').val();
  const shifts = $('#shifts').val();
	const textShifts = $('#shifts option:selected').text();

	/* Separar los componentes */
	const [day, month, year] = date.split('-').map(Number);

	/* Crear la fecha como local, no UTC */
	const dateTitle = new Date(day, month - 1, year); /* mes va de 0 a 11 */

	/* Obtener día de la semana en español */
	const dateName = dateTitle.toLocaleDateString('es-CL', {day: 'numeric', month: 'long', year: 'numeric', timeZone: 'America/Santiago'});

  if (!date || shifts === '-' || !shifts) {
    Swal.fire({
      title: 'Datos incompletos',
      text: 'Debe seleccionar fecha y turno.',
      icon: 'warning'
    });
    return;
  }

  $.ajax({
    url: '../controllers/famesaShiftsReportController.php',
    type: 'POST',
    dataType: 'html',
    data: { date, shifts },

    beforeSend() {
      $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
    },

    success(response) {
      const clean = response.trim();

      if (clean.length > 0) {
        $div.html(clean).fadeIn();

				$('#shiftTextMini').html(`Turno: ${textShifts} </br> Fecha: ${dateName} </br> Horario: ${shifts}`).css("font-size", "smaller");
				$('#shiftCardMini').fadeIn(150);
				$('#btnPrintShiftsReportFamesa').prop('disabled', false);
      } else {
        $div.hide().empty();
				$('#shiftCardMini').fadeOut(150);
				$('#btnPrintShiftsReportFamesa').prop('disabled', true);

        Swal.fire({
          title: 'Sin resultados',
          text: 'No se encontró planificación para los filtros seleccionados.',
          icon: 'info'
        });
      }
    },

    error(xhr) {
      console.error(xhr.responseText);
			$('#btnPrintShiftsReportFamesa').prop('disabled', true);
      Swal.fire({
        title: 'Error',
        text: 'Error al consultar la información.',
        icon: 'error'
      });
    },

    complete() {
      $btn.prop('disabled', false).html('<i class="fas fa-search"></i> Buscar');
    }
  });
}

let fpInstance = null;

function loadDatePicker() {
	fetch('../controllers/famesaDateWhithMovs.php').then(res => res.json()).then(data => {
		const fechasValidas = Array.isArray(data) ? data : [];

		if (fpInstance) {
			fpInstance.destroy();
		}

		fpInstance = flatpickr("#dateForm", {
			dateFormat: "Y-m-d",
			enable: fechasValidas,

			locale: {
				...flatpickr.l10ns.es,
				firstDayOfWeek: 1
			},

			onDayCreate: function (dObj, dStr, fp, dayElem) {
				const fecha = dayElem.dateObj.toLocaleDateString('en-CA');

				if (fechasValidas.includes(fecha)) {
					dayElem.style.background = "#28a745";
					dayElem.style.color = "#fff";
					dayElem.style.borderRadius = "50%";
				}
			}
		});
	})
	.catch(err => console.error(err));
}

document.addEventListener("DOMContentLoaded", loadDatePicker);

var printShiftsReportFamesa = function () {
  const contenido = document.getElementById('shiftsDiv').innerHTML;
  if (!contenido.trim()) return;
  const ventana = window.open('', '', 'width=1200,height=800');
  ventana.document.write(contenido);
  ventana.document.close();
  ventana.focus();
  ventana.print();
}
</script>
