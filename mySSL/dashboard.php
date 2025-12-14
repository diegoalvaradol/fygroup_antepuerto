<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db   = (new Database())->getConnection();
$port = new outerPort($db);
$cfg  = new cfg($db);
$user = new user($db);

$infoCfg       = json_decode($cfg->getInfo(1), true);
$admin         = $user->isAdmin($_SESSION["user"]["run"]);
$releasedTime  = new DateTime($infoCfg['released_date']);
$updateTime    = new DateTime($infoCfg['update_date']);
$arrayDivision = get::getDivisionName();
$sideBarSSL    = menu::sideBarSSL();
$mainTapBarSSL = menu::mainTapBarSSL();
$footer        = menu::footerSSL();
$top           = UIComponents::scrollToTopButton();
$whatsAppBtn   = UIComponents::whatsappChatBox();
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
    <title>SSL | Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <style>
      body {
        background: #f7f9fc;
      }

      /* Tarjetas */
      .card {
        border: none;
        border-radius: 1rem;
        transition: transform .2s ease, box-shadow .2s ease;
        background: #fff;
      }
      .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      }

      /* Iconos grandes */
      .card i {
        padding: 12px;
        border-radius: 50%;
        background: rgba(0,0,0,0.05);
      }

      /* Progress bar animada */
      .progress-bar {
        background: linear-gradient(90deg, #36d1dc, #5b86e5);
        transition: width 1s ease-in-out;
      }

      /* Botones */
      .btn {
        border-radius: 30px;
        transition: all .3s ease;
      }
      .btn:hover {
        transform: scale(1.05);
      }

      /* Modal más elegante */
      .modal-content {
        border-radius: 1rem;
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
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
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Contenedores -->
                        <div class="col-xl-2 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body">
                              <div class="text-center">
                                <i class="fas fa-boxes-stacked fa-2x text-primary mb-2"></i>
                                <h6 class="text-primary text-uppercase mb-3">Contenedores</h6>
                              </div>
                              <div class="d-flex justify-content-between px-3">
                                <div class="text-center">
                                  <div class="text-muted small">Contenedores</div>
                                  <div class="h5 font-weight-bold text-dark"><?=$port->getTotalContainer($admin);?></div>
                                </div>
                                <div class="text-center">
                                  <div class="text-muted small">Pallets</div>
                                  <div class="h5 font-weight-bold text-dark"><?=$port->getTotalContainerPallets($admin);?></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Termos -->
                        <div class="col-xl-2 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body">
                              <div class="text-center">
                                <i class="fas fa-snowflake fa-2x text-success mb-2"></i>
                                <h6 class="text-success text-uppercase mb-3">Termos</h6>
                              </div>
                              <div class="d-flex justify-content-between px-3">
                                <div class="text-center">
                                  <div class="text-muted small">Camiones</div>
                                  <div class="h5 font-weight-bold text-dark"><?=$port->getTotalThermo($admin);?></div>
                                </div>
                                <div class="text-center">
                                  <div class="text-muted small">Pallets</div>
                                  <div class="h5 font-weight-bold text-dark"><?=$port->getTotalPallets($admin);?></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Arrivos -->
                        <div class="col-xl-3 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body">
                              <div class="text-center">
                                <i class="fas fa-truck-moving fa-2x text-warning mb-2"></i>
                                <h6 class="text-warning text-uppercase mb-3">Arrivos</h6>
                              </div>
                              <div class="text-center">
                                <?php $totalTrucks         = $port->getTotalTrucks($admin); ?>
                                <?php $trucksInAntepuerto  = $port->getTotalTrucksInAnpuerto($admin); ?>
                                <?php $trucksArrivedTrucks = $port->getTotalArrivedTrucks($admin); ?>
                                <div class="text-muted small">Total Camiones Arrivados</div>
                                <div class="h5 font-weight-bold text-dark"><?=$totalTrucks?></div>

                                <div class="mb-1">
                                  <small class="text-success font-weight-bold">
                                    Solicitados: <?=$trucksArrivedTrucks?>
                                    <i class="fas fa-info-circle text-info" role="button"
                                      data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right"
                                      data-bs-content="Camiones solicitados por terminal.">
                                    </i>
                                  </small>
                                </div>
                                <div>
                                  <small class="text-danger font-weight-bold">
                                    Pendientes: <?=$trucksInAntepuerto?>
                                    <i class="fas fa-info-circle text-info" role="button"
                                      data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right"
                                      data-bs-content="Camiones que se encuentran en antepuerto.">
                                    </i>
                                  </small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Capacidad -->
                        <div class="col-xl-2 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body">
                              <div class="text-center mb-3">
                                <i class="fas fa-chart-pie fa-2x text-info mb-2"></i>
                                <h6 class="text-info text-uppercase">Ocupación</h6>
                              </div>
                              <div class="text-center mb-2">
                                <div class="text-muted small">Ocupación Antepuerto</div>
                                <div class="h5 font-weight-bold text-dark">
                                  <?php $percentUsage = $port->getPercentUsage($infoCfg['goals'], $admin); ?>
                                  <?php echo $percentUsage . '%'; ?>
                                </div>
                              </div>
                              <div class="progress mb-2" style="height: 8px;">
                                <div id="myProgressBar" class="progress-bar bg-info" role="progressbar"
                                  style="width: <?=$percentUsage?>%;" aria-valuenow="<?=$percentUsage?>" aria-valuemin="0" aria-valuemax="100">
                                </div>
                              </div>
                              <small class="d-block text-muted text-center">
                                <?=$port->getTotalTrucksInAnpuerto($admin)?> camiones de un total de <?=$infoCfg['goals']?>.
                              </small>
                            </div>
                          </div>
                        </div>

                        <!-- Camiones Enviados -->
                        <div class="col-xl-3 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body">
                              <div class="text-center mb-3">
                                <i class="fas fa-anchor-circle-check fa-2x text-danger mb-2"></i>
                                <h6 class="text-danger text-uppercase">Últimos Camiones Enviados</h6>
                              </div>
                              <small class="d-block text-muted text-center">
                                Muestra los últimos 5 camiones enviados.
                              </small>
                              <small class="d-block text-dark text-center">
                                <?=$port->getLastSentTrucks();?>
                              </small>
                            </div>
                          </div>
                        </div>

                        <!-- Gráfico de Camiones Por Día -->
                        <?php if ($admin): ?>
                          <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card bg-light shadow-sm h-100">
                              <div class="card-body">
                                <div class="text-center mb-3">
                                  <i class="fas fa-chart-column fa-2x text-info mb-2"></i>
                                  <h6 class="text-info text-uppercase">Movimientos por Día</h6>
                                </div>
                                <div class="form-group row justify-content-center">
                                  <div class="col-12 col-md-auto me-md-4 mb-3">
                                    <label for="fechaInicio" class="text-dark font-weight-bold">Desde</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaInicio">
                                  </div>
                                  <div class="col-12 col-md-auto me-md-4 mb-3">
                                    <label for="fechaFin" class="text-dark font-weight-bold">Hasta</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaFin">
                                  </div>
                                </div>
                                <div class="row justify-content-center">
                                  <div class="col-12">
                                    <div id="divGraficoCamiones" style="position: relative;">
                                      <p id="mensajeSinDatos" style="display: none; margin: 0;">
                                        <i class="fas fa-exclamation-circle" style="margin-right:0.5rem;"></i>
                                        No hay datos disponibles para las fechas seleccionadas. Por favor ajusta el rango e intenta nuevamente.
                                      </p>
                                      <canvas id="graficoCamiones"></canvas>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php endif; ?>
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


    <!-- Info System Modal-->
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Acerca del Sistema</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
              <span>×</span>
            </button>
                </div>
                <div class="modal-body">
                    <small><b>Nombre: </b><?php echo $infoCfg['name']; ?></small>
                    <br>
                    <small><b>Versión: </b><?php echo $infoCfg['version']; ?></small>
                    <br>
                    <small><b>Compilación: </b><?php echo $infoCfg['compilation']; ?></small>
                    <br>
                    <small><b>Lanzamiento: </b><?php echo $releasedTime->format('d-m-Y H:i'); ?></small>
                    <br>
                    <small><b>Últ. Actualización: </b><?php echo $updateTime->format('d-m-Y H:i'); ?></small>
                    <br>
                    <small>
            </div>
        </div>
    </div>

    <!-- Modal de ajustes-->
    <div class="modal fade" id="goalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Configurar Capacidad de Antepuerto</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
              <span>×</span>
            </button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Perfil de: <?php echo $_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"] . '.'; ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
              <span>×</span>
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
                        <input type="hidden" id="division" name="division" value="<?php echo $_SESSION["user"]["division"]; ?>">
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
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
              <span>×</span>
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

    <!-- Bootstrap JS (necesario para popover) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chat flotante expandible estilo WhatsApp - Responsive -->
    <?php echo $whatsAppBtn; ?>
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
    progressBar.classList.add("bg-success");
  }else if(percentage >= 75 && percentage < 90){
    progressBar.classList.add("bg-warning");
  }else if(percentage >= 90){
    progressBar.classList.add("bg-danger");
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

/* Dibuja el gráfico de barras */
<?php if ($admin): ?>
const ctx = document.getElementById('graficoCamiones').getContext('2d');
let chart;

const today = new Date().toLocaleString("en-CA", { timeZone: "America/Santiago", year: "numeric", month: "2-digit", day: "2-digit" });
document.getElementById('fechaInicio').setAttribute('max', today);
document.getElementById('fechaFin').setAttribute('max', today);

/* Obtener datos desde PHP */
async function cargarDatos(fechaInicio, fechaFin) {
  if (fechaInicio > fechaFin) {
    Swal.fire({
      title: 'Error',
      text: 'La fecha de inicio no puede ser posterior a la fecha de fin.',
      icon: 'error',
      confirmButtonColor: '#d33'
    });
    return [];
  }

  const res = await fetch('../controllers/getTrucksPerDayController.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ fechaInicio, fechaFin })
  });
  return await res.json();
}

/* Dibujar el gráfico */
async function filtrarYActualizar(fechaInicio, fechaFin) {
  const datos = await cargarDatos(fechaInicio, fechaFin);

  const mensaje = document.getElementById('mensajeSinDatos');
  const canvas = document.getElementById('graficoCamiones');
  const divCanvas = document.getElementById('divGraficoCamiones');

  if (!datos.length) {
    mensaje.style.display = 'flex';
    mensaje.style.justifyContent = 'center';
    mensaje.style.alignItems = 'center';
    mensaje.style.height = '100%';
    mensaje.style.color = '#b00020';
    mensaje.style.fontSize = '1rem';

    canvas.style.display = 'none';
    divCanvas.style.height = '120px';
    if (chart) chart.destroy();
    return;
  }

  mensaje.style.display = 'none';
  canvas.style.display = 'block';
  divCanvas.style.height = '400px';

  const labels = datos.map(d => d.Fecha);

  const data = {
    labels: labels,
    datasets: [
      {
        label: 'Ingresados',
        data: datos.map(d => d.Ingresos),
        backgroundColor: 'rgba(75, 192, 192, 0.7)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      },
      {
        label: 'Despachados',
        data: datos.map(d => d.Egresos),
        backgroundColor: 'rgba(255, 99, 132, 0.7)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      }
    ]
  };

  const config = {
    type: 'bar',
    data: data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        datalabels: {
          align: 'top',
          anchor: 'end',
          font: { weight: 'bold' },
          color: '#000'
        },
        tooltip: {
          callbacks: {
            title: ctx => ctx[0].label,
            label: ctx => `${ctx.dataset.label}: ${ctx.raw}`
          }
        }
      },
      scales: {
        x: {
          title: { display: true, text: 'Fecha' }
        },
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Cantidad de Camiones' },
          ticks: { stepSize: 1, precision: 0 }
        }
      }
    },
    plugins: [ChartDataLabels]
  };

  if (chart) chart.destroy();
  chart = new Chart(ctx, config);
}

/* Setear valores por defecto (últimos 7 días) */
function formatDate(date) {
  return date.toISOString().split('T')[0];
}

const hoy = new Date();
const hace7dias = new Date();
hace7dias.setDate(hoy.getDate() - 7);

document.getElementById('fechaInicio').value = formatDate(hace7dias);
document.getElementById('fechaFin').value = formatDate(hoy);

/* Cargar gráfico inicial */
filtrarYActualizar(formatDate(hace7dias), formatDate(hoy));

/* Eventos de filtros */
document.getElementById('fechaInicio').addEventListener('change', e => {
  const fin = document.getElementById('fechaFin').value;
  filtrarYActualizar(e.target.value, fin);
});
document.getElementById('fechaFin').addEventListener('change', e => {
  const inicio = document.getElementById('fechaInicio').value;
  filtrarYActualizar(inicio, e.target.value);
});
<?php endif; ?>

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
        window.location = 'dashboard.php';
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

$(document).ready(function() {
  setInterval(actualizarReloj, 1000);
  actualizarReloj(); /* Primera llamada */
});
</script>