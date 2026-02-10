<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$db   = (new Database())->getConnection();
$ship = new ship($db);
$cfg  = new cfg($db);
$user = new user($db);

$infoCfg         = json_decode($cfg->getInfo(1), true);
$admin           = $user->isAdmin($_SESSION["user"]["run"]);
$releasedTime    = new DateTime($infoCfg['released_date']);
$updateTime      = new DateTime($infoCfg['update_date']);
$sideBarSSL      = menu::sideBarSSL();
$secondTapBarSSL = menu::secondTapBarSSL();
$footer          = menu::footerSSL();
$top             = UIComponents::scrollToTopButton();
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
    <title>SSL | Naves</title>

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
                <?php echo $secondTapBarSSL; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Naves</h1>

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
                                        <form class="form-container" id="shipForm">
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for='vessel' class='text-gray-800 font-weight-bold'>Motonave</label>
                                                    <input type="text" class="form-control form-control-user" id="vessel" name="vessel" placeholder="Polar Argentina">
                                                    <small class="text-danger" id="error-vessel"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for='line' class='text-gray-800 font-weight-bold'>Linea Naviera</label>
                                                    <select class="form-control select2 form-control-user" id="line" name="line">
                                                        <option value="-">Seleccione una línea...</option>
                                                    </select>
                                                    <small class="text-danger" id="error-line"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for='voyage' class='text-gray-800 font-weight-bold'>N° de Viaje</label>
                                                    <input type="text" class="form-control form-control-user" id="voyage" name="voyage" placeholder="525N">
                                                    <small class="text-danger" id="error-voyage"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for='eta' class='text-gray-800 font-weight-bold'>Fecha y Hora de Arrivo</label>
                                                    <input type="datetime-local" class="form-control form-control-user" id="eta" name="eta">
                                                    <small class="text-danger" id="error-eta"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for='etd' class='text-gray-800 font-weight-bold'>Fecha y Hora de Zarpe</label>
                                                    <input type="datetime-local" class="form-control form-control-user" id="etd" name="etd">
                                                    <small class="text-danger" id="error-etd"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for='pol' class='text-gray-800 font-weight-bold'>Puerto de Carga</label>
                                                    <select class="form-control select2 form-control-user" id="pol" name="pol">
                                                        <option value="-">Seleccione un puerto...</option>
                                                    </select>
                                                    <small class="text-danger" id="error-pol"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for='pod' class='text-gray-800 font-weight-bold'>Puerto de Descarga</label>
                                                    <select class="form-control select2 form-control-user" id="pod" name="pod">
                                                        <option value="-">Seleccione un puerto...</option>
                                                    </select>
                                                    <small class="text-danger" id="error-pod"></small>
                                                </div>
                                            </div>

                                            <button id="loadBtn" type="button" class="btn btn-primary btn-sm btn-user" onclick="saveShip()">
                                              <span id="loadBtnText"><i class="fas fa-solid fa-check-circle"></i> Guardar</span>
                                              <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            </button>
                                            <button type='button' class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-undo'></i> Limpiar</button>
                                        </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Listado de Naves -->
                    <?php echo $ship->getTableShip(); ?>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Deseas cerrar sesión?</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
              <span>×</span>
            </button>
                </div>
                <div class="modal-body">Selecciona 'Cerrar sesión' si realmente deseas hacerlo.</div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger" href="logout.php"><i class='fas fa-solid fa-sign-out-alt'></i> Cerrar sesión</a>
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

    <!-- Modal Editar Motonave-->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="editShipModal" style="display:none; position:fixed; width:50%; top:20%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
    <h4>Editar Motonave</h4>
    <form id="editShipForm">
        <div class="form-group row">
            <div class="col-sm-6">
                <label>Motonave:</label>
                <input type="text" class="form-control form-control-user" id="shipName" name="shipName">
            </div>
        </div>

        <div class="form-group row">
          <div class="col-sm-6">
                <label>Naviera:</label>
                <select class="form-control select2 form-control-user" id="shipLine" name="shipLine">
                  <option value="-">Seleccione una linea...</option>
                </select>
            </div>

            <div class="col-sm-6">
                <label>N° Viaje:</label>
                <input type="text" class="form-control form-control-user" id="shipVoyage" name="shipVoyage">
            </div>
        </div>


        <div class="form-group row">
            <div class="col-sm-6">
                <label>Fecha y Hora de Arrivo:</label>
                <input type="datetime-local" class="form-control form-control-user" id="shipEta" name="shipEta">
            </div>
            <div class="col-sm-6">
                <label>Fecha y Hora de Zarpe:</label>
                <input type="datetime-local" class="form-control form-control-user" id="shipEtd" name="shipEtd">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-6">
                <label>Puerto de Carga:</label>
                <select class="form-control select2 form-control-user" id="shipPOL" name="shipPOL">
                  <option value="-">Seleccione un puerto...</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label>Puerto de Descarga:</label>
                <select class="form-control select2 form-control-user" id="shipPOD" name="shipPOD">
                  <option value="-">Seleccione un puerto...</option>
                </select>
            </div>
        </div>
        <br>
        <input type="hidden" id="shipId" name="shipId">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

var editShip = function(id) {
  $.ajax({
    url: '../controllers/shipEditController.php',
     type: 'POST',
     data: { id: id },
     dataType: 'json',
     success: function(data) {
      $('#shipId').val(data.id);
      $('#shipName').val(data.vesselName);
      $('#shipVoyage').val(data.voyage);
      $('#shipEta').val(data.eta);
      $('#shipEtd').val(data.etd);
      $('#shipLine').empty();
      $('#shipLine').append($('<option>', {value: data.shipLine, text: data.nameLine}));
      $('#shipPOL').empty();
      $('#shipPOL').append($('<option>', {value: data.pol, text: data.polCity +' - '+ data.polCountry}));
      $('#shipPOD').empty();
      $('#shipPOD').append($('<option>', {value: data.pod, text: data.podCity +' - '+ data.podCountry}));
      $('#scrollTopBtn').trigger('click');

      /* Mostrar overlay y modal */
      $('#modalOverlay').fadeIn(200);
      $('#editShipModal').fadeIn(200);
    },
    error: function() {
      alert('Error al cargar los datos.');
    }
  });
}

var closeModal = function() {
  $('#editShipModal').fadeOut(200);
  $('#modalOverlay').fadeOut(200);
}

var saveChanges = function() {
  $.ajax({
    url: '../controllers/shipUpdateController.php',
    data: $('#editShipForm').serialize(),
    type: 'POST',
  }).done(function(x) {
    if(x == 'OK'){
      Swal.fire({
        title: '¡Éxito!',
        text: '¡Motonave actualizada con éxito!',
        icon: 'success',
        confirmButtonColor: '#4CAF50'
      }).then((result) => {
        window.location = '<?php echo generateMkey('enter_ship'); ?>';
      });
    } else {
      Swal.fire({
        title: 'Oops...',
        text: 'Error al actualizar la motonave.',
        icon: 'error',
        cancelButtonColor: '#d33',
      });
    }
  });
}

var deleteShip = function(id) {
  Swal.fire({
    title: 'Eliminar Motonave.',
    text: '¿Estas seguro de eliminar esta motonave?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Si, elimimar!",
    cancelButtonText : 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../controllers/shipDeleteController.php',
        type: 'POST',
        data: { id: id },
      }).done(function(x) {
        if(x == 'OK'){
          Swal.fire({
            title: '¡Éxito!',
            text: '¡Motonave eliminada con éxito!',
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          }).then((result) => {
            window.location = '<?php echo generateMkey('enter_ship'); ?>';
          });
        } else if(x == 'NOOK') {
          Swal.fire({
            title: 'Oops...',
            text: 'Error al eliminar la motonave.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        } else if(x == 'NOOK2') {
          Swal.fire({
            title: 'Oops...',
            text: 'La motonave que tratas de eliminar se encuentra asociado a una ingreso de contenedor/termo registrado, favor revisa e intenta nuevamente.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        }
      });
    }
  });
}

var stackingShip = function(id, name, voyage, status) {
  var statusLabel = statusMsg = null;
  if(status == 1){
    statusLabel = 'Cerrar';
    statusMsg = 'cerrado';
  }else{
    statusLabel = 'Abrir';
    statusMsg = 'abierto';
  }

  Swal.fire({
    title: statusLabel + ' Embarque de Motonave.',
    html: 'Motonave: '+name+' - Viaje: '+voyage+'.'+'</br>'+'¿Estas seguro de '+statusLabel+' el embarque de esta motonave?',
    icon: 'info',
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Si, "+statusLabel+" embarque!",
    cancelButtonText : 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../controllers/shipEndStackingController.php',
        type: 'POST',
        data: {
          shipId: id,
          status: status
        },
      }).done(function(x) {
        if(x == 'OK'){
          Swal.fire({
            title: '¡Éxito!',
            html: 'Embarque de:'+'</br>'+'Motonave: '+name+' - Viaje: '+voyage+'</br>'+'Fue '+statusMsg+' con éxito.',
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          }).then((result) => {
            window.location = '<?php echo generateMkey('enter_ship'); ?>';
          });
        } else  {
          Swal.fire({
            title: 'Oops...',
            text: 'Error al finalizar el embarque de la motonave.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        }
      });
    }
  });
}

var saveShip = function() {
  const form = document.getElementById('shipForm');
  const formData = new FormData(form);
  let hasError = false;
  const btn = $('#loadBtn');
  const text = $('#loadBtnText');
  const spinner = $('#loadBtnSpinner');

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
    text.addClass('d-none');
    spinner.removeClass('d-none');
    btn.prop('disabled', true);

    $.ajax({
      url: '../controllers/shipController.php',
      data: $('#shipForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          text: '¡Motonave registrada con éxito!',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = '<?php echo generateMkey('enter_ship'); ?>';
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al registrar la motonave.',
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

  $('#line').select2({
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/shipLineJsonController.php',
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

  $('#pol, #pod').select2({
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/portsJsonController.php',
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

  $('#shipLine').select2({
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/shipLineJsonController.php',
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

  $('#shipPOL, #shipPOD').select2({
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/portsJsonController.php',
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