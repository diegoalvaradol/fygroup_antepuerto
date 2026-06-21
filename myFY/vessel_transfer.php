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
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>FYGroup | Roleo de Carga</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
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
                                        <span id="loadBtnText"><i class="fas fa-check-circle"></i> Realizar Roleo</span>
                                        <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                    <button type='button' class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-eraser'></i> Limpiar</button>
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

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
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
        $(inputElement).data('select2').$container.addClass('select2-error');
      } else if (inputElement) {
        inputElement.classList.add('is-invalid');
      }

      hasError = true;
    } else {
      if (errorElement) {
        errorElement.innerText = '';
      }

      if (isSelect2) {
        $(inputElement).data('select2').$container.removeClass('select2-error');
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

$(document).ready(function() {
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
</script>
