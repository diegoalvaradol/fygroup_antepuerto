<?php
//header("Location: maintenance.php");
//exit;

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$port = new internationalChargue();
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
    <title>FYGroup | Carga Internacional</title>

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
                <div class="container-fluid-custom">
                    <!-- Breadcrumb -->
                    <?= menu::breadcrumb(); ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Carga Internacional</h1>
                    <div class="col-sm-6">
                      <div class="alert custom-alert-warning d-flex align-items-center justify-content-between" role="alert">
                        <div class="d-flex align-items-center">
                          <div class="icon me-4" style="padding-right: 10px; color:#eab308;">
                            <i class="fa-solid fa-circle-info fa-xl me-3"></i>
                          </div>
                          <div>
                            <strong>Información:</strong></br>
                            Formulario de carga y contenedores provenientes desde el exterior.
                          </div>
                        </div>

                        <div class="flag-badge ms-3">
                          <img src="https://flagcdn.com/w20/ar.png" alt="Argentina">
                          <span>AR</span>
                        </div>
                      </div>
                    </div>

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
                                        <form class="form-container" id="inInternationalContainerForm">
                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <label for="countervessel" class="mr-2 text-gray-800 font-weight-bold">N° de Camión</label>
                                                    <input type="text" class="form-control form-control-user" id="countervessel" name="countervessel" placeholder="Ingresa número" style="max-width: 150px;">
                                                    <small class="text-danger" id="error-countervessel"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="vessel" class="text-gray-800 font-weight-bold">Motonave</label>
                                                    <i class="fas fa-info-circle text-info" role="right" data-toggle="popover" data-trigger="hover focus" data-placement="right" data-content="Solo muestra aquellas motonaves que están en estado abierto."></i>
                                                    <select class="form-control select2 form-control-user" id="vessel" name="vessel"></select>
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
                                                    <select class="form-control select2 form-control-user" id="carplate" name="carplate"></select>
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
                                                    <select class="form-control select2 form-control-user" id="exporter" name="exporter"></select>
                                                    <small class="text-danger" id="error-exporter"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="palletsquantity" class="text-gray-800 font-weight-bold">Pallets</label>
                                                    <input type="number" class="form-control form-control-user" id="palletsquantity" name="palletsquantity" min="0" max="30" step="1" oninput="validarMaximo(this)" placeholder="N° de Pallets">
                                                    <small class="text-danger" id="error-palletsquantity"></small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="drivername" class="text-gray-800 font-weight-bold">Nombre de Conductor</label>
                                                    <input type="text" class="form-control form-control-user" id="drivername" name="drivername" placeholder="Nombre Conductor">
                                                    <small class="text-danger" id="error-drivername"></small>
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="cellphonedriver" class="text-gray-800 font-weight-bold">N° de Teléfono</label>
                                                    <div class="input-group">
                                                      <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-default"><img src="https://flagcdn.com/w20/ar.png" alt="Chile" style="width:20px; height:auto; margin-right:5px;">+54</span>
                                                      </div>
                                                      <input type="tel" class="form-control form-control-user" id="cellphonedriver" name="cellphonedriver" maxlength="11" pattern="\d{11}" oninput="limitarTelefono(this)" placeholder="9 XXX XXX XXXX">
                                                    </div>

                                                    <small class="text-danger" id="error-cellphonedriver"></small>
                                                </div>
                                            </div>

                                            <input type="hidden" id="digitedby" name="digitedby" value="<?php echo $_SESSION['user']['run']; ?>">
                                            <button id="loadBtn" type="button" class="btn btn-primary btn-sm btn-user" onclick="saveIntContainer()">
                                              <span id="loadBtnText"><i class="fas fa-check-circle"></i> Guardar</span>
                                              <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            </button>
                                            <button type='button' class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-eraser'></i> Limpiar</button>
                                      </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Contenedores Internacional -->
                    <?php echo $port->tableContainerInternational(); ?>
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

/* Valida maxima cantidad de pallets */
function validarMaximo(input) {
  if (parseInt(input.value) > 40) {
    input.value = 40;
  }
  if (parseInt(input.value) < 0) {
    input.value = 0;
  }
}

/* Restringe el numero de telefono a 13 numeros */
function limitarTelefono(input) {
  // Elimina cualquier caracter no numérico
  input.value = input.value.replace(/\D/g, '');

  // Limita a 13 caracteres
  if (input.value.length > 11) {
    input.value = input.value.slice(0, 11);
  }
}

var saveIntContainer = function() {
  const form = document.getElementById('inInternationalContainerForm');
  const formData = new FormData(form);
  let hasError = false;
  const btn = $('#loadBtn');
  const text = $('#loadBtnText');
  const spinner = $('#loadBtnSpinner');

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
      url: '../controllers/internationalChargueController.php',
      data: $('#inInternationalContainerForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          text: '¡Contenedor ingresado con éxito!',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = '<?php echo generateMkey('enter_container_international'); ?>';
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al ingresar el contenedor.',
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

var exportExcel = function(nave, condicion, exportador) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../controllers/internationalChargueDownloadExcelController.php';
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
        window.location = '<?php echo generateMkey('enter_container_international'); ?>';
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

$(document).ready(function() {
  $('#vessel').select2({
    placeholder: 'Seleccione una motonave...',
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
    placeholder: 'Seleccione una patente...',
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
          $('#info-vessel').html(`${response}`);
        },
        error: function () {
          $('#info-vessel').html(`<div class="card shadow-sm border-0 mb-3">
              <div class="card-body text-danger">
                'Error al obtener la información.'
              </div>
            </div>
          `);
        }
      });

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
    }else{
      $('#info-vessel').html('');
      $('#countervessel').val(null);
    }
  });

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
    placeholder: 'Seleccione una patente...',
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
</script>
