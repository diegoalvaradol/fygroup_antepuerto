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
$paginaActual = isset($_GET['page']) ? (int) $_GET['page'] : 1;
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
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>FYGroup | Usuarios</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Usuarios</h1>
                    <p class="mb-4">
                        Para agregar un nuevo usuario haz click en el botón agregar.
                        <button class='btn btn-success btn-sm' onclick="addNewUser()"><i class='fas fa-plus'></i> Agregar</button>
                    </p>

                    <!-- Tabla de Usuarios -->
                    <?php echo $user->getTableUser(); ?>
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

    <!-- Scroll to Top Button -->
    <?php echo $top; ?>

    <!-- Modales -->
    <?php echo $modals->render();?>

    <!-- Modal Editar Contraseña Usuario-->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="resetPasswordModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3); min-width:300px;">
        <h4>Resetear contraseña</h4>

        <form id="resetPasswordForm">
            <div class="form-group row">
                <div class="col-sm-12">
                    <label>Nueva contraseña:</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                </div>
            </div>

            <input type="hidden" id="userRun" name="userRun">
            <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">

            <button type="button" class="btn btn-success btn-sm" onclick="saveChanges()"><i class='fas fa-check-circle'></i> Guardar</button>
            <button type="button" class="btn btn-danger btn-sm" onclick="closeModal()">Cancelar</button>
        </form>
    </div>

    <!-- Modal Nuevo Usuario-->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="newUserModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3); min-width:300px; width:50%; max-width:600px; max-height:90vh; overflow-y:auto;">
        <h4>Crear nuevo usuario</h4>

        <form id="newUserForm">
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>RUN:</label>
                    <input type="text" class="form-control form-control-user" id="run" name="run" autocomplete="run" maxlength="12" placeholder="12.345.678-9" oninput="formatearRut(this)" onblur="validaRut(this.value)">
                    <small class="text-danger" id="error-run"></small>
                </div>

                <div class="col-sm-6">
                    <label>Divisón:</label>
                    <select class="form-control select2 form-control-user" id="division" name="division">
                        <option value="-" selected>Seleccione una división...</option>
                        <option value="fy">Personal FYGroup</option>
                        <option value="terminal">Terminal</option>
                        <option value="shipper">Naviera</option>
                    </select>
                    <small class="text-danger" id="error-division"></small>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Nombre:</label>
                    <input type="text" class="form-control form-control-user" id="name" name="name">
                    <small class="text-danger" id="error-name"></small>
                </div>

                <div class="col-sm-6">
                    <label>Apellido:</label>
                    <input type="text" class="form-control form-control-user" id="lastname" name="lastname">
                    <small class="text-danger" id="error-lastname"></small>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Correo:</label>
                    <input type="email" class="form-control form-control-user" id="email" name="email">
                    <small class="text-danger" id="error-email"></small>
                </div>

                <div class="col-sm-6">
                    <label>Contraseña:</label>
                    <input type="password" class="form-control form-control-user" id="password" name="password">
                    <small class="text-danger" id="error-password"></small>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Administrador:</label>
                    <select class="form-control select2 form-control-user" id="is_admin" name="is_admin">
                        <option value="-" selected>Seleccione una opción...</option>
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                    <small class="text-danger" id="error-is_admin"></small>
                </div>

                <div class="col-sm-6">
                    <label>Editor:</label>
                    <select class="form-control select2 form-control-user" id="is_admin_edit" name="is_admin_edit">
                        <option value="-" selected>Seleccione una opción...</option>
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                    <small class="text-danger" id="error-is_admin_edit"></small>
                </div>
            </div>

            <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
            <button type="button" class="btn btn-success btn-sm" onclick="saveNewUser()"><i class='fas fa-check-circle'></i> Guardar</button>
            <button type="button" class="btn btn-danger btn-sm" onclick="closeModalNewUser()">Cancelar</button>
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
  let cuerpo = rut.slice(0, -1);
  let dv = rut.slice(-1);
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

var validaRut = function (rut) {
  rut = rut.replace(/[^0-9kK]/g, '').toUpperCase();

  Swal.fire({
    title: 'RUT inválido',
    icon: 'error',
    confirmButtonColor: '#d33',
  }).then((result) => {
    $('#run').focus();
  });

  const cuerpo = rut.slice(0, -1);
  const dv = rut.slice(-1);

  let suma = 0;
  let multiplo = 2;

  for (let i = cuerpo.length - 1; i >= 0; i--) {
    suma += parseInt(cuerpo[i]) * multiplo;
    multiplo = multiplo < 7 ? multiplo + 1 : 2;
  }

  const dvEsperado = 11 - (suma % 11);
  let dvCalculado = '';

  if (dvEsperado === 11) {
    dvCalculado = '0';
  } else if (dvEsperado === 10) {
    dvCalculado = 'K';
  } else {
    dvCalculado = dvEsperado.toString();
  }

  if (dv !== dvCalculado) {
    Swal.fire({
      title: 'RUT inválido',
      text: 'El dígito verificador no coincide.',
      icon: 'error',
      confirmButtonColor: '#d33',
    }).then((result) => {
      $('#run').focus();
    });

    return false;
  }

  return true;
}

var saveNewUser = function() {
  const form = document.getElementById('newUserForm');
  const formData = new FormData(form);
  let hasError = false;
  var paginaActual = $('input[name="page"]').val();

  document.querySelectorAll('small.text-danger').forEach(el => el.innerText = '');
  document.querySelectorAll('.form-control-user').forEach(el => el.classList.remove('is-invalid'));

  /* Validar si algún campo está vacío */
  for (let [key, value] of formData.entries()) {
    const inputElement = form.querySelector(`[name="${key}"]`);
    const errorElement = document.getElementById('error-' + key);
    const isSelect2 = inputElement && $(inputElement).hasClass('select2-hidden-accessible');
    const isEmpty = value.trim() === '' || value === '-';

    if (isEmpty) {
      if (errorElement) {
        errorElement.innerText = 'Este campo es obligatorio.';
      }

      if (isSelect2) {
        // Para select2: agrega borde rojo al contenedor visible
        $(inputElement).next('.select2-container')
          .find('.select2-selection')
          .addClass('border border-danger');
      } else if (inputElement) {
        inputElement.classList.add('is-invalid');
      }

      hasError = true;
    } else {
      if (errorElement) {
        errorElement.innerText = '';
      }

      if (isSelect2) {
        $(inputElement).next('.select2-container')
          .find('.select2-selection')
          .removeClass('border border-danger');
      } else if (inputElement) {
        inputElement.classList.remove('is-invalid');
      }
    }
  }
  /* Hace envio de los datos a traves del formulario */
  if(!hasError){
    $.ajax({
      url: '../controllers/userController.php',
      data: $('#newUserForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          text: '¡Usuario ingresado con éxito!',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = '<?php echo generateMkey('enter_user'); ?>&page=' + paginaActual;
        });
      }else if(x == 'NOOK') {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al ingresar usuario.',
          icon: 'error',
          cancelButtonColor: '#d33',
        });
      }
    });
  }
}

var addNewUser = function(run) {
  $('#userRun').val(run);
  $('#modalOverlay').fadeIn(200);
  $('#newUserModal').fadeIn(200);
}

var closeModalNewUser = function() {
  $('#newUserModal').fadeOut(200);
  $('#modalOverlay').fadeOut(200);
}

var resetPassword = function(run) {
  $('#userRun').val(run);
  $('#modalOverlay').fadeIn(200);
  $('#resetPasswordModal').fadeIn(200);
}

var closeModal = function() {
  $('#resetPasswordModal').fadeOut(200);
  $('#modalOverlay').fadeOut(200);
}

var saveChanges = function() {
  var paginaActual = $('input[name="page"]').val();

  $.ajax({
    url: '../controllers/userResetPassword.php',
    data: $('#resetPasswordForm').serialize(),
    type: 'POST',
  }).done(function(x) {
    if(x == 'OK'){
      Swal.fire({
        title: '¡Éxito!',
        text: 'Contraseña actualizada con éxito!',
        icon: 'success',
        confirmButtonColor: '#4CAF50'
      }).then((result) => {
        window.location = '<?php echo generateMkey('enter_user'); ?>&page=' + paginaActual;
      });
    } else if(x == 'EMPTY_PASSWORD'){
      Swal.fire({
        title: '¡Atención!',
        text: 'La contraseña no puede estar vacía.',
        icon: 'warning',
        confirmButtonColor: '#f8bb86'
      });
    }else {
      Swal.fire({
        title: 'Oops...',
        text: 'Error al actualizar la contraseña.',
        icon: 'error',
        cancelButtonColor: '#d33',
      });
    }
  });
}

var changeStatusUser = function (run, status) {
  var statusLabel, statusMsg;
  var paginaActual = $('input[name="page"]').val();

  if (status == 1) {
    statusLabel = 'habilitar';
    statusMsg = 'habilitado';
  } else {
    statusLabel = 'deshabilitar';
    statusMsg = 'deshabilitado';
  }

  Swal.fire({
    title: `${statusLabel.charAt(0).toUpperCase() + statusLabel.slice(1)} usuario`,
    html: `¿Estás seguro de ${statusLabel} este usuario?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: `Sí, ${statusLabel} usuario`,
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../controllers/userChangeStatusController.php',
        type: 'POST',
        data: {
          run: run,
          status: status
        },
      }).done(function (x) {
        if (x == 'OK') {
          Swal.fire({
            title: 'Éxito',
            text: `Usuario ${statusMsg} correctamente.`,
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          }).then(() => {
            window.location = '<?php echo generateMkey('enter_user'); ?>&page=' + paginaActual;
          });
        } else {
          Swal.fire({
            title: 'Error',
            text: 'No se pudo cambiar el estado del usuario.',
            icon: 'error',
            confirmButtonColor: '#d33',
          });
        }
      });
    }
  });
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
        window.location = '<?php echo generateMkey('enter_user'); ?>';
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

$(document).on('select2:open', function () {
  let searchField = document.querySelector('.select2-container--open .select2-search__field');
  if (searchField) {
    searchField.focus();
  }
});
</script>
