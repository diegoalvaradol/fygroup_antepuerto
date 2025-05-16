<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/class.outerPort.php';
require_once __DIR__ . '/../models/class.config.php';

$db   = (new Database())->getConnection();
$port = new outerPort($db);
$cfg  = new cfg($db);

$infoCfg = json_decode($cfg->getInfo(1), true);
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
    <title>Ingreso Termos</title>

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
                        <a class="collapse-item" href="enter_container_port.php">Ingreso Contenedores</a>
                        <a class="collapse-item" href="enter_thermo_port.php">Ingreso Termos</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Puerto Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePuerto" aria-expanded="true" aria-controls="collapsePuerto">
                    <i class="fas fa-fw fa-ship"></i>
                    <span>Puerto</span>
                </a>
                <div id="collapsePuerto" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Items:</h6>
                        <a class="collapse-item" href="enter_ship.php">Naves</a>
                        <a class="collapse-item" href="enter_ship_line.php">Lineas</a>
                        <a class="collapse-item" href="enter_port.php">Puertos</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Programación Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProgramacion" aria-expanded="true" aria-controls="collapseProgramacion">
                    <i class="fas fa-fw fa-file-pdf"></i>
                    <span>Planificación</span>
                </a>
                <div id="collapseProgramacion" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Items:</h6>
                        <a class="collapse-item" href="program_tpc.php">Planificación Naviera TPC</a>
                        <!-- <a class="collapse-item" href="program_maersk.php">Programación Maersk</a> -->
                        <!-- <a class="collapse-item" href="program_msc.php">Programación MSC</a> -->
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
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#infoModal" style="color: #0483cd;">
                                    <i class="fas fa-circle-info fa-sm fa-fw mr-2" style="color: #0483cd;"></i>Acerca del Sistema
                                </a>
                                <div class="dropdown-divider"></div>
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
                    <h1 class="h3 mb-1 text-gray-800">Formulario Ingreso de Termos <button type='button' class='btn btn-primary btn-user' style='float:right' onclick="window.location.href='enter_container_port.php';"><i class='fas fa-fw fa-truck'></i> Ingreso de Contenedores</button></h1>
                    <p class="mb-4">Formulario de ingreso de camiones arrivados a antepuerto. Este listado contempla todos aquellos camiones del tipo contenedor que han arrivado a antepuerto panul,
                        puedes revisar nuestro archivo online en el siguiente link: <a href="https://1drv.ms/x/s!AsvILqv5w9sBgQOOUx4TD7XdflNS?e=J5Bf0s" target="__blank">Listado Termos En Línea</a>.
                    </p>

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
                                        <form class="form-container" id="inTermoForm">
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <select class="form-control select2" id="vessel" name="vessel"></select>
                                                    <small class="text-danger" id="error-vessel"></small>
                                                </div>
                                                <div class="col-sm-6">
                                                    <small class="text-black" id="info-vessel"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-control-user" id="carplate" name="carplate" placeholder="Patente">
                                                    <small class="text-danger" id="error-carplate"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-control-user" id="guidenumber" name="guidenumber" placeholder="N° de Guía">
                                                    <i class="fas fa-info-circle text-info" role="button" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="right" data-bs-content="Si el camión trae mas de una guía saparalo con una coma. (Ej: 123, 456)"></i>
                                                    <small class="text-danger" id="error-guidenumber"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <select class="form-control select2 form-control-user" id="exporter" name="exporter"></select>
                                                    <small class="text-danger" id="error-exporter"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <input type="number" class="form-control form-control-user" id="palletsquantity" name="palletsquantity" min="0" max="30" step="1" placeholder="N° de Pallets">
                                                    <small class="text-danger" id="error-palletsquantity"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <input type="datetime-local" class="form-control form-control-user" id="datein" name="datein">
                                                    <small class="text-grey">Fecha y Hora de Entrada</small>
                                                    <small class="text-danger" id="error-datein"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <select class="form-control select2 form-control-user" id="comodity" name="comodity">
                                                        <option selected>Seleccione una condición...</option>
                                                        <option value="No Fumigado">No Fumigado</option>
                                                        <option value="USDA">USDA</option>
                                                        <option value="System Approach">System Approach</option>
                                                    </select>
                                                    <small class="text-danger" id="error-comodity"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-control-user" id="booking" name="booking" placeholder="N° de Booking">
                                                    <small class="text-danger" id="error-booking"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-control-user" id="stay" name="stay" placeholder="Estadía">
                                                    <small class="text-danger" id="error-stay"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-control-user" id="observations" name="observations" placeholder="Observaciones">
                                                    <small class="text-danger" id="error-observations"></small>
                                                </div>
                                            </div>

                                            <input type="hidden" id="origin" name="origin" value="2">
                                            <button type='button' class='btn btn-primary btn-user btn-block' onclick="saveInTermo()"><i class='fas fa-solid fa-check-circle'></i> Ingresar</button>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Listado de Termos -->
                    <?php $tableThermos = $port->getTableThermo(); ?>
                    <?php echo $tableThermos; ?>
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
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <small><b>Nombre: </b><?php echo $infoCfg['name']; ?></small>
                    <br>
                    <small><b>Versión: </b><?php echo $infoCfg['version']; ?></small>
                    <br>
                    <small><b>Compilación: </b><?php echo $infoCfg['compilation']; ?></small>
                    <br>
                    <small><b>Lanzamiento: </b> 01 de mayo de 2025.</small>
                    <br>
                    <small><b>Últ. Actualización: </b> 06 de mayo de 2025.</small>
                    <br>
                    <small><b>Autor: </b> Diego Alvarado López.</small>
                    <br>
                    <small><b>Programador y Diseñador: </b> Diego Alvarado López.</small>
                    <br>
                    <small><b> Contactar al Whatsapp: </b><a href="https://wa.me/56923816700?text=Hola%2C%20quiero%20más%20información%20sobre%20el%20producto" target="_blank"><i class="fas fa-brands fa-whatsapp" style="color: #63E6BE;"></i><b>+56923816700</b></a></small>
                    <br>
                    <small><b> Correo: </b><a href="mailto:diego.alvaraado@gmail.com" target="_blank"><b><i class="fas fa-solid fa-envelope" style="color: #1768a6;"></i></i> diego.alvaraado@gmail.com </b></a></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Añadir hora de salida del camión termo -->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="addHourTermoModal" style="display:none; position:fixed; width:50%; top:20%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
    <h4 id="h4-departure-hour"></h4>
    <form id="addHourTermoForm">
        <div class="form-group row">
            <div class="col-sm-12">
              <label id="label-stay" style="color:darkorange;"></label>
              </br>
              <label>Hora de salida:</label>
              <input type="datetime-local" class="form-control form-control-user" id="dateout" name="dateout">
              <small class="text-danger" id="error-dateout"></small>
            </div>
        </div>

        <input type="hidden" id="rowId" name="rowId">
        <input type="hidden" id="originId" name="originId">
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

var editTermoHour = function(id) {
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
      $('#h4-departure-hour').html('Registrar Salida Camión: '+data.car_plate);
      $('#label-stay').html(`Estadía: ${dias} días con ${horas} horas y ${minutos} minutos.`);

      /* Mostrar overlay y modal */
      $('#modalOverlay').fadeIn(200);
      $('#addHourTermoModal').fadeIn(200);
    },
    error: function() {
      alert('Error al cargar los datos.');
    }
  });
}

var closeModal = function() {
  $('#addHourTermoModal').fadeOut(200);
  $('#modalOverlay').fadeOut(200);
}

var saveChanges = function() {
  const form = document.getElementById('addHourTermoForm');
  const formData = new FormData(form);
  let hasError = false;

  document.querySelectorAll('small.text-danger').forEach(el => el.innerText = '');
  document.querySelectorAll('.form-control-user').forEach(el => el.classList.remove('is-invalid'));

  /* Validar si algún campo está vacío */
  for (let [key, value] of formData.entries()) {
    if (!value.trim()) {
      const errorElement = document.getElementById('error-' + key);

      if (errorElement) {
        errorElement.innerText = 'Este campo es obligatorio.';
        const inputElement = form.querySelector(`[name="${key}"]`);
        inputElement.classList.class('is-invalid');
      }

      hasError = true;
    }
  }

  /* Hace envio de los datos a traves del formulario */
  if(!hasError){
    $.ajax({
      url: '../controllers/outerPortUpdateController.php',
      data: $('#addHourTermoForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          text: '¡Hora de salida ingresada con éxito!',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = 'enter_thermo_port.php';
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

var saveInTermo = function() {
  const form = document.getElementById('inTermoForm');
  const formData = new FormData(form);
  let hasError = false;

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
    $.ajax({
      url: '../controllers/outerPortController.php',
      data: $('#inTermoForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OKT'){
        Swal.fire({
          title: '¡Éxito!',
          text: '¡Ingreso de termo registrado exitosamente!',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = 'enter_thermo_port.php';
        });
      }else if(x == 'NOOKT') {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al registrar el ingreso del termo.',
          icon: 'error',
          cancelButtonColor: '#d33',
        });
      }
    });
  }
}

var exportExcel = function(nave, condicion, exportador) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../controllers/thermoDownloadExcelController.php';
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

$(document).ready(function() {
  setInterval(actualizarReloj, 1000);
  actualizarReloj(); /* Primera llamada */

  $('#vessel').select2({
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
    placeholder: 'Seleccione una exportador...',
    allowClear: true,
    tags: true,
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

  $('#vessel').on('change', function () {
    const vessel = $(this).val();

    if (vessel != '-') {
      $.ajax({
        url: '../controllers/vesselInfoController.php',
        method: 'POST',
        data: {id: vessel},
        success: function (response) {
          $('#info-vessel').html(response);
        },
        error: function () {
          $('#info-vessel').html('Error al obtener la información.');
        }
      });
    }else{
      $('#info-vessel').html('');
    }
  });
});
</script>