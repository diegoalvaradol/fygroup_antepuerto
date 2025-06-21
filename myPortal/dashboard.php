<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$db            = (new Database())->getConnection();
$port          = new outerPort($db);
$cfg           = new cfg($db);
$arrayDivision = get::getDivisionName();
$sideBarPortal = menu::sideBarPortal();
$tapBarPortal  = menu::mainTapBarPortal();
$footer        = menu::footerSSL();

$infoCfg      = json_decode($cfg->getInfo(1), true);
$releasedTime = new DateTime($infoCfg['released_date']);
$updateTime   = new DateTime($infoCfg['update_date']);

/* Establece Limite de 30 minutos para el usuario pueda visitar el portal cliente */
$tiempoMaximo = 1800; /* 30 minutos */
if (time() - $_SESSION['last_session'] > $tiempoMaximo) {
  session_unset();
  session_destroy();
  header("Location: login.php?timeout=1");
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
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>Dashboard | Sistema Antepuerto</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert<?php echo $sideBarPortal; ?>2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background:#293c74;">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <label style="color:white; align-content:center;"><i class="fas fa-solid fa-1x fa-clock"></i>&nbsp;</label>
                        <label class="ml-auto" id="relojFecha" style="color:white; align-content:center;"></label>
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <label style="color:white; align-content:center;"><i class="fas fa-solid fa-1x fa-clock"></i>&nbsp;</label>
                        <label class="ml-auto" id="countDownSession" style="color:white; align-content:center;"></label>
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-white-600 large">Bienvenido, <?php echo $_SESSION["user"]["name"]; ?>!</span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#userModal" style="color: #0483cd;">
                                    <i class="fas fa-user fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Perfil
                                </a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#licenceModal" style="color: #0483cd;">
                                    <i class="fas fa-copyright fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Licencia
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal" style="color: #cd1804;">
                                    <i class="fa-solid fa-right-from-bracket" style="color: #cd1804;"></i> Cerrar Sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="col-sm-12">
                        <div class="alert alert-info" role="alert"> <i class="fa-solid fa-circle-info fa-lg"></i> <b style="font-size:100%;">¡Atención! : </b> Estimado usuario, cuenta con un tiempo de 30 minutos para visualizar el contnigo del sistema, transcurrido dicho tiempo su sesión se cerrará.</div>
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
                                                <?php $totalContainer = $port->getTotalContainer(); ?>
                                                <?php echo $totalContainer; ?>
                                            </div>
                                        </div>
                                        <div class="col mr-6">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pallets</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php $totalPallets = $port->getTotalContainerPallets(); ?>
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
                                                <?php $totalThermo = $port->getTotalThermo(); ?>
                                                <?php echo $totalThermo; ?>
                                            </div>
                                        </div>
                                        <div class="col mr-6">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pallets</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php $totalPallets = $port->getTotalPallets(); ?>
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
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Arrivos</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php $totalTrucks        = $port->getTotalTrucks(); ?>
                                                <?php $trucksInAntepuerto = $port->getTotalTrucksInAnpuerto(); ?>
                                                <?php echo $totalTrucks . ' camiones.'; ?>
                                                
                                                <small class="h5 mb-0 font-weight-bold text-suceess-800" style="font-size:small;color: green;">Solicitados: <?php print_r($totalTrucks - $trucksInAntepuerto); ?> </small><i class="fas fa-info-circle text-info" title="Solicitados" role="button" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-content="Muestra el total de camiones que han arrivado a antepuerto y que ya han sido solicitados por terminal."></i>
                                                <small class="h5 mb-0 font-weight-bold text-danger-800" style="font-size:small;color: red;">Pendientes: <?php print_r($trucksInAntepuerto); ?> </small><i class="fas fa-info-circle text-info" title="Pendientes" role="button" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-content="Muestra el total de camiones que se encuentra en el antepuerto y que no han sido solicitados por terminal."></i>
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
                                                    <?php $percentUsage = $port->getPercentUsage($infoCfg['goals']); ?>
                                                    <?php print_r($percentUsage); ?>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress">
                                                        <div id="myProgressBar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $trucksInAntepuerto = $port->getTotalTrucksInAnpuerto(); ?>
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
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Deseas cerrar sesión?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
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

    <!-- Modal del perfil de usuario-->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Perfil de: <?php echo $_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"] . '.'; ?></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="row justify-content-center">
                    <h6 class="modal-title" id="exampleModalLabel">División: <?php echo $arrayDivision[$_SESSION["user"]["division"]]; ?></h6>
                </div>
                <div class="modal-body">
                    <form id="editUserInfoForm">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="alert alert-info" role="alert" style="font-size:85%;"> <i class="fa-solid fa-circle-info"></i> ¡Para guardar los cambios deberás ingresar tu contraseña actual!</div>
                            </div>
                            <div class="col-sm-12">
                                <label>RUN:</label>
                                <input type="text" class="form-control form-control-user" disabled value="<?php echo $_SESSION["user"]["run"]; ?>">
                                <label>Nombre:</label>
                                <input type="text" class="form-control form-control-user" id="name" name="name" value="<?php echo $_SESSION["user"]["name"]; ?>">
                                <label>Apellido:</label>
                                <input type="text" class="form-control form-control-user" id="lastname" name="lastname" value="<?php echo $_SESSION["user"]["last_name"]; ?>">
                                <label>Correo:</label>
                                <input type="email" class="form-control form-control-user" id="email" name="email" value="<?php echo $_SESSION["user"]["email"]; ?>">
                                <label>Contraseña:</label>
                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Ingresa tu contraseña actual">
                            </div>
                        </div>

                        <input type="hidden" id="run" name="run" value="<?php echo $_SESSION["user"]["run"]; ?>">
                        <button type="button" name="saveinfouser" class="btn btn-success btn-user btn-sm" onclick="saveInfoUser()"><i class='fas fa-solid fa-check-circle'></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal licencia del software-->
    <div class="modal fade" id="licenceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Licencia de Uso de Software</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="container mt-4 p-3 border rounded" style="background-color: #f9f9f9;">
                        <h3 class="text-center">Licencia de Uso de Software</h3>
                        <p><strong>Nombre del Software: </strong><?php echo $infoCfg['name']; ?></p>
                        <p><strong>Versión: </strong><?php echo $infoCfg['version']; ?></p>
                        <p><strong>Titular de los derechos: </strong><?php echo $infoCfg['author']; ?></p>
                        <p><strong>Fecha de Lanzamiento: </strong><?php echo $releasedTime->format('d-m-Y H:i'); ?></p>

                        <h5>1. OBJETO DE LA LICENCIA</h5>
                        <p>Esta licencia regula el uso del software denominado "Sistema Integral SSL", desarrollado en lenguaje PHP (backend), JavaScript y HTML (frontend), y utilizando MySQL como sistema de gestión de base de datos.</p>

                        <h5>2. CONCESIÓN DE LICENCIA</h5>
                        <p>El titular concede al usuario una licencia de uso no exclusiva, intransferible y revocable, para ejecutar, probar y operar el software con fines internos. El software no puede ser redistribuido ni modificado sin autorización expresa por escrito del titular.</p>

                        <h5>3. DERECHOS RESERVADOS</h5>
                        <p>Todos los derechos no expresamente concedidos en esta licencia son reservados por el titular. El código fuente, diseño y estructura de base de datos son propiedad intelectual protegida.</p>

                        <h5>4. RESTRICCIONES</h5>
                        El usuario se compromete a:
                        <ul>
                            <li>No copiar, modificar ni distribuir el software.</li>
                            <li>No revender ni sublicenciar el software.</li>
                            <li>No modificar o crear obras derivadas del software.</li>
                            <li>No usar el software en productos o servicios comerciales sin licencia extendida.</li>
                            <li>No realizar ingeniería inversa sobre el código fuente o la base de datos.</li>
                            <li>No usarlo en servicios que compitan con el titular.</li>
                        </ul>

                        <h5>5. PROPIEDAD INTELECTUAL</h5>
                        <p>Todo el contenido del software es propiedad exclusiva de <?php echo $infoCfg['author']; ?> y está protegido por las leyes de propiedad intelectual.</p>

                        <h5>6. LIMITACIÓN DE GARANTÍA</h5>
                        <p>El software se entrega "tal cual", sin garantías. El titular no se responsabiliza por daños derivados de su uso.</p>

                        <h5>7. TERMINACIÓN</h5>
                        <p>El incumplimiento de cualquiera de los términos de esta licencia resultará en su terminación inmediata, debiendo el usuario cesar el uso del software y eliminar todas sus copias.</p>

                        <h5>8. LEGISLACIÓN APLICABLE</h5>
                        <p>Esta licencia se regirá por las leyes de Chile. Cualquier conflicto será sometido a los tribunales competentes del país.</p>

                        <p class="mt-4"><strong>Firmado: </strong><br><?php echo $infoCfg['author']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../assets/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../assets/js/demo/chart-area-demo.js"></script>
    <script src="../assets/js/demo/chart-pie-demo.js"></script>

    <!-- Bootstrap JS (necesario para popover) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
/* Inicializa el popover */
document.addEventListener('DOMContentLoaded', function () {
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  popoverTriggerList.forEach(function (el) {
    new bootstrap.Popover(el);
  });
});

/* Conteo regresivo para cierre de sesion */
let inactivityTime = function () {
  let time;
  let warningTimeout = 60 * 60 * 1000; /* Minutos a convenir */
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
  const opcionesFecha = { year: 'numeric', month: 'long', day: 'numeric' };
  const fecha = ahora.toLocaleDateString('es-ES', opcionesFecha);
  const hora = ahora.toLocaleTimeString('es-ES');
  $('#relojFecha').html(`${fecha} - ${hora}`);
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
