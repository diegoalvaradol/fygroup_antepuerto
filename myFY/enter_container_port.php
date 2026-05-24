<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$port = new outerPort();
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
    <title>FYGroup | Ingreso Contenedores</title>

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
                    <!-- Breadcrumb -->
                    <?= menu::breadcrumb(); ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Contenedores</h1>

                    <div class="col-sm-6">
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
                                        <form class="form-container" id="inContainerForm">
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
                                                    <label for="carplate" class="text-gray-800 font-weight-bold">Patente</label>
                                                    <select class="form-control select2 form-control-user" id="carplate" name="carplate">
                                                      <option value="-">Seleccione una patente...</option>
                                                    </select>
                                                    <small class="text-danger" id="error-carplate"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="guidenumber" class="text-gray-800 font-weight-bold">N° de Guía</label>
                                                    <input type="text" class="form-control form-control-user" id="guidenumber" name="guidenumber" placeholder="N° de Guía (Ej: 123 ó 123, 456)">
                                                    <small class="text-danger" id="error-guidenumber"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="container" class="text-gray-800 font-weight-bold">Contenedor</label>
                                                    <input type="text" class="form-control form-control-user" id="container" name="container" minlength="11" maxlength="11" onblur="validarContenedor(this.value)" placeholder="Contenedor Ej:(UETU6168056)">
                                                    <small class="text-danger" id="error-container"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="sealnumber" class="text-gray-800 font-weight-bold">Sello de Naviera</label>
                                                    <input type="text" class="form-control form-control-user" id="sealnumber" name="sealnumber" placeholder="N° de Sello">
                                                    <small class="text-danger" id="error-sealnumber"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="exporter" class="text-gray-800 font-weight-bold">Exportador</label>
                                                    <select class="form-control select2 form-control-user" id="exporter" name="exporter" onchange="setAgency(this.value)">
                                                        <option value="-">Seleccione un exportador...</option>
                                                    </select>
                                                    <small class="text-danger" id="error-exporter"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="d-flex align-items-center">
                                                        <label for="agency" class="text-gray-800 font-weight-bold">Agencia</label>
                                                    </div>
                                                    <select class="form-control select2 form-control-user" id="agency" name="agency">
                                                      <option value="-">Seleccione una agencia...</option>
                                                    </select>
                                                    <small class="text-danger" id="error-agency"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="palletsquantity" class="text-gray-800 font-weight-bold">Pallets</label>
                                                    <input type="number" class="form-control form-control-user" id="palletsquantity" name="palletsquantity" min="0" max="40" step="1" oninput="validarMaximo(this)" value="20" placeholder="N° de Pallets">
                                                    <small class="text-danger" id="error-palletsquantity"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="cellphonedriver" class="text-gray-800 font-weight-bold">N° de Teléfono</label>
                                                    <div class="input-group">
                                                      <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-default"><img src="https://flagcdn.com/w20/cl.png" alt="Chile" style="width:20px; height:auto; margin-right:5px;">+56</span>
                                                      </div>
                                                      <input type="tel" class="form-control form-control-user" id="cellphonedriver" name="cellphonedriver" maxlength="9" pattern="\d{9}" oninput="limitarTelefono(this)" placeholder="9 XXXX XXXX">
                                                    </div>

                                                    <small class="text-danger" id="error-cellphonedriver"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="exporter" class="text-gray-800 font-weight-bold">Fecha y Hora de Entrada</label>
                                                    <input type="datetime-local" class="form-control form-control-user" id="datein" name="datein">
                                                    <small class="text-danger" id="error-datein"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="comodity" class="text-gray-800 font-weight-bold">Condición</label>
                                                    <select class="form-control select2 form-control-user" id="comodity" name="comodity">
                                                        <option value="-" selected>Seleccione una condición...</option>
                                                        <option value="No Fumigado">No Fumigado</option>
                                                        <option value="USDA">USDA</option>
                                                        <option value="System Approach">System Approach</option>
                                                    </select>
                                                    <small class="text-danger" id="error-comodity"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="booking" class="text-gray-800 font-weight-bold">Reserva (Booking)</label>
                                                    <input type="text" class="form-control form-control-user" id="booking" name="booking" placeholder="N° de Booking">
                                                    <small class="text-danger" id="error-booking"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="stay" class="text-gray-800 font-weight-bold">Estadía</label>
                                                    <input type="text" class="form-control form-control-user" id="stay" name="stay" placeholder="Estadía">
                                                    <small class="text-danger" id="error-stay"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="observations" class="text-gray-800 font-weight-bold">Observaciones</label>
                                                    <input type="text" class="form-control form-control-user" id="observations" name="observations" placeholder="Observaciones">
                                                    <small class="text-danger" id="error-observations"></small>
                                                </div>
                                            </div>

                                            <input type="hidden" id="origin" name="origin" value="1">
                                            <input type="hidden" id="cntId" name="cntId" value="0">
                                            <input type="hidden" id="isUpdate" name="isUpdate" value="0">
                                            <input type="hidden" id="createdby" name="createdby" value="<?php echo $_SESSION['user']['run']; ?>">
                                            <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
                                            <button id="loadBtn" type="button" class="btn btn-primary btn-sm btn-user" onclick="saveInContainer()">
                                              <span id="loadBtnText"><i class="fas fa-solid fa-check-circle"></i> Guardar</span>
                                              <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            </button>
                                            <button type='button' class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-undo'></i> Limpiar</button>
                                      </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Contenedores -->
                    <?php echo $port->tableContainer(); ?>
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

    <!-- Modal Añadir hora de salida del camión contenedor -->
    <div id="modalOverlay"></div>
    <div id="addHourContainerModal" class="custom-modal">
        <h4 id="h4-departure-hour"></h4>

        <form id="addHourContainerForm">
            <div class="form-group row">
                <div class="col-sm-12">
                    <p id="label-stay"></p>
                    <label>Hora de salida:</label>
                    <input type="datetime-local" class="form-control form-control-user" id="dateout" name="dateout">
                    <small class="text-danger" id="error-dateout"></small>
                </div>
            </div>

            <input type="hidden" id="rowId" name="rowId">
            <input type="hidden" id="originId" name="originId">
            <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">

            <button type="button" name="savechanges" class="btn btn-success btn-user btn-sm" onclick="saveChanges()">
                <i class='fas fa-solid fa-check-circle'></i> Guardar
            </button>

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
const inputContenedor = document.getElementById('container');

inputContenedor.addEventListener('input', function () {
  this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 11);
});

var validarContenedor = function (container){
  const regex = /^[A-Z]{4}\d{7}$/;

  if (container.length !== 11) {
    return false;
  }

  if (!regex.test(container)){
    return false;
  }

  /* Convertir letras a números según ISO 6346 */
  const letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  const valores = {};
  for (let i = 0; i < letras.length; i++) {
    /* Saltar múltiplos de 11 según el estándar */
    valores[letras[i]] = (i < 11) ? i : i + 1;
  }

  const base = 2; // factor base
  let suma = 0;
  for (let i = 0; i < 10; i++) {
    const char = container[i];
    const val = isNaN(char) ? valores[char] : parseInt(char);
    suma += val * Math.pow(base, i);
  }

  const digitoCalculado = (suma % 11) === 10 ? 0 : suma % 11;
  const digitoReal = parseInt(container[10]);

  if(digitoCalculado !== digitoReal){
    Swal.fire({
      title: 'Oops...',
      html: 'El número de contenedor: '+container+' es inválido. </br> ¿Deseas ingresarlo de todas formas?',
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#4CAF50',
      cancelButtonColor: '#d33',
      confirmButtonText: "Si, continuar!",
      cancelButtonText: "No, editar!",
      allowOutsideClick: false,
      allowEscapeKey: false
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: '¡Atención!',
          html: 'Contenedor: '+container+' ingresado con éxito.',
          icon: 'success'
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        $('#container').focus();
      }
    });
  }
}

var editContainerHour = function(id) {
  $.ajax({
    url: '../controllers/outerPortEditController.php',
     type: 'POST',
     data: { id: id },
     dataType: 'json',
     success: function(data) {
      const fechaIngreso = new Date(data.arrival_date); /* Reemplaza con la fecha y hora reales */
      const ahora = new Date();
      const msDiff = ahora - fechaIngreso;
      const totalMinutos = Math.floor(msDiff / (1000 * 60));
      const dias = Math.floor(totalMinutos / (60 * 24));
      const horas = Math.floor((totalMinutos % (60 * 24)) / 60);
      const minutos = totalMinutos % 60;

      $('#rowId').val(data.row_id);
      $('#originId').val(data.origin);
      $('#h4-departure-hour').html('Registrar Salida Camión: '+data.car_plate).css('font-weight', 'bold').css('font-size', '20px');
      $('#label-stay').html(`Estadía: ${dias} días con ${horas} horas y ${minutos} minutos.`).css('font-weight', 'bold').css('color', (dias >= 1) ? 'red' : 'green');
      $('#dateout').val(data.departure_date ? data.departure_date : '');

      /* Mostrar overlay y modal */
      $('#modalOverlay').fadeIn(200);
      $('#addHourContainerModal').fadeIn(200);
    },
    error: function() {
      alert('Error al cargar los datos.');
    }
  });
}

var closeModal = function() {
  $('#addHourContainerModal').fadeOut(200);
  $('#modalOverlay').fadeOut(200);
}

var saveChanges = function() {
  const form = document.getElementById('addHourContainerForm');
  const formData = new FormData(form);
  let hasError = false;
  var paginaActual = $('input[name="page"]').val();

  document.querySelectorAll('small.text-danger').forEach(el => el.innerText = '');
  document.querySelectorAll('.form-control-user').forEach(el => el.classList.remove('is-invalid'));

  /* Validar si algún campo está vacío */
  for (let [key, value] of formData.entries()) {
    const inputElement = form.querySelector(`[name="${key}"]`);
    const errorElement = form.querySelector(`#error-${key}`);
    const isSelect2 = inputElement && $(inputElement).hasClass('select2-hidden-accessible');
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

  /* Hace envio de los datos a traves del formulario */
  if(!hasError){
    $.ajax({
      url: '../controllers/outerPortUpdateController.php',
      data: $('#addHourContainerForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          text: '¡Hora de salida ingresada con éxito!',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = '<?php echo generateMkey('enter_container_port'); ?>&page=' + paginaActual;
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al ingresar la hora de salida.',
          icon: 'error',
          cancelButtonColor: '#d33',
        });
      }
    });
  }
}

var saveInContainer = function() {
  const form = document.getElementById('inContainerForm');
  const formData = new FormData(form);
  const isUpdate = $('#isUpdate').val();
  let hasError = false;
  const btn = $('#loadBtn');
  const text = $('#loadBtnText');
  const spinner = $('#loadBtnSpinner');
  var container = $('#container').val();
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
    text.addClass('d-none');
    spinner.removeClass('d-none');
    btn.prop('disabled', true);

    $.ajax({
      url: '../controllers/outerPortController.php',
      data: $('#inContainerForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(isUpdate == 1){
        if(x == 'OKUC'){
          Swal.fire({
            title: '¡Éxito!',
            html: '¡Contenedor <b>'+container+'</b> actualizado con éxito!',
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          }).then((result) => {
            window.location = '<?php echo generateMkey('enter_container_port'); ?>&page=' + paginaActual;
          });
        }else if(x == 'NOOKUC') {
          Swal.fire({
            title: 'Oops...',
            html: 'Error al actualizar el contenedor: <b>'+container+'</b>.',
            icon: 'error',
            cancelButtonColor: '#d33',
          }).then(() => {
            text.removeClass('d-none');
            spinner.addClass('d-none');
            btn.prop('disabled', false);
          });
        }
      } else {
        if(x == 'OKC'){
          Swal.fire({
            title: '¡Éxito!',
            html: '¡Contenedor <b>'+container+'</b> ingresado con éxito!',
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          }).then((result) => {
            window.location = '<?php echo generateMkey('enter_container_port'); ?>&page=' + paginaActual;
          });
        }else if(x == 'NOOKC') {
          Swal.fire({
            title: 'Oops...',
            html: 'Error al ingresar el contenedor: <b>'+container+'</b>.',
            icon: 'error',
            cancelButtonColor: '#d33',
          }).then(() => {
            text.removeClass('d-none');
            spinner.addClass('d-none');
            btn.prop('disabled', false);
          });
        }
      }
    });
  }
}

var deleteTruck = function(id) {
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
        url: '../controllers/outerPortDeleteController.php',
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
            window.location = '<?php echo generateMkey('enter_container_port'); ?>';
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
            text: 'El camión que tratas de eliminar tiene una hora de salida registrada.',
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
  form.action = '../controllers/containerDownloadExcelController.php';

  const fields = {
    nave: nave || '',
    patente: patente || '',
    guia: guia || '',
    division: '<?= $_SESSION['user']['division']; ?>',
    cliente: '<?= $_SESSION['user']['run']; ?>'
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

var editContainer = function(id) {
  $.ajax({
    url: '../controllers/containerEditController.php',
     type: 'POST',
     data: { id: id },
     dataType: 'json',
     success: function(data) {
      $('#isUpdate').val(1);
      $('#cntId').val(id);
      $('#countervessel').val(data.counter_vessel);
      $('#vessel').empty();
      $('#vessel').append($('<option>', {value: data.vessel_id, text: data.vessel_name + ' (Viaje: ' + data.voyage + ')'}));
      $('#vessel').trigger('change');
      $('#carplate').empty();
      $('#carplate').append($('<option>', {value: data.car_plate, text: data.car_plate}));
      $('#guidenumber').val(data.guide_number);
      $('#container').val(data.container);
      $('#sealnumber').val(data.seal_number);
      $('#exporter').empty();
      $('#exporter').append($('<option>', {value: data.exporter, text: data.exporter}));
      $('#agency').empty();
      $('#agency').append($('<option>', {value: data.agency, text: data.agency}));
      $('#palletsquantity').val(data.pallets_quantity);
      $('#cellphonedriver').val(data.cellphone_driver);
      $('#datein').val(data.arrival_date);
      $('#comodity').val(data.comodity);
      $('#booking').val(data.booking);
      $('#stay').val(data.stay);
      $('#observations').val(data.observations);
      $('#loadBtn').addClass('btn-info');
      $('#loadBtnText').html('<i class="fas fa-solid fa-check-circle"></i> Actualizar Contenedor');
      $('#scrollTopBtn').trigger('click');
    },
    error: function() {
      alert('Error al cargar los datos.');
    }
  });
}

var setAgency = function(exporter){
  $.ajax({
    url: '../controllers/setAgencyController.php',
    data: {
      exporter: exporter
    },
    type: 'POST',
  }).done(function(name) {
    if(name !== ''){
      $('#agency').empty();
      $('#agency').append($('<option>', {value: name, text: name}));
    }
  });
}

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
        window.location = '<?php echo generateMkey('enter_container_port'); ?>';
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

  $('#carplate').select2({
    allowClear: true,
    tags: true,
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

  $('#exporter').select2({
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/exporterJsonController.php',
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

  $('#agency').select2({
    allowClear: true,
    tags: false,
    width: '100%',
    ajax: {
      url: '../controllers/agencyJsonController.php',
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
          url: '../controllers/setCounterVesselController.php',
          method: 'POST',
          data: {id: vessel, origin: 1},
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
