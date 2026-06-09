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
    <title>FYGroup | Lineas Navieras</title>

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
                                                <input type="text" class="form-control form-control-user" id="rutShipLine" name="rutShipLine" oninput="formatearRut(this)" maxlength="12" onblur="validaRut(this.value), verifyRut(this.value)" placeholder="11.222.333-0">
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
                                        <button type='button' class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-eraser'></i> Limpiar</button>
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

    <!-- Scroll to Top Button -->
    <?php echo $top; ?>

    <!-- Modales -->
    <?php echo $modals->render();?>

    <!-- Modal Editar Linea-->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="editLineModal" style="display:none; position:fixed; width: 80%; top:10%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
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
    <script src="../assets/js/fygroup.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
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

var verifyRut = function(rut) {
  if(rut !== ''){
    $.ajax({
      url: '../controllers/shipLineRutVerifyController.php',
      data: {
        rut: rut
      },
      type: "POST",
    }).done(function(x) {
      if(x == 'NOOK'){
        Swal.fire({
          title: 'Oops...',
          html: 'R.U.T <b>'+rut+'</b> ya se encuentra registrado.',
          icon: 'error',
          cancelButtonColor: '#d33',
        }).then((result) => {
          $('#rutShipLine').val('').focus();
        });
      }
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
          html: 'Linea <b>'+name+'</b> ya se encuentra registrado.',
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
        window.location = '<?php echo generateMkey('enter_ship_line'); ?>';
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
</script>
