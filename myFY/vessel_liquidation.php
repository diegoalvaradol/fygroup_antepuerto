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
    <meta name="Vista Formulario de Registro de Nuevo Usuario" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/favicon-256x256.png"/>
    <title>FYGroup | Liquidación de Nave</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Liquidación de Nave</h1>
                    <p class="mb-4">Acá podrás obtener la liquidación de carga por nave y/o exportador.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <!-- Custom Text Color Utilities -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Formulario de Búsqueda</h6>
                                </div>

                                <div class="card-body">
                                    <form class="form-container" id="vesselReportForm">
                                        <div class="form-group row">
                                          <!-- Exportador -->
                                          <div class="col-md-3">
                                            <label for="exporter" class="text-gray-800 font-weight-bold">Exportador <em>(Opcional)</em></label>
                                            <select class="form-control select2" id="exporter" name="exporter">
                                              <option value="-">Seleccione un exportador...</option>
                                            </select>
                                          </div>

                                          <!-- Motonave -->
                                          <div class="col-md-3">
                                            <label for="vessel" class="text-gray-800 font-weight-bold">Motonave</label>
                                            <select class="form-control select2" id="vessel" name="vessel">
                                              <option value="-">Seleccione una motonave...</option>
                                            </select>
                                          </div>

                                          <!-- Información Motonave -->
                                          <div class="col-md-3">
                                            <label class="text-gray-800 font-weight-bold">Información de Motonave</label>
                                            <small id="info-vessel"></small>
                                          </div>

                                          <!-- Liquidación y Excel-->
                                          <div class="col-md-3">
                                            <label class="text-gray-800 font-weight-bold d-block">Liquidación Motonave</label>
                                            <div id="btnPdfVesselLiquidation"></div>
                                          </div>
                                      </div>
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
let inactivityTime = function () {
  let time;
  let warningTimeout = 30 * 60 * 1000; // 30 min
  let countdownTime = 30;

  function startTimer() {
    window.addEventListener('click', resetTimer, false);
    window.addEventListener('keypress', resetTimer, false);
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
      willClose: () => clearInterval(timerInterval)
    }).then((result) => {
      if (result.isConfirmed) {
        resetTimer();
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

/* ===== RELOJ ===== */
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
        window.location = '<?php echo generateMkey('vessel_liquidation'); ?>';
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
  // iniciar inactivity
  inactivityTime();

  // reloj
  setInterval(actualizarReloj, 1000);
  actualizarReloj();

  $('#vessel').select2({
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
          search: params.term,
          finished: 1
        };
      },
      processResults: function (data) {
        return { results: data };
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
          search: params.term
        };
      },
      processResults: function (data) {
        return { results: data };
      },
      cache: true
    }
  });

  $('#vessel').on('change', cargarLiquidacion);
  $('#exporter').on('change', cargarLiquidacion);

  function cargarLiquidacion() {
    const vessel   = $('#vessel').val();
    const exporter = $('#exporter').val();

    if (!vessel || vessel === '-') {
      $('#info-vessel').html('');
      $('#btnPdfVesselLiquidation').html('');

      return;
    }

    /* Info motonave */
    $.ajax({
      url: '../controllers/vesselInfoController.php',
      method: 'POST',
      data: { id: vessel },
      success: function (response) {
        $('#info-vessel').html(response);
      },
      error: function () {
        $('#info-vessel').html('<div class="text-danger">Error al obtener la información.</div>');
      }
    });

    /* Liquidación */
    $.ajax({
      url: '../controllers/getLiquidacionController.php',
      method: 'POST',
      data: {
        id: vessel,
        exporter: exporter
      },
      success: function (response) {
        $('#btnPdfVesselLiquidation')
          .html(response)
          .css('margin', '0 auto')
          .css('display', 'inline-block');
      },
      error: function () {
        $('#btnPdfVesselLiquidation').html(`
          <div class="alert alert-warning">
            No se pudo generar la liquidación.
          </div>
        `);
      }
    });
  }
});

var exportExcel = function(nave) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../controllers/shipsReportDownloadExcelController.php';
  form.style.display = 'none';

  const fields = {
    nave: nave
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

/* ===== FIX FOCO SELECT2 ===== */
$(document).on('select2:open', function () {
  let searchField = document.querySelector('.select2-container--open .select2-search__field');
  if (searchField) searchField.focus();
});
</script>
