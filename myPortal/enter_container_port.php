<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$db   = (new Database())->getConnection();
$port = new outerPort($db);
$cfg  = new cfg($db);

$infoCfg = json_decode($cfg->getInfo(1), true);

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
    <meta name="Vista Formulario de Registro de Nuevo Usuario" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>Portal - SSL | Listado de Contenedores</title>

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
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color:#293c74;">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <img src="../img/ssl-logo-azul.png" style="width:100%;">
            </a>

            <!-- Heading -->
            <div class="sidebar-heading">Sistema Antepuerto</div>

            <!-- Nav Item - Antepuerto Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAntepuerto" aria-expanded="true" aria-controls="collapseAntepuerto">
                    <i class="fas fa-fw fa-truck"></i>
                    <span>Antepuerto</span>
                </a>
                <div id="collapseAntepuerto" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Items:</h6>
                        <a class="collapse-item" href=<?php echo generateMkey('enter_container_port', 'myPortal');?> >Ingreso Contenedores</a>
                        <a class="collapse-item" href=<?php echo generateMkey('enter_thermo_port', 'myPortal');?> >Ingreso Termos</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Text Buttom (Sidebar) -->
            <div class="d-flex flex-column h-100">
                <div class="text-center d-none d-md-inline mt-auto">
                    <hr class="sidebar-divider">
                    <small><?php echo $infoCfg['name']; ?></small>
                    <br>
                    <small><b>Versión: </b><?php echo $infoCfg['version']; ?></small>
                </div>
            </div>
        </ul>
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

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-white-600 large">Bienvenido, <?php echo $_SESSION["user"]["name"]; ?>!</span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
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
                    <h1 class="h3 mb-1 text-gray-800">Reporte de Contenedores</h1>

                    <!-- Tabla de Contenedores -->
                    <?php $tableContainer = $port->getTableContainer(); ?>
                    <?php echo $tableContainer; ?>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <?php echo $infoCfg['mark']; ?> 2025</span>
                    </div>
                </div>
            </footer>
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
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Cancelar</button>
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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

function actualizarReloj() {
  const ahora = new Date();
  const opcionesFecha = { year: 'numeric', month: 'long', day: 'numeric' };
  const fecha = ahora.toLocaleDateString('es-ES', opcionesFecha);
  const hora = ahora.toLocaleTimeString('es-ES');
  $('#relojFecha').html(`${fecha} - ${hora}`);
}

var exportExcel = function(nave, condicion, exportador) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../controllers/containerDownloadExcelController.php';
  form.style.display = 'none';

  const fields = {
    nave: nave,
    condicion: condicion,
    exportador: exportador
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
          current: 1 /* Muestra las naves que cuentan con una ETA mayor a la fecha actual */
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

  $('#patente').select2({
    placeholder: 'Seleccione una patente...',
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/carPlateJsonController.php',
      method: 'POST',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          search: params.term /* Lo que escribe el usuario */
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

$(document).on('select2:open', function () {
  let searchField = document.querySelector('.select2-container--open .select2-search__field');
  if (searchField) {
    searchField.focus();
  }
});
</script>