<?php
  require_once __DIR__ . '/../config/auth.php';
  require_once __DIR__ . '/../config/includes.php';

  $famesa = new famesa();
  $cfg    = new cfg();
  $user   = new user();

  $infoCfg       = json_decode($cfg->getInfo(1), true);
  $admin         = $user->isAdmin($_SESSION["user"]["run"]);
  $releasedTime  = new DateTime($infoCfg['released_date']);
  $updateTime    = new DateTime($infoCfg['update_date']);
  $arrayDivision = get::getDivisionName();
  $sideBarSSL    = menu::sideBarSSL();
  $mainTapBarSSL = menu::mainTapBarSSL();
  $footer        = menu::footerSSL();
  $top           = UIComponents::scrollToTopButton();
  $paginaActual  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
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
    <title>FYGroup | Ingreso Cámiones</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Ingreso Camiones Famesa</h1>

                    <div class="col-sm-12">
                      <div class="alert alert-info" role="alert"><i class="fa-solid fa-circle-info"></i>
                        <b>¡Atención! : </b> Todos aquellos camiones que superen un día (1) de estadía en antepuerto serán destacados de color rojo.
                      </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg-12">
                            <!-- Custom Text Color Utilities -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Formulario de Ingreso</h6>
                                </div>

                                <div class="card-body">
                                        <form class="form-container" id="inTruckForm">
                                            <div class="form-inline mb-3">
                                                <label for="countervessel" class="mr-2 text-gray-800 font-weight-bold">N° de Camión</label>
                                                <input type="text" class="form-control form-control-user" id="countervessel" name="countervessel" placeholder="Ingresa número" style="max-width: 150px;">
                                                <small class="text-danger" id="error-countervessel"></small>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="vessel" class="text-gray-800 font-weight-bold">Motonave</label>
                                                    <select class="form-control select2 form-control-user" id="vessel" name="vessel">
                                                        <option value="-">Seleccione una motonave...</option>
                                                    </select>
                                                    <i class="fas fa-info-circle text-info" role="right" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-content="Solo muestra aquellas motonaves que no hayan zarpado de puerto."></i>
                                                    <small class="text-danger" id="error-vessel"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for='voyage' class='text-gray-800 font-weight-bold'>Información de Motonave</label>
                                                    </br>
                                                    <small class="text-black" id="info-vessel"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="carplatetruck" class="text-gray-800 font-weight-bold">Patente Camión</label>
                                                    <select class="form-control select2 form-control-user" id="carplatetruck" name="carplatetruck">
                                                        <option value="-">Seleccione una patente...</option>
                                                    </select>
                                                    <small class="text-danger" id="error-carplatetruck"></small>
                                                </div>


                                                <div class="col-sm-6">
                                                    <label for="carplateramp" class="text-gray-800 font-weight-bold">Patente Rampla</label>
                                                    <select class="form-control select2 form-control-user" id="carplateramp" name="carplateramp">
                                                        <option value="-">Seleccione una patente...</option>
                                                    </select>
                                                    <small class="text-danger" id="error-carplateramp"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="guidenumber" class="text-gray-800 font-weight-bold">N° de Guía</label>
                                                    <input type="text" class="form-control form-control-user" id="guidenumber" name="guidenumber" placeholder="N° de Guía (Ej: 123 ó 123, 456)">
                                                    <small class="text-danger" id="error-guidenumber"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="maxibagsquantity" class="text-gray-800 font-weight-bold">MaxiBags</label>
                                                    <input type="number" class="form-control form-control-user" id="maxibagsquantity" name="maxibagsquantity" min="0" max="40" step="1" oninput="validarMaximo(this)" value="20" placeholder="N° de MaxiBags">
                                                    <small class="text-danger" id="error-maxibagsquantity"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label class="text-gray-800 font-weight-bold">Categoría</label>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="category" id="cat1" value="1">
                                                        <label class="form-check-label" for="cat1">Cat. 1</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="category" id="cat2" value="2">
                                                        <label class="form-check-label" for="cat2">Cat. 2</label>
                                                    </div>

                                                    <small class="text-danger" id="error-category"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="arrivaldateport" class="text-gray-800 font-weight-bold">Fecha y Hora de Entrada Puerto</label>
                                                    <input type="datetime-local" class="form-control form-control-user" id="arrivaldateport" name="arrivaldateport">
                                                    <small class="text-danger" id="error-arrivaldateport"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                   <label for="observations" class="text-gray-800 font-weight-bold">Observaciones</label>
                                                    <input type="text" class="form-control form-control-user" id="observations" name="observations" placeholder="Observaciones">
                                                    <small class="text-danger" id="error-observations"></small>
                                                </div>
                                            </div>

                                            <input type="hidden" id="departuredateport" name="departuredateport" value="0">
                                            <input type="hidden" id="arrivaldatedeposit" name="arrivaldatedeposit" value="0">
                                            <input type="hidden" id="departuredatedeposit" name="departuredatedeposit" value="0">

                                            <input type="hidden" id="truckId" name="truckId" value="0">
                                            <input type="hidden" id="isUpdate" name="isUpdate" value="0">
                                            <input type="hidden" id="createdby" name="createdby" value="<?php echo $_SESSION["user"]["run"]; ?>">
                                            <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
                                            <button id="loadBtn" type="button" class="btn btn-primary btn-sm btn-user" onclick="saveInTruck()">
                                              <span id="loadBtnText"><i class="fas fa-solid fa-check-circle"></i> Guardar</span>
                                              <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            </button>
                                            <button type='button' class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-undo'></i> Limpiar</button>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Listado de Termos -->
                    <?php echo $famesa->getTableTrucksFamesa(); ?>
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
                    <h5 class="modal-title" id="exampleModalLabel">Perfil de: <?php echo $_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"] . '.'; ?></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar"><span>×</span></button>
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

    <!-- SALIDA PUERTO -->
    <div id="modalAddHourDeparturePort" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="addHourDeparturePort" class="modal-box" style="display:none; position:fixed; width:30%; top:20%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
      <h4 id="h4-departure-hour-port"></h4>
      <h6 id="h6-carplates-departure-port"></h6>

      <form id="formDeparturePort">
        <div class="form-group row">
          <div class="col-sm-12">
            <label id="label-stay-departure-port"></label><br>
            <label>Hora salida:</label>
            <input type="datetime-local" name="departure_date_port" class="form-control">
            <small class="text-danger" id="error-departure_date_port"></small>
          </div>
        </div>

        <input type="hidden" name="id">
        <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
        <button type="button" name="savechanges" class="btn btn-success btn-user btn-sm" onclick="saveChanges('#formDeparturePort')"><i class='fas fa-solid fa-check-circle'></i> Guardar</button>
        <button type="button" name="closemodal" class="btn btn-danger btn-user btn-sm"  onclick="closeModal()">Cancelar</button>
      </form>
    </div>

    <!-- ENTRADA DEPÓSITO -->
    <div id="modalAddHourArrivalDeposit" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="addHourArrivalDeposit" class="modal-box" style="display:none; position:fixed; width:30%; top:20%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
      <h4 id="h4-arrival-hour-deposit"></h4>
      <h6 id="h6-carplates-arrival-deposit"></h6>

      <form id="formArrivalDepot">
         <div class="form-group row">
            <div class="col-sm-12">
              <label id="label-stay-arrival-deposit"></label><br>
              <label>Hora entrada:</label>
              <input type="datetime-local" name="arrival_date_deposit" class="form-control">
              <small class="text-danger" id="error-arrival_date_deposit"></small>
            </div>
        </div>

        <input type="hidden" name="id">
        <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
        <button type="button" name="savechanges" class="btn btn-success btn-user btn-sm" onclick="saveChanges('#formArrivalDepot')"><i class='fas fa-solid fa-check-circle'></i> Guardar</button>
        <button type="button" name="closemodal" class="btn btn-danger btn-user btn-sm"  onclick="closeModal()">Cancelar</button>
      </form>
    </div>

    <!-- SALIDA DEPÓSITO -->
    <div id="modalAddHourDepartureDeposit" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="addHourDepartureDeposit" class="modal-box" style="display:none; position:fixed; width:30%; top:20%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
      <h4 id="h4-departure-hour-deposit"></h4>
      <h6 id="h6-carplates-departure-deposit"></h6>

      <form id="formDepartureDepot">
        <div class="form-group row">
          <div class="col-sm-12">
            <label id="label-stay-departure-deposit"></label><br>
            <label>Hora salida:</label>
            <input type="datetime-local" name="departure_date_deposit" class="form-control">
            <small class="text-danger" id="error-departure_date_deposit"></small>
          </div>
        </div>

        <input type="hidden" name="id">
        <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
        <button type="button" name="savechanges" class="btn btn-success btn-user btn-sm" onclick="saveChanges('#formDepartureDepot')"><i class='fas fa-solid fa-check-circle'></i> Guardar</button>
        <button type="button" name="closemodal" class="btn btn-danger btn-user btn-sm"  onclick="closeModal()">Cancelar</button>
      </form>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

function openModalHour(id, type) {
  $.post('../controllers/famesaTruckEditController.php', { id }, function(data) {
    let config = {
      departure_port: {
        modal: '#modalAddHourDeparturePort',
        box: '#addHourDeparturePort',
        title: '#h4-departure-hour-port',
        plates: '#h6-carplates-departure-port',
        label: '#label-stay-departure-port',
        input: '[name="departure_port"]',
        fecha: data.arrival_date_port,
        text: 'Registrar Salida Puerto.'
      },
      arrival_depot: {
        modal: '#modalAddHourArrivalDeposit',
        box: '#addHourArrivalDeposit',
        title: '#h4-arrival-hour-deposit',
        plates: '#h6-carplates-arrival-deposit',
        label: '#label-stay-arrival-deposit',
        input: '[name="arrival_depot"]',
        fecha: data.departure_date_port,
        text: 'Registrar Ingreso Depósito.'
      },
      departure_depot: {
        modal: '#modalAddHourDepartureDeposit',
        box: '#addHourDepartureDeposit',
        title: '#h4-departure-hour-deposit',
        plates: '#h6-carplates-departure-deposit',
        label: '#label-stay-departure-deposit',
        input: '[name="departure_depot"]',
        fecha: data.arrival_date_deposit,
        text: 'Registrar Salida Depósito.'
      }
    };

    let c = config[type];
    if (!c) return;

    // estadía
    const inicio = new Date(c.fecha);
    const ahora = new Date();
    const diff = ahora - inicio;
    const min = Math.floor(diff / 60000);
    const d = Math.floor(min / 1440);
    const h = Math.floor((min % 1440) / 60);
    const m = min % 60;

    $('[name="id"]').val(data.row_id);
    $(c.title).text(c.text);
    $(c.plates).text(`Patentes: ${data.car_plate_truck} | ${data.car_plate_ramp}`);
    $(c.label).text(`Estadía: ${d}d con ${h}h y ${m}m`);
    $(c.input).val(c.fecha || '');

    $(c.modal).fadeIn(200);
    $(c.box).fadeIn(200);

  }, 'json');
}

function saveChanges(formId) {
  const form = document.querySelector(formId);
  const formData = new FormData(form);
  let hasError = false;
  let paginaActual = $(form).find('input[name="page"]').val();

  // limpiar errores
  $(form).find('small.text-danger').text('');
  $(form).find('.form-control-user').removeClass('is-invalid');

  // validar campos
  for (let [key, value] of formData.entries()) {
    const inputElement = form.querySelector(`[name="${key}"]`);
    const errorElement = form.querySelector(`#error-${key}`);
    const isEmpty = value.trim() === '' || value === '-';

    if (isEmpty) {
      if (errorElement) errorElement.innerText = 'Este campo es obligatorio.';
      if (inputElement) inputElement.classList.add('is-invalid');

      hasError = true;
    } else {
      if (errorElement) errorElement.innerText = '';
      if (inputElement) inputElement.classList.remove('is-invalid');
    }
  }

  // enviar
  if (!hasError) {
    $.ajax({
      url: '../controllers/famesaUpdateHourTruckController.php',
      type: 'POST',
      data: $(form).serialize()
    }).done(function(res) {
      let r = {};
      try { r = JSON.parse(res); } catch(e) {}

      if (r.status === 'ok') {
        Swal.fire({
          title: '¡Éxito!',
          text: 'Hora guardada correctamente.',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then(() => {
          window.location = '<?php echo generateMkey('enter_truck_famesa'); ?>&page=' + paginaActual;
        });
      } else {
        Swal.fire({
          title: 'Error',
          text: 'No se pudo guardar.',
          icon: 'error'
        });
      }
    });
  }
}

function closeModal() {
  $('.modal-overlay').fadeOut(200);
  $('.modal-box').fadeOut(200);
}

var saveInTruck = function() {
  const form = document.getElementById('inTruckForm');
  const formData = new FormData(form);
  const isUpdate = $('#isUpdate').val();
  let hasError = false;
  const btn = $('#loadBtn');
  const text = $('#loadBtnText');
  const spinner = $('#loadBtnSpinner');
  var paginaActual = $('input[name="page"]').val();

  // Limpiar errores anteriores
  document.querySelectorAll('small.text-danger').forEach(el => el.innerText = '');
  document.querySelectorAll('.form-control-user').forEach(el => el.classList.remove('is-invalid'));
  $('.select2-selection').removeClass('border border-danger');

  // Campos que son obligatorios
  const requiredFields = [
    'countervessel',
    'vessel',
    'carplatetruck',
    'guidenumber',
    'maxibagsquantity',
    'category',
    'arrivaldateport'
  ];

  for (let field of requiredFields) {
    const inputElement = form.querySelector(`[name="${field}"]`);
    const errorElement = document.getElementById('error-' + field);
    const isSelect2 = inputElement && $(inputElement).hasClass('select2-hidden-accessible');
    const value = formData.get(field)?.trim() ?? '';

    if (value === '' || value === '-') {
      hasError = true;
      if (errorElement) errorElement.innerText = 'Este campo es obligatorio.';
      if (isSelect2) {
        $(inputElement).next('.select2-container').find('.select2-selection').addClass('border border-danger');
      } else if (inputElement) {
        inputElement.classList.add('is-invalid');
      }
    }
  }

  if (!hasError) {
    text.addClass('d-none');
    spinner.removeClass('d-none');
    btn.prop('disabled', true);

    $.ajax({
      url: '../controllers/famesaTruckController.php',
      data: $('#inTruckForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      let successMsg = isUpdate == 1 ? '¡Camión actualizado con éxito!' : '¡Ingreso de camión registrado exitosamente!';
      let errorMsg   = isUpdate == 1 ? 'Error al actualizar el camión.' : 'Error al registrar el ingreso del camión.';
      let successCode = isUpdate == 1 ? 'OKU' : 'OK';
      let failCode    = isUpdate == 1 ? 'NOOKU' : 'NOOK';

      if (x == successCode) {
        Swal.fire({
          title: '¡Éxito!',
          text: successMsg,
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then(() => {
          window.location = '<?php echo generateMkey('enter_truck_famesa'); ?>&page=' + paginaActual;
        });
      } else if (x == failCode) {
        Swal.fire({
          title: 'Oops...',
          text: errorMsg,
          icon: 'error',
          cancelButtonColor: '#d33',
        }).then(() => {
          text.removeClass('d-none');
          spinner.addClass('d-none');
          btn.prop('disabled', false);
        });
      }
    });
  }
};

var deleteTruck = function(id) {
  var paginaActual = $('input[name="page"]').val();

  Swal.fire({
    title: 'Eliminar Camión.',
    text: '¿Estas seguro de eliminar este camión?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Si, eliminar!",
    cancelButtonText : 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../controllers/famesaTruckDeleteController.php',
        type: 'POST',
        data: { id: id },
      }).done(function(x) {
        if(x == 'OK'){
          Swal.fire({
            title: '¡Éxito!',
            text: 'Camión eliminado con éxito!',
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          }).then((result) => {
            window.location = '<?php echo generateMkey('enter_truck_famesa'); ?>&page=' + paginaActual;
          });
        } else if(x == 'NOOK'){
          Swal.fire({
            title: 'Oops...',
            text: 'Error al eliminar el camión.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        }else if(x == 'NOOK2'){
          Swal.fire({
            title: 'Oops...',
            text: 'El camión que tratas de eliminar tiene una hora de ingreso y/o salida de puerto y/o depósito registrada.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        }
      });
    }
  });
}

var exportExcel = function(nave, patente, guia) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../controllers/famesaTruckDownloadExcelController.php';
  form.style.display = 'none';

  const fields = {
    nave: nave,
    patente: patente,
    guia: guia
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

var editTruck = function(id) {
  $.ajax({
    url: '../controllers/famesaTruckEditController.php',
    type: 'POST',
    data: { id: id },
    dataType: 'json',
    success: function(data) {
      $('#isUpdate').val(1);
      $('#truckId').val(id);
      $('#countervessel').val(data.counter_vessel);

      // Vessel select
      $('#vessel').empty().append(
        $('<option>', {
          value: data.vessel_id,
          text: data.vessel_name + ' (Viaje: ' + data.voyage + ')'
        })
      ).trigger('change');

      // Truck & ramp plates
      $('#carplatetruck').empty();
      $('#carplatetruck').append($('<option>', {value: data.car_plate_truck, text: data.car_plate_truck}));

      $('#carplateramp').empty();
      $('#carplateramp').append($('<option>', {value: data.car_plate_ramp, text: data.car_plate_ramp}));

      // Otros campos
      $('#guidenumber').val(data.guide_number);
      $('#maxibagsquantity').val(data.maxibags_quantity);
      $('input[name="category"][value="' + data.category + '"]').prop('checked', true);

      // Fechas: si son null, poner ''
      $('#arrivaldateport').val(data.arrival_date_port ?? '');
      $('#departuredateport').val(data.departure_date_port ?? '');
      $('#arrivaldatedeposit').val(data.arrival_date_deposit ?? '');
      $('#departuredatedeposit').val(data.departure_date_deposit ?? '');

      $('#observations').val(data.observations ?? '');
      $('#loadBtn').addClass('btn-info');
      $('#loadBtnText').html('<i class="fas fa-check-circle"></i> Actualizar Camión');
      $('#scrollTopBtn').trigger('click');
    },
    error: function() {
      alert('Error al cargar los datos.');
    }
  });
};

/* Valida maxima cantidad de pallets */
function validarMaximo(input) {
  if (parseInt(input.value) > 40) {
    input.value = 40;
  }
  if (parseInt(input.value) < 0) {
    input.value = 0;
  }
}

/* Restringe el numero de telefono a 9 numeros */
function limitarTelefono(input) {
  // Elimina cualquier caracter no numérico
  input.value = input.value.replace(/\D/g, '');

  // Limita a 9 caracteres
  if (input.value.length > 9) {
    input.value = input.value.slice(0, 9);
  }
}

$(document).ready(function() {
  setInterval(actualizarReloj, 1000);
  actualizarReloj(); /* Primera llamada */

  $('#vessel').select2({
    allowClear: true,
    tags: false,
    width: '95%',
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

  $('#carplatetruck').select2({
    allowClear: true,
    tags: true,
    width: '100%',
    ajax: {
      url: '../controllers/carPlateTruckJsonController.php',
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

  $('#carplateramp').select2({
    allowClear: true,
    tags: true,
    width: '100%',
    ajax: {
      url: '../controllers/carPlateRampJsonController.php',
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

  $('#vessel').on('change', function () {
    const vessel = $(this).val();
    const isUpdate = $('#isUpdate').val();

    if (vessel != '-') {
      $.ajax({
        url: '../controllers/vesselInfoController.php',
        method: 'POST',
        data: {id: vessel},
        success: function (response) {
          $('#info-vessel').html(`${response}`);
        },
        error: function () {
          $('#info-vessel').html(`<div class="card shadow-sm border-0 mb-3">
              <div class="card-body text-danger">
                Error al obtener la información.
              </div>
            </div>
          `);
        }
      });

      if (isUpdate == 0) {
        $.ajax({
          url: '../controllers/setCounterVesselFamesaController.php',
          method: 'POST',
          data: {id: vessel},
          success: function (response) {
            $('#countervessel').val(response);
          },
          error: function () {
            $('#countervessel').val(0);
          }
        });
      }
    }else{
      $('#info-vessel').html('');
      $('#countervessel').val(null);
    }
  });

  $('#nave').select2({
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
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/carPlateTruckJsonController.php',
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