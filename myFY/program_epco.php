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

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Vista Formulario de Registro de Nuevo Usuario" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/favicon-256x256.png"/>
    <title>FYGroup | Planificación EPCO</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

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
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Planificación Naviera EPCO</h1>
                    <p class="mb-4">Acá puedes revisar las naves anunciadas para el Puerto de Coquimbo</p>

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
                                    <form class="form-container" id="epcoForm">
                                        <div class="form-group d-flex flex-wrap align-items-end justify-content-center">
                                            <div class="col-12 col-md-auto me-md-4 mb-3">
                                                <label for="dateFrom" class="text-gray-800 font-weight-bold">Desde</label>
                                                <input type="date" class="form-control form-control-user" id="dateFrom" name="dateFrom">
                                                <small class="text-danger" id="error-dateFrom"></small>
                                            </div>

                                            <div class="col-12 col-md-auto me-md-4 mb-3">
                                                <label for="dateTo" class="text-gray-800 font-weight-bold">Hasta</label>
                                                <input type="date" class="form-control form-control-user" id="dateTo" name="dateTo">
                                                <small class="text-danger" id="error-dateTo"></small>
                                            </div>

                                            <div class="col-12 col-md-auto me-md-4 mb-3">
                                                <button type="button" class="btn btn-primary btn-user" id="btnBuscar" onclick="loadEpcoProgram()">
                                                        <i class="fas fa-solid fa-search"></i> Buscar
                                                </button>

                                                <button type="button" class="btn btn-success btn-user" id="btnPrintEpcoProgram" onclick="printEpcoProgram()" disabled>
                                                        <i class="fas fa-solid fa-print"></i> Imprimir
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="card-body" id="divFrame">
                                        <!-- Frame del PDF -->
                                        <iframe id="framePdf" name="framePdf" frameborder="0"></iframe>
                                    </div>

                                    <div class="text-center">
                                        <img src="../images/logo-epco.png" class="logo-responsive">
                                        <h6 class="m-0 font-weight-bold text-center small text-primary">
                                            Powered by EPCO.
                                        </h6>
                                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
/* Conteo regresivo para cierre de sesion */
let inactivityTime = function () {
  let time;
  let warningTimeout = 30 * 60 * 1000; /* Minutos a convenir */
  let countdownTime = 30; /* 30 segundos para responder */

  function startTimer() {
    window.addEventListener('mousemove', resetTimer, false);
    window.addEventListener('keypress', resetTimer, false);
    window.addEventListener('click', resetTimer, false);
    window.addEventListener('scroll', resetTimer, false);
    resetTimer();
  }

  function logoutCountdown() {
    let timerInterval;
    Swal.fire({
      title: "¿Sigues ahí?",
      html: `Serás desconectado en <b></b> segundos por inactividad.`,
      icon: "warning",
      timer: countdownTime * 1000,
      timerProgressBar: true,
      showCancelButton: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonColor: '#4e73df',
      cancelButtonColor: '#d33',
      confirmButtonText: "¡Sigo aquí!",
      cancelButtonText: "Cerrar sesión",
      didOpen: () => {
        const b = Swal.getHtmlContainer().querySelector("b");
        timerInterval = setInterval(() => {
          b.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
        }, 1000);
      },
      willClose: () => {
        clearInterval(timerInterval);
      }
    }).then((result) => {
      if (result.isConfirmed) {
        resetTimer(); /* Usuario activo, reiniciar contador */
      } else {
        window.location = 'login.php?msg=sesion_expirada';
      }
    });
  }

  function resetTimer() {
    clearTimeout(time);
    time = setTimeout(logoutCountdown, warningTimeout);
  }

  startTimer();
};

window.onload = function () {
  inactivityTime();
};

function loadEpcoProgram() {
  const dateFrom = $('#dateFrom').val();
  const dateTo = $('#dateTo').val();

  if (!dateFrom || !dateTo) {
    Swal.fire({
      title: '¡Atención!',
      text: 'Debes ingresar ambas fechas para consultar la planificación naviera.',
      icon: 'info'
    });
    return;
  }

  const btn = $('#btnBuscar');
  const container = $('#divFrame');
  const btnPrint = $('#btnPrintEpcoProgram');

  btn.prop('disabled', true)
     .html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

  container.hide().empty();
  btnPrint.prop('disabled', true);

  $.ajax({
    url: '../controllers/programEpco.php',
    method: 'POST',
    data: { from: dateFrom, to: dateTo },
    dataType: 'html',
    timeout: 15000
  })
  .done(function(response) {
    const clean = response.trim();

    if (clean) {
      container.html(clean).fadeIn();
      btnPrint.prop('disabled', false);
    } else {
      Swal.fire({
        title: 'Sin resultados',
        text: 'No se encontró una planificación naviera para el rango de fechas.',
        icon: 'warning'
      });

      container.hide().empty();
      btnPrint.prop('disabled', true);
    }
  })
  .fail(function(xhr, status) {
    let msg = 'Ocurrió un error al consultar la planificación.';

    if (status === 'timeout') {
      msg = 'La consulta tardó demasiado (timeout).';
    }

    Swal.fire({
      title: 'Error',
      text: msg,
      icon: 'error'
    });

    container.hide().empty();
    btnPrint.prop('disabled', true);
  })
  .always(function() {
    btn.prop('disabled', false)
       .html('<i class="fas fa-search"></i> Buscar');
  });
}

var printEpcoProgram = function () {
  const contenido = document.getElementById('divFrame').innerHTML;
  if (!contenido.trim()) return;
  const ventana = window.open('', '', 'width=1200,height=800');
  ventana.document.write(contenido);
  ventana.document.close();
  ventana.focus();
  ventana.print();
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
        window.location = '<?php echo generateMkey('program_epco'); ?>';
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

var saveInfoUser = function() {
  const password = $('#password').val();
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
  let hasError = false;

  /* Revisa que la contraseña tenga los caracteres obligatorios */
  if (!regex.test(password)) {
    Swal.fire({
      title: 'Oops...',
      text: 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.',
      icon: 'error',
      cancelButtonColor: '#d33',
    });

    hasError = true;
  }

  if(!hasError){
    $.ajax({
      url: '../controllers/userSaveController.php',
      data: $('#editUserInfoForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          html: '¡Información actualizada con éxito! </br> Por motivos de seguridad deberás iniciar sesión nuevamente.',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = 'logout.php';
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al actualizar la información.',
          icon: 'error',
          cancelButtonColor: '#d33',
        });
      }
    });
  }
}

function actualizarReloj() {
  const ahora = new Date();

  const hora = ahora.toLocaleTimeString("es-CL", {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: false
  });

  const diaSemana = ahora.toLocaleDateString("es-CL", { weekday: "long" });
  const fecha = ahora.toLocaleDateString("es-CL", {
    day: "numeric",
    month: "long",
    year: "numeric"
  });

  document.getElementById("relojFecha").innerHTML = `
    <div style="display:flex; align-items:center; gap:10px;">
      <div style="font-size:20px; font-weight:bold;">
        ${hora}
      </div>
      |
      <div style="line-height:1.2;">
        <div>${diaSemana}</div>
        <div style="font-size:12px;">${fecha}</div>
      </div>
    </div>
  `;
}
setInterval(actualizarReloj, 1000);
actualizarReloj(); /* Primera llamada */
$('#divFrame').hide(); /* Oculta el div del frame al carga la pagina */
</script>
