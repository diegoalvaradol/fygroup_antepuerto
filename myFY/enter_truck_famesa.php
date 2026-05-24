<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$famesa = new famesa();
$cfg = new cfg();
$user = new user();
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
$paginaActual = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$modals = new Modals($infoCfg, $arrayDivision, $releasedTime, $updateTime);
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>FYGroup | Ingreso Cámiones</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/fygroup.css" rel="stylesheet">

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
                    <h1 class="h3 mb-1 text-gray-800">Ingreso Camiones Famesa</h1>

                    <div class="col-sm-6">
                      <div class="alert custom-alert-info d-flex align-items-center" role="alert">
                        <?php echo $alerts->customAlert('info', 'Atención', 'Los camiones que superen 1 día de estadía en antepuerto serán resaltados en rojo.'); ?>
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
                                                <i class="fas fa-info-circle text-info" role="right" data-toggle="popover" data-trigger="hover focus" data-placement="right" data-content="Solo muestra aquellas motonaves que no hayan zarpado de puerto."></i>
                                                <small class="text-danger" id="error-vessel"></small>
                                            </div>

                                            <div class="col-sm-3">
                                                <label for='voyage' class='text-gray-800 font-weight-bold'>Información de Motonave</label>
                                                </br>
                                                <small id="info-vessel"></small>
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
                                        <input type="hidden" id="createdby" name="createdby" value="<?php echo $_SESSION['user']['run']; ?>">
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

    <!-- Scroll to Top Button -->
    <?php echo $top; ?>

    <!-- Modales -->
    <?php echo $modals->render();?>

    <!-- SALIDA PUERTO -->
    <div id="modalAddHourDeparturePort" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="addHourDeparturePort" class="modal-box" style="display:none; position:fixed; width:80%; top:10%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
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
    <div id="addHourArrivalDeposit" class="modal-box" style="display:none; position:fixed; width:80%; top:10%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
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
    <div id="addHourDepartureDeposit" class="modal-box" style="display:none; position:fixed; width:80%; top:10%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
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
    <script src="../assets/js/fygroup.js"></script>

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
        window.location = '<?php echo generateMkey('enter_truck_famesa'); ?>';
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
