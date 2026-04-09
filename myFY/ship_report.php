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
    <meta name="Vista Formulario de Registro de Nuevo Usuario" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/favicon-256x256.png"/>
    <title>FYGroup | Reporte de Naves</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Reporte de Naves</h1>

                    <!-- Tabla de Naves -->
                    <?php echo $port->shipReport(); ?>
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

    <!-- Scroll to Top Button-->
    <?php echo $top; ?>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="logoutModalLabel">¿Deseas cerrar sesión?</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
              <span>×</span>
            </button>
          </div>
          <div class="modal-body">
            Selecciona 'Cerrar sesión' si realmente deseas hacerlo.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
            <a class="btn btn-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de ajustes-->
    <div class="modal fade" id="goalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Configurar Capacidad de Antepuerto</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <form id="addGoalForm">
                        <div class="form-group row">
                            <div class="col-sm-12">
                            <label id="label-stay" style="color:darkorange;"></label>
                            <label>Capacidad:</label>
                            <input type="text" class="form-control form-control-user" id="goals" name="goals" value="<?php echo $infoCfg['goals']; ?>">
                            </div>
                        </div>

                        <button type="button" name="savenewgoals" class="btn btn-success btn-user btn-sm" onclick="saveNewGoals()"><i class='fas fa-solid fa-check-circle'></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal del perfil de usuario-->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Perfil de: <?php echo $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . '.'; ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar"><span>×</span></button>
                </div>
                <div class="row justify-content-center">
                    <h6 class="modal-title" id="exampleModalLabel">División: <?php echo $arrayDivision[$_SESSION['user']['division']]; ?></h6>
                </div>
                <div class="modal-body">
                    <form id="editUserInfoForm">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="alert custom-alert-info" role="alert" style="font-size:85%;"> <i class="fa-solid fa-circle-info"></i> ¡Para guardar los cambios deberás ingresar tu contraseña actual!</div>
                            </div>
                            <div class="col-sm-12">
                                <label>RUN:</label>
                                <input type="text" class="form-control form-control-user" disabled value="<?php echo $_SESSION['user']['run']; ?>">
                                <label>Nombre:</label>
                                <input type="text" class="form-control form-control-user" id="name" name="name" value="<?php echo $_SESSION['user']['name']; ?>">
                                <label>Apellido:</label>
                                <input type="text" class="form-control form-control-user" id="lastname" name="lastname" value="<?php echo $_SESSION['user']['last_name']; ?>">
                                <label>Correo:</label>
                                <input type="email" class="form-control form-control-user" id="email" name="email" value="<?php echo $_SESSION['user']['email']; ?>">
                                <label>Contraseña:</label>
                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Ingresa tu contraseña actual">
                            </div>
                        </div>

                        <input type="hidden" id="run" name="run" value="<?php echo $_SESSION['user']['run']; ?>">
                        <input type="hidden" id="division" name="division" value="<?php echo $_SESSION['user']['division']; ?>">
                        <button type="button" name="saveinfouser" class="btn btn-success btn-user btn-sm" onclick="saveInfoUser()"><i class='fas fa-solid fa-check-circle'></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal licencia del software-->
    <div class="modal fade" id="licenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Licencia de Uso de Software</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar"><span>×</span></button>
                </div>

                <div class="modal-body">
                    <div class="container mt-4 p-3 border rounded" style="background-color: #f9f9f9;">
                        <h4 class="text-center mb-3">Licencia de Uso</h4>

                        <p><strong>Software:</strong> <?php echo $infoCfg['name']; ?></p>
                        <p><strong>Compilación:</strong> <?php echo $infoCfg['compilation']; ?></p>
                        <p><strong>Versión:</strong> <?php echo $infoCfg['version']; ?></p>
                        <p><strong>Titular:</strong> <?php echo $infoCfg['author']; ?></p>
                        <p><strong>Lanzamiento:</strong> <?php echo $releasedTime->format('d-m-Y H:i'); ?></p>
                        <p><strong>Últ. actualización:</strong> <?php echo $updateTime->format('d-m-Y H:i'); ?></p>

                        <hr>

                        <h6>1. Objeto</h6>
                        <p>
                          Esta licencia regula el uso del sistema desarrollado en PHP, JavaScript y MySQL,
                          destinado a la gestión operativa del cliente.
                        </p>

                        <h6>2. Licencia</h6>
                        <p>
                          Se concede una licencia <strong>no exclusiva, intransferible y revocable</strong>,
                          únicamente para uso interno. Cualquier otro uso requiere autorización escrita.
                        </p>

                        <h6>3. Derechos</h6>
                        <p>
                          El código fuente, estructura y diseño son propiedad de
                          <strong><?php echo $infoCfg['author']; ?></strong>.
                        </p>

                        <h6>4. Restricciones</h6>
                        <ul>
                          <li>No copiar, modificar ni distribuir el software.</li>
                          <li>No revender ni sublicenciar.</li>
                          <li>No realizar ingeniería inversa.</li>
                          <li>No usar en servicios que compitan directamente.</li>
                        </ul>

                        <h6>5. Condiciones de Pago y Soporte</h6>
                        <p>
                          Todo desarrollo, modificación o soporte solicitado deberá ser pagado
                          según lo acordado previamente entre las partes.
                          El acceso a nuevas versiones y soporte depende del cumplimiento de pagos.
                        </p>

                        <h6>6. Bloqueo por Incumplimiento de Pago</h6>
                        <p>
                          En caso de <strong>mora o incumplimiento en el pago</strong> de desarrollos,
                          modificaciones o servicios asociados:
                        </p>
                        <ul>
                          <li>El titular podrá <strong>suspender total o parcialmente el sistema</strong>.</li>
                          <li>Se podrá limitar acceso a funcionalidades críticas.</li>
                          <li>Se podrá bloquear el acceso hasta regularizar la deuda.</li>
                          <li>No se garantiza continuidad operativa durante el periodo de incumplimiento.</li>
                        </ul>
                        <p>
                          La reactivación del sistema estará sujeta al pago total de la deuda pendiente.
                        </p>

                        <h6>7. Garantía</h6>
                        <p>
                          El software se entrega "tal cual", sin garantías de funcionamiento continuo.
                        </p>

                        <h6>8. Terminación</h6>
                        <p>
                          El incumplimiento de esta licencia implica su término inmediato y la obligación
                          de dejar de usar el sistema.
                        </p>

                        <h6>9. Legislación</h6>
                        <p>
                          Regido por las leyes de Chile.
                        </p>

                        <p class="mt-4">
                          <strong>Firmado:</strong><br>
                          <?php echo $infoCfg['author']; ?>
                        </p>
                    </div>
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
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
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

$(document).ready(function() {
  setInterval(actualizarReloj, 1000);
  actualizarReloj(); /* Primera llamada */

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

$(document).on('select2:open', function () {
  let searchField = document.querySelector('.select2-container--open .select2-search__field');
  if (searchField) {
    searchField.focus();
  }
});
</script>
