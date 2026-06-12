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
?>

<!-- JAVASCRIPT -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>FYGroup | Planificación TPC</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/fygroup.css" rel="stylesheet">

    <!-- Custom styles FYGroup-->
    <link rel="stylesheet" href="../assets/css/app.css">
    <script src="../assets/js/sidebar.js"></script>
</head>

    <style>
        iframe {
            width: 100%;
            height: 90vh;
            border: 1px solid #ccc;
            border-radius: 8px;
            display: none;
        }

        .errorLabel {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            margin-top: 10px;
            font-weight: bold;
            text-align: center;
        }
    </style>
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
                    <h1 class="h3 mb-1 text-gray-800">Itinerarios TPC</h1>
                    <p class="mb-4">Acá podrás consultar la planificación naviera actual y fechas pasadas del terminal.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <!-- Custom Text Color Utilities -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Formulario de Consulta</h6>
                                </div>

                                <div class="card-body">
                                    <form class="form-container" id="programForm">
                                        <div class="form-group d-flex flex-wrap align-items-end justify-content-center">
                                            <div class="col-12 col-md-auto me-md-4 mb-3">
                                                <label for="dateFrom" class="text-gray-800 font-weight-bold">Fecha</label>
                                                <input type="date" class="form-control form-control-user" id="datePicker" name="datePicker">
                                            </div>

                                            <div class="col-12 col-md-auto mb-3">
                                                <button type="button" class="btn btn-primary btn-user" id="btnBuscar" onclick="loadPDF()">
                                                    <i class="fas fa-search"></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <div id="divFrame">
                                        <!-- Div de contenido Dinamico -->
                                        <h6 class="m-0 font-weight-bold text-primary" id="tituloPlanificacion" style="text-align:center;"></h6>
                                        <hr>

                                        <!-- Frame del PDF -->
                                        <iframe id="framePdf" src="" frameborder="0"></iframe>

                                        <!-- Mensaje de Error -->
                                        <div id="errorMessage" class="errorLabel" style="display:none;">
                                            🚫 No se encontró la planificación naviera para la fecha seleccionada. <br>
                                            Por favor, intenta con otra fecha o vuelve más tarde.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mb-4">
                                <img src="../logos/logo-tpc.png" class="logo-responsive">
                                <h6 class="m-0 font-weight-bold text-center small text-primary">
                                    Powered by TPC.
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
/* Condiciona a que solo pueda mostrar la fecha de hoy como maximo */
const today = new Date().toLocaleString("en-CA", { timeZone: "America/Santiago", year: "numeric", month: "2-digit", day: "2-digit" });
document.getElementById('datePicker').setAttribute('max', today);

function formatDateFromInput(dateString) {
  const [year, month, day] = dateString.split("-");
  return `${day}-${month}-${year}`;
}

function formatDateUrl(dateString) {
  const [year, month] = dateString.split("-");
  return `${year}/${month}`;
}

function loadPDF() {
  const dateString = $('#datePicker').val();

  if (!dateString) {
    Swal.fire({
      title: '¡Atención!',
      text: 'Debes ingresar una fecha para consultar la planificación naviera.',
      icon: 'info'
    });
    return;
  }

  const date = new Date(dateString + 'T00:00:00');

  const dateName = date.toLocaleDateString('es-CL', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });

  const formattedDate = formatDateFromInput(dateString);
  const formattedDateUrl = formatDateUrl(dateString);

  const pdfUrl = `https://tpc.cl/wp-content/uploads/${formattedDateUrl}/Planificacion-Naviera-${formattedDate}.pdf`;

  const framePdf = document.getElementById('framePdf');

  // loading UI
  $('#btnBuscar')
    .prop('disabled', true)
    .html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

  $('#divFrame').hide();

  // manejar carga correcta
  framePdf.onload = function () {
    $('#divFrame').fadeIn().slideDown();
    framePdf.style.display = 'block';

    document.getElementById('tituloPlanificacion').innerHTML =
      `Planificación Naviera: ${dateName}`;

    $('#btnBuscar')
      .prop('disabled', false)
      .html('<i class="fas fa-search"></i> Buscar');
  };

  // manejar error real (PDF no existe)
  framePdf.onerror = function () {
    Swal.fire({
      title: 'Oops...',
      text: `No se encontró una planificación naviera para la fecha: ${formattedDate}.`,
      icon: 'error'
    });

    $('#btnBuscar')
      .prop('disabled', false)
      .html('<i class="fas fa-search"></i> Buscar');
  };

  // cargar PDF
  framePdf.src = pdfUrl;
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
        window.location = '<?php echo generateMkey('program_tpc'); ?>';
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

/* Escuchar el submit del formulario */
document.getElementById('programForm').addEventListener('submit', function(e) {
  e.preventDefault(); // No recargar página
  const selectedDateStr = document.getElementById('datePicker').value;
  loadPDF(selectedDateStr);
});

$('#divFrame').hide(); /* Oculta el div del frame al carga la pagina */
</script>
