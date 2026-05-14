<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$cfg = new cfg();
$user = new user();
$port = new outerPort();
$alerts = new UIComponents();

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
    <title>FYGroup | Roleo de Carga</title>

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
              <h1 class="h3 mb-1 text-gray-800">Roleo de Carga</h1>
              <p class="mb-4">Acá podrás realizar el roleo de carga entre naves del tipo liner y charter.</p>

              <div class="col-sm-6">
                <?php echo $alerts->customAlert('warning', 'Atención', 'Considerar que la acción de roleo es un proceso irreversible.'); ?>
              </div>

              <!-- Content Row -->
              <div class="row">
                <!-- First Column -->
                <div class="col-lg">
                  <!-- Custom Text Color Utilities -->
                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary">Formulario de Roleo</h6>
                    </div>

                    <div class="card-body">
                      <div class="col-sm-3">
                        <?php echo $alerts->customAlert('info', 'Simbología', '"-T": Termo | "-C": Contenedores.'); ?>
                      </div>

                      <form class="form-container" id="vesselTransferForm">
                        <div class="form-group row">
                          <div class="col-sm-4">
                            <div class="form-inline mb-3">
                              <label class="mr-2 text-gray-800 font-weight-bold">Motonave de Origen</label>
                              <i class="fas fa-info-circle text-info" role="right" data-toggle="popover" data-trigger="hover focus" data-placement="right" data-content="Indica la nave de origen del roleo."></i>

                              <select class="form-control select2 form-control-user" id="fromvessel" name="fromvessel">
                                <option value="-">Seleccione una motonave...</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-sm-4">
                            <div class="form-inline mb-3">
                              <label class="mr-2 text-gray-800 font-weight-bold">Motonave de Destino</label>
                              <i class="fas fa-info-circle text-info" role="right" data-toggle="popover" data-trigger="hover focus" data-placement="right" data-content="Indica la nave de destino del roleo."></i>

                              <select class="form-control select2 form-control-user" id="tovessel" name="tovessel">
                                <option value="-">Seleccione una motonave...</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-sm-4">
                            <div class="form-inline mb-3">
                              <label class="mr-2 text-gray-800 font-weight-bold">Camiones Disponibles</label>
                              <i class="fas fa-info-circle text-info" role="right" data-toggle="popover" data-trigger="hover focus" data-placement="right" data-content="Indica los camiones disponibles para el roleo."></i>
                              <select class="form-control select2 form-control-user" id="rowId" name="rowId[]" multiple>
                                <option value="-">Seleccione uno o más camiones...</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="form-group row">
                          <div class="col-sm-4">
                            <div class="form-inline mb-3">
                              <label class="mr-2 text-gray-800 font-weight-bold">Información Motonave de Origen</label>
                            </div>

                            <div class="form-inline mb-3">
                              <small class="text-black" id="info-fromvessel"></small>
                            </div>
                          </div>

                          <div class="col-sm-4">
                            <div class="form-inline mb-3">
                              <label class="mr-2 text-gray-800 font-weight-bold">Información Motonave de Destino</label>
                            </div>

                            <div class="form-inline mb-3">
                              <small class="text-black" id="info-tovessel"></small>
                            </div>
                          </div>
                        </div>

                        <button id="loadBtn" type="button" class="btn btn-primary btn-sm btn-user" onclick="saveVesselTransfer()">
                          <span id="loadBtnText"><i class="fas fa-solid fa-check-circle"></i> Realizar Roleo</span>
                          <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                        <button type='button' class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-undo'></i> Limpiar</button>
                      </form>
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
var saveVesselTransfer = function() {
  const form = document.getElementById('vesselTransferForm');
  const formData = new FormData(form);
  let hasError = false;
  const btn = $('#loadBtn');
  const text = $('#loadBtnText');
  const spinner = $('#loadBtnSpinner');
  var fromVessel = $('#fromvessel').val();
  var toVessel = $('#tovessel').val();
  var rowId = $('#rowId').val();

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

  if(fromVessel === '-'){
    Swal.fire({
      title: 'Oops...',
      text: 'Debes seleccionar una nave de origen.',
      icon: 'error',
      cancelButtonColor: '#d33',
    });

    hasError = true;
  }

  if(toVessel === '-'){
    Swal.fire({
      title: 'Oops...',
      text: 'Debes seleccionar una neve de destino.',
      icon: 'error',
      cancelButtonColor: '#d33',
    });

    hasError = true;
  }

  if(fromVessel === '-' && toVessel === '-'){
    Swal.fire({
      title: 'Oops...',
      text: 'Debes seleccionar una nave de origen y destino para realizar el roleo.',
      icon: 'error',
      cancelButtonColor: '#d33',
    });

    hasError = true;
  }

  if((fromVessel !== '-' && toVessel !== '-') && fromVessel === toVessel){
    Swal.fire({
      title: 'Oops...',
      text: 'El roleo no se puede realizar a la misma nave.',
      icon: 'error',
      cancelButtonColor: '#d33',
    });

    hasError = true;
  }

  /* Hace envio de los datos a traves del formulario */
  if(!hasError){
    text.addClass('d-none');
    spinner.removeClass('d-none');
    btn.prop('disabled', true);

    $.ajax({
      url: '../controllers/vesselTransferController.php',
      data: $('#vesselTransferForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      Swal.fire({
        title: "¿Estás seguo de realizar el roleo?",
        text: "EL roleo consta de un total de ("+rowId.length+") camiones.",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#4CAF50",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, rolear",
        cancelButtonText: "No, cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
          if(x === 'OK'){
            Swal.fire({
              title: '¡Éxito!',
              text: 'Roleo realizado con éxito!',
              icon: 'success',
              confirmButtonColor: '#4CAF50'
            }).then((result) => {
              window.location = '<?php echo generateMkey('vessel_transfer'); ?>';
            });
          }

          if(x === 'NOOK'){
            Swal.fire({
              title: 'Oops...',
              text: 'Error al realizar el roleo de carga entre naves.',
              icon: 'error',
              cancelButtonColor: '#d33',
            }).then(() => {
              text.removeClass('d-none');
              spinner.addClass('d-none');
              btn.prop('disabled', false);
            });
          }

          if(x === 'ERROR'){
            Swal.fire({
              title: 'Oops...',
              html: 'Asegurate que el tipo de naves sea el mismo.'+'</br>'+'[Liner => Liner] ó [Charter => Charter]',
              icon: 'error',
              cancelButtonColor: '#d33',
            }).then(() => {
              text.removeClass('d-none');
              spinner.addClass('d-none');
              btn.prop('disabled', false);
            });
          }
        }else if(result.dismiss){
          text.removeClass('d-none');
          spinner.addClass('d-none');
          btn.prop('disabled', false);
        }
      });
    });
  }
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
        window.location = '<?php echo generateMkey('vessel_transfer'); ?>';
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

  $('#fromvessel, #tovessel').select2({
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

  $('#rowId').select2({
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
          field: params.term,
          vessel: $('#fromvessel').val(), /* Lo que escribe el usuario */
          trucks: 1 /* Muestra todas las naves del sistema */
        };
      },
      processResults: function (data) {
        /* Valida que el campo contenga datos de camiones disponibles para el roleo */
        if(data.length == 1){
          Swal.fire({
            title: 'Oops...',
            html: 'No existen camiones disponibles para rolear.',
            icon: 'warning'
          });

          return {
            results: []
          };
        }

        return {
          results: data
        };
      },
      cache: true
    }
  });

  $('#fromvessel').on('change', function () {
    const vessel = $(this).val();

    if (vessel != '-') {
      $.ajax({
        url: '../controllers/vesselInfoController.php',
        method: 'POST',
        data: {id: vessel},
        success: function (response) {
          $('#info-fromvessel').html(`${response}`);
        },
        error: function () {
          $('#info-fromvessel').html(`<div class="card shadow-sm border-0 mb-3">
              <div class="card-body text-danger">
                Error al obtener la información.
              </div>
            </div>
          `);
        }
      });
    }else{
      $('#info-fromvessel').html('');
    }
  });

  $('#tovessel').on('change', function () {
    const vessel = $(this).val();

    if (vessel != '-') {
      $.ajax({
        url: '../controllers/vesselInfoController.php',
        method: 'POST',
        data: {id: vessel},
        success: function (response) {
          $('#info-tovessel').html(`${response}`);
        },
        error: function () {
          $('#info-tovessel').html(`<div class="card shadow-sm border-0 mb-3">
              <div class="card-body text-danger">
                Error al obtener la información.
              </div>
            </div>
          `);
        }
      });
    }else{
      $('#info-tovessel').html('');
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
