<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$db   = (new Database())->getConnection();
$cfg  = new cfg($db);
$user = new user($db);

$infoCfg         = json_decode($cfg->getInfo(1), true);
$admin           = $user->isAdmin($_SESSION["user"]["run"]);
$releasedTime    = new DateTime($infoCfg['released_date']);
$updateTime      = new DateTime($infoCfg['update_date']);
$sideBarSSL      = menu::sideBarSSL();
$secondTapBarSSL = menu::secondTapBarSSL();
$footer          = menu::footerSSL();

/* Validar superadmin */
if (!$admin) {
  $usuario = $_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"] . ' (' . $_SESSION["user"]["run"] . ')';
  $pag     = basename(__FILE__);
  $url     = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
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
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>SSL | Liquidación de Nave</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Liquidación de Naves</h1>
                    <p class="mb-4">Acá podras obtener la liquidación de carga por nave y exportador.</p>

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
                                        <div class="form-row">
                                          <!-- Exportador -->
                                          <div class="form-group col-md-4">
                                            <label for="exporter" class="text-gray-800 font-weight-bold">Exportador <em>(Opcional)</em></label>
                                            <select class="form-control select2" id="exporter" name="exporter">
                                              <option value="-">Seleccione un exportador...</option>
                                            </select>
                                          </div>

                                          <!-- Motonave -->
                                          <div class="form-group col-md-4">
                                            <label for="vessel" class="text-gray-800 font-weight-bold">Motonave</label>
                                            <select class="form-control select2" id="vessel" name="vessel">
                                              <option value="-">Seleccione una motonave...</option>
                                            </select>
                                          </div>

                                          <!-- Información Motonave -->
                                          <div class="form-group col-md-4">
                                            <label class="text-gray-800 font-weight-bold">Información de Motonave</label>
                                            <small id="info-vessel" class="form-text text-muted"></small>
                                          </div>

                                          <!-- Liquidación -->
                                          <div class="form-group">
                                            <label class="text-gray-800 font-weight-bold">Liquidación Motonave</label>
                                            <div id="detalleLiquidacion"></div>
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
                    <small><b>Autor: </b><?php echo $infoCfg['author']; ?></small>
                    <br>
                    <small><b>Programador y Diseñador: </b><?php echo $infoCfg['author']; ?></small>
                    <br>
                    <small><b> Contactar al Whatsapp: </b><a href="https://wa.me/56923816700?text=Hola%2C%20quiero%20más%20información%20sobre%20el%20producto" target="_blank"><i class="fas fa-brands fa-whatsapp" style="color: #63E6BE;"></i><b>+56923816700</b></a></small>
                    <br>
                    <small><b> Correo: </b><a href="mailto:diego.alvaraado@gmail.com" target="_blank"><b><i class="fas fa-solid fa-envelope" style="color: #1768a6;"></i></i> diego.alvaraado@gmail.com </b></a></small>
                </div>
            </div>
        </div>
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

$(document).ready(function() {
  setInterval(actualizarReloj, 1000);
  actualizarReloj(); /* Primera llamada */

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
          search: params.term, /* Lo que escribe el usuario */
          finished: 1 /* Muestra todas las naves finalizadas */
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

  $('#vessel').on('change', function () {
    const vessel = $(this).val();
    const exporter = $('#exporter').val();

    if (vessel != '-') {
      $.ajax({
        url: '../controllers/vesselInfoController.php',
        method: 'POST',
        data: {id: vessel},
        success: function (response) {
          $('#info-vessel').html(response).css({'color': 'dodgerblue'});
        },
        error: function () {
          $('#info-vessel').html('Error al obtener la información.');
        }
      });

			$.ajax({
        url: '../controllers/getLiquidacionController.php',
        method: 'POST',
        data: {
          id: vessel,
          exporter: exporter
        },
        success: function (response) {
          $('#detalleLiquidacion').html(response);
        },
        error: function () {
          $('#detalleLiquidacion').html('No se ha encontrado una liquidación para la motonave consultada.');
        }
      });
    }else{
      $('#info-vessel').html('');
      $('#detalleLiquidacion').html('');
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