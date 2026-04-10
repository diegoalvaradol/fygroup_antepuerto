<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$line = new shipLine();
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
$paginaActual = isset($_GET['page']) ? (int) $_GET['page'] : 1;
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
    <title>FYGroup | Lineas Navieras</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Lineas Navieras</h1>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg-12">
                            <!-- Custom Text Color Utilities -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Formulario</h6>
                                </div>

                                <div class="card-body">
                                    <form class="form-container" id="shipLineForm">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for='rutShipLine' class='text-gray-800 font-weight-bold'>R.U.T</label>
                                                <input type="text" class="form-control form-control-user" id="rutShipLine" name="rutShipLine" oninput="formatearRut(this)" maxlength="12" onblur="validaRut(this.value)" placeholder="11.222.333-0">
                                                <small class="text-danger" id="error-rutShipLine"></small>
                                            </div>

                                            <div class="col-sm-3">
                                                <label for='shipline' class='text-gray-800 font-weight-bold'>Nombre de Linea Naviera</label>
                                                <input type="text" class="form-control form-control-user" id="shipline" name="shipline" onblur="verifyShipLine(this.value)" placeholder="Maersk, Hapag Lloyd, etc.">
                                                <small class="text-danger" id="error-shipline"></small>
                                            </div>
                                        </div>

                                        <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
                                        <button id="loadBtn" type="button" class="btn btn-primary btn-sm btn-user" onclick="saveShipLine()">
                                          <span id="loadBtnText"><i class="fas fa-solid fa-check-circle"></i> Guardar</span>
                                          <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                        <button type='button' class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-undo'></i> Limpiar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Listado de Lineas Navieras -->
                    <?php echo $line->getTableShipLine(); ?>
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
          <div class="modal-header bg-info text-white py-2 px-3">
            <h6 class="modal-title font-weight-bold mb-0" id="logoutModalLabel">¿Deseas cerrar sesión?</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span>×</span>
            </button>
          </div>
          <div class="modal-body">
            Selecciona 'Cerrar sesión' si realmente deseas hacerlo.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de ajustes-->
    <div class="modal fade" id="goalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-2 px-3">
                    <h6 class="modal-title font-weight-bold mb-0" id="exampleModalLabel">Configurar Capacidad de Antepuerto</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span>×</span></button>
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
                <div class="modal-header bg-info text-white py-2 px-3">
                    <h6 class="modal-title font-weight-bold mb-0" id="exampleModalLabel">Perfil de: <?php echo $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . '.'; ?></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span>×</span></button>
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
                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Ingresa tu contraseña actual" autocomplete="current-password">
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
                <div class="modal-header bg-info text-white py-2 px-3">
                    <h6 class="modal-title font-weight-bold mb-0" id="exampleModalLabel">Licencia de Uso de Software</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span>×</span></button>
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

    <!-- Modal Editar Linea-->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="editLineModal" style="display:none; position:fixed; width:35%; top:20%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
    <h4>Editar Línea</h4>
    <form id="editLineForm">
        <div class="form-group row">
            <div class="col-sm-6">
              <label>R.U.T:</label>
              <input type="text" class="form-control form-control-user" id="rutLine" name="rutLine" disabled>
            </div>
            <div class="col-sm-6">
              <label>Linea Naviera:</label>
              <input type="text" class="form-control form-control-user" id="lineName" name="lineName">
            </div>
        </div>

        <input type="hidden" id="lineId" name="lineId">
        <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
        <button type="button" name="savechanges" class="btn btn-success btn-user btn-sm" onclick="saveChanges()"><i class='fas fa-solid fa-check-circle'></i> Guardar</button>
        <button type="button" name="closemodal" class="btn btn-danger btn-user btn-sm" onclick="closeModal()">Cancelar</button>
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
var formatearRut = function (inputRun) {
  let rut = inputRun.value.replace(/[^0-9kK]/g, '').toUpperCase();

  /* Separar cuerpo y DV */
  let cuerpo = rut.slice(0, -1);
  let dv = rut.slice(-1);

  /* Agregar puntos cada 3 dígitos desde la derecha */
  let cuerpoFormateado = '';
  let i = 0;
  for (let j = cuerpo.length - 1; j >= 0; j--) {
    cuerpoFormateado = cuerpo[j] + cuerpoFormateado;
    i++;
    if (i % 3 === 0 && j !== 0) {
      cuerpoFormateado = '.' + cuerpoFormateado;
    }
  }

  inputRun.value = cuerpoFormateado + '-' + dv;
}

var validaRut = function(rut) {
  rut = rut.replace(/[^0-9kK]/g, '').toUpperCase();

  if (rut.length < 2) return false;
  const cuerpo = rut.slice(0, -1);
  const dvIngresado = rut.slice(-1);

  let suma = 0;
  let multiplo = 2;

  /* Recorrer el cuerpo del RUT de derecha a izquierda */
  for (let i = cuerpo.length - 1; i >= 0; i--) {
    suma += parseInt(cuerpo[i]) * multiplo;
    multiplo = multiplo < 7 ? multiplo + 1 : 2;
  }

  const dvEsperado = 11 - (suma % 11);
  let dvCalculado = '';

  if (dvEsperado === 11) dvCalculado = '0';
  else if (dvEsperado === 10) dvCalculado = 'K';
  else dvCalculado = dvEsperado.toString();

  if(dvCalculado === dvIngresado){
    Swal.fire({
      title: '¡Éxito!',
      text: '¡El R.U.T ingresado es válido!',
      icon: 'success',
      confirmButtonText: 'Aceptar'
    });
  }else{
    Swal.fire({
      title: 'Error!',
      text: '¡El R.U.T ingresado no es válido!',
      icon: 'warning',
      cancelButtonColor: 'Aceptar'
    }).then(() => {
      $('#rutShipLine').focus();
    });
  }
}

var verifyShipLine = function(name) {
  if(name !== ''){
    $.ajax({
      url: '../controllers/shipLineVerifyController.php',
      data: {
        name: name
      },
      type: "POST",
    }).done(function(x) {
      if(x == 'NOOK'){
        Swal.fire({
          title: 'Oops...',
          text: 'La Linea '+name+' ya se encuentra registrado.',
          icon: 'error',
          cancelButtonColor: '#d33',
        }).then((result) => {
          $('#shipline').val('').focus();
        });
      }
    });
  }
}

var editShipLine = function(id) {
  $.ajax({
    url: '../controllers/shipLineEditController.php',
     type: 'POST',
     data: { id: id },
     dataType: 'json',
     success: function(data) {
      $('#lineId').val(data.line_id);
      $('#lineName').val(data.name);
      $('#rutLine').val(data.rut);

      /* Mostrar overlay y modal */
      $('#modalOverlay').fadeIn(200);
      $('#editLineModal').fadeIn(200);
    },
    error: function() {
      alert('Error al cargar los datos.');
    }
  });
}

var closeModal = function() {
  $('#editLineModal').fadeOut(200);
  $('#modalOverlay').fadeOut(200);
}

var saveChanges = function() {
  var paginaActual = $('input[name="page"]').val();

  $.ajax({
    url: '../controllers/shipLineUpdateController.php',
    data: $('#editLineForm').serialize(),
    type: 'POST',
  }).done(function(x) {
    if(x == 'OK'){
      Swal.fire({
        title: '¡Éxito!',
        text: '¡Linea actualizada con éxito!',
        icon: 'success',
        confirmButtonColor: '#4CAF50'
      }).then((result) => {
        window.location = '<?php echo generateMkey('enter_ship_line'); ?>&page=' + paginaActual;
      });
    } else {
      Swal.fire({
        title: 'Oops...',
        text: 'Error al actualizar la linea.',
        icon: 'error',
        cancelButtonColor: '#d33',
      });
    }
  });
}

var deleteShipLine = function(id) {
  var paginaActual = $('input[name="page"]').val();

  Swal.fire({
    title: 'Eliminar Linea Naviera.',
    text: '¿Estas seguro de eliminar esta linea?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Si, elimimar!",
    cancelButtonText : 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../controllers/shipLineDeleteController.php',
        type: 'POST',
        data: { id: id },
      }).done(function(x) {
        if(x == 'OK'){
          Swal.fire({
            title: '¡Éxito!',
            text: '¡Linea eliminada con éxito!',
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          }).then((result) => {
            window.location = '<?php echo generateMkey('enter_ship_line'); ?>&page=' + paginaActual;
          });
        } else if(x == 'NOOK'){
          Swal.fire({
            title: 'Oops...',
            text: 'Error al eliminar la linea.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        }else if(x == 'NOOK2'){
          Swal.fire({
            title: 'Oops...',
            text: 'La linea naviera que tratas de eliminar se encuentra asociado a una motonave registrada, favor revisa e intenta nuevamente.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        }
      });
    }
  });
}

var saveShipLine = function() {
  const form = document.getElementById('shipLineForm');
  const formData = new FormData(form);
  let hasError = false;
  const btn = $('#loadBtn');
  const text = $('#loadBtnText');
  const spinner = $('#loadBtnSpinner');
  var paginaActual = $('input[name="page"]').val();

  document.querySelectorAll('small.text-danger').forEach(el => el.innerText = '');
  document.querySelectorAll('.form-control-user').forEach(el => el.classList.remove('is-invalid'));

  /* Validar si algún campo está vacío */
  for (let [key, value] of formData.entries()) {
    if (!value.trim()) {
      const errorElement = document.getElementById('error-' + key);

      if (errorElement) {
        errorElement.innerText = 'Este campo es obligatorio.';
        const inputElement = form.querySelector(`[name="${key}"]`);
        inputElement.classList.add('is-invalid');
      }

      hasError = true;
    }
  }

  /* Hace envio de los datos a traves del formulario */
  if(!hasError){
    text.addClass('d-none');
    spinner.removeClass('d-none');
    btn.prop('disabled', true);

    $.ajax({
      url: '../controllers/shipLineController.php',
      data: $('#shipLineForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          text: '¡Linea registrada con éxito!',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = '<?php echo generateMkey('enter_ship_line'); ?>&page=' + paginaActual;
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al registrar la linea.',
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
}

$(document).ready(function() {
  setInterval(actualizarReloj, 1000);
  actualizarReloj(); /* Primera llamada */
});
</script>
