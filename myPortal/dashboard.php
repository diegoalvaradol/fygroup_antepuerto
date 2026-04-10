<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$port = new outerPort();
$cfg = new cfg();
$user = new user();

$arrayDivision = get::getDivisionName();
$sideBarPortal = menu::sideBarPortal();
$tapBarPortal = menu::secondTapBarPortal();
$footer = menu::footerSSL();
$top = UIComponents::scrollToTopButton();

$infoCfg = json_decode($cfg->getInfo(1), true);
$admin = $user->isAdmin($_SESSION['user']['run']);
$releasedTime = new DateTime($infoCfg['released_date']);
$updateTime = new DateTime($infoCfg['update_date']);

/* Establece Limite de 30 minutos para el usuario pueda visitar el portal cliente */
$tiempoMaximo = 1800; /* 30 minutos */
if (time() - $_SESSION['last_session'] > $tiempoMaximo) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Dasboard" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/favicon-256x256.png"/>
    <title>Dashboard | Sistema Antepuerto</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php echo $sideBarPortal; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php echo $tapBarPortal; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert"> <i class="fa-solid fa-circle-info fa-lg"></i> <b style="font-size:100%;">¡Atención! : </b> Estimado usuario, cuenta con un tiempo de 30 minutos para visualizar el contenido del sistema, transcurrido dicho tiempo su sesión se cerrará.</div>
                    </div>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Resumen de Contenedores Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="text-sm font-weight-bold text-primary text-uppercase mb-1" style="text-align:center;">Contenedores</div>
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-6">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Contenedores</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php $totalContainer = $port->getTotalContainer($admin); ?>
                                                <?php echo $totalContainer; ?>
                                            </div>
                                        </div>
                                        <div class="col mr-6">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pallets</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php $totalPallets = $port->getTotalContainerPallets($admin); ?>
                                                <?php echo $totalPallets; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen de Termos Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="text-sm font-weight-bold text-success text-uppercase mb-1" style="text-align:center;">Termos</div>
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-6">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Camiones</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php $totalThermo = $port->getTotalThermo($admin); ?>
                                                <?php echo $totalThermo; ?>
                                            </div>
                                        </div>
                                        <div class="col mr-6">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pallets</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php $totalPallets = $port->getTotalPallets($admin); ?>
                                                <?php echo $totalPallets; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen de Arrivos Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="text-sm font-weight-bold text-warning text-uppercase mb-1" style="text-align:center;">Arrivos</div>
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-12">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Camiones Arrivados</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php $totalTrucks = $port->getTotalTrucks($admin); ?>
                                                <?php $trucksInAntepuerto = $port->getTotalTrucksInAnpuerto($admin); ?>
                                                <?php $trucksArrivedTrucks = $port->getTotalArrivedTrucks($admin); ?>

                                                <?php echo $totalTrucks; ?>
                                                </br>
                                                <small class="h5 mb-0 font-weight-bold text-success-800" style="font-size:small;color: green;">Solicitados: <?php echo $trucksArrivedTrucks; ?> </small><i class="fas fa-info-circle text-info" title="Solicitados" role="button" data-toggle="popover" data-trigger="hover focus" data-placement="right" data-content="Camiones solicitados por terminal."></i>
                                                <small class="h5 mb-0 font-weight-bold text-danger-800" style="font-size:small;color: red;">Pendientes: <?php echo $trucksInAntepuerto; ?> </small><i class="fas fa-info-circle text-info" title="Pendientes" role="button" data-toggle="popover" data-trigger="hover focus" data-placement="right" data-content="Camiones que se encuentran en antepuerto."></i>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen de Capacidad Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="text-sm font-weight-bold text-info text-uppercase mb-1" style="text-align:center;">Capacidad</div>
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ocupación Antepuerto</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                    <?php $percentUsage = $port->getPercentUsage($infoCfg['goals'], $admin); ?>
                                                    <?php print_r($percentUsage); ?>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress">
                                                        <div id="myProgressBar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $trucksInAntepuerto = $port->getTotalTrucksInAnpuerto($admin); ?>
                                            <small style="color:black; font-size:smaller;"><?php echo $trucksInAntepuerto . ' camiones de un total de ' . $infoCfg['goals'] . '.'; ?> </small>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-circle-notch fa-2x text-gray-300"></i>
                                        </div>
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

    <!-- Scroll to Top Button-->
    <?php echo $top; ?>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-2 px-3">
                    <h6 class="modal-title font-weight-bold mb-0" id="exampleModalLabel">¿Deseas cerrar sesión?</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span>×</span>
            </button>
                </div>
                <div class="modal-body">Selecciona 'Cerrar sesión' si realmente deseas hacerlo.</div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-dismiss="modal" >Cancelar</button>
                    <a class="btn btn-danger" href="logout.php" onclick="finishCountDown()"><i class='fas fa-solid fa-sign-out-alt'></i> Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

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
/* Inicializa el popover */
document.addEventListener('DOMContentLoaded', function () {
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'));
  popoverTriggerList.forEach(function (el) {
    new bootstrap.Popover(el);
  });
});

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

/* Porcentaje de ocupación del antepuerto */
setProgress(<?=$percentUsage;?>); //cambia a 30%

function setProgress(value) {
  const progressBar = document.getElementById('myProgressBar');
  const percentage = Math.min(Math.max(value, 0), 100); /* Entre 0 y 100 */

  if (percentage < 75) {
    progressBar . classList . add("bg-success");
  } else if (percentage >= 75 && percentage < 90) {
    progressBar . classList . add("bg-warning");
  } else if (percentage >= 90) {
    progressBar . classList . add("bg-danger");
  }

  progressBar.style.width = percentage + '%';
  progressBar.setAttribute('aria-valuenow', percentage);
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
var saveInfoUser = function() {
  const password = $('#password').val();
  var division = 'portal'; /* Division portal cliente */
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
      data: $('#editUserInfoForm').serialize() + '&division=' + encodeURIComponent(division),
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

/* Si ya hay un tiempo guardado en sessionStorage, úsalo. Si no, restablece a 30 segundos */
let tiempoLimite = sessionStorage.getItem('tiempoLimite') ? parseInt(sessionStorage.getItem('tiempoLimite')) : 1800; /* 30 segundos por defecto si no está en sessionStorage */

/* Función que actualiza el contador */
function actualizarConteo() {
  let minutos = Math.floor(tiempoLimite / 60);
  let segundos = tiempoLimite % 60;

  /* Muestra el tiempo en formato MM:SS */
  $('#countDownSession').html(`Tiempo restante: ${minutos}:${segundos < 10 ? '0' + segundos : segundos}`);

  if (tiempoLimite <= 0) {
    clearInterval(contador); /* Detiene el contador cuando llega a 0 */
  } else {
    tiempoLimite--;
    sessionStorage.setItem('tiempoLimite', tiempoLimite); /* Guarda el tiempo restante en sessionStorage */
  }
}

/* Puedes simular el inicio y cierre de sesión con botones: */
// startCountDown(); // Llama esta función cuando el usuario inicie sesión
// finishCountDown(); // Llama esta función cuando el usuario cierre sesión

/* Simulación de inicio de sesión */
function startCountDown() {
  sessionStorage.setItem('tiempoLimite', 1800); /* Restablece el temporizador a 30 segundos al iniciar sesión */
  localStorage.setItem('tiempoLimite', 1800); /* Si es el primer inicio, establece el tiempo por defecto en localStorage */
}

/* Simulación de cierre de sesión */
function finishCountDown() {
  sessionStorage.removeItem('tiempoLimite'); /* Elimina el tiempo de la sesión actual */
}

$(document).ready(function() {
  const contador = setInterval(actualizarConteo, 1000); /* Llama a la función cada segundo (1000ms) */

  setInterval(actualizarReloj, 1000);
  actualizarReloj(); /* Primera llamada */
  startCountDown();
});
</script>
