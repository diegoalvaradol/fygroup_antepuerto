<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$cfg = new cfg();
$user = new user();
$port = new outerPort();

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
    <title>FYGroup | Crear Portada</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Crear Portada</h1>
                    <p class="mb-4">Acá puedes generar portadas para tus reportes.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <!-- Custom Text Color Utilities -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Crear Portada</h6>
                                </div>

                                <div class="card-body">
                                    <form class="form-container" id="coverMakerForm">
                                        <div class="form-group row justify-content-center">
                                          <!-- Exportador -->
                                            <div class="col-sm-2">
                                                <label for="vessel" class="text-gray-800 font-weight-bold">Motonave</label>
                                                <select class="form-control select2 form-control-user" id="vessel" name="vessel">
                                                    <option value="-">Seleccione una motonave...</option>
                                                </select>
                                                <small class="text-danger" id="error-vessel"></small>
                                            </div>

                                            <div class="col-sm-2">
                                                <label for="exporter" class="text-gray-800 font-weight-bold">Exportador</label>
                                                <select class="form-control select2 form-control-user" id="exporter" name="exporter">
                                                    <option value="-">Seleccione un exportador...</option>
                                                </select>
                                                <small class="text-danger" id="error-exporter"></small>
                                            </div>

                                            <div class="col-sm-2">
                                                <label for="agency" class="text-gray-800 font-weight-bold">Agencia</label>
                                            <input type="text" class="form-control form-control-user" id="agency" name="agency" value="FYGROUP" placeholder="FYGROUP" readonly>
                                                <small class="text-danger" id="error-agency"></small>
                                            </div>

                                            <div class="col-sm-2" style="margin-top: 30px;">
                                                <button type="button" class="btn btn-primary btn-user" id="btnGenerar" onclick="coverMaker()">
                                                    <i class="fas fa-marker"></i> Generar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <h1 class="h3 mb-1 text-gray-800">Portada</h1>

                            <div id="loader" style="display:none; text-align:center; padding:20px;">
                                <i class="fas fa-spinner fa-spin fa-3x" style="color: #4e73df;"></i></br> Generando portada...
                            </div>

                            <!--Div Portada -->
                            <div id="coverDiv" style="width:100%; height:800px; display:none;"></div>
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
let currentPdfUrl = null;

var coverMaker = function() {
  const $btn = $('#btnGenerar');
  const $div = $('#coverDiv');
  const $loader = $('#loader');

  const form = document.getElementById('coverMakerForm');
  const formData = new FormData(form);

  const vessel = $('#vessel').val();
  const exporter = $('#exporter').val();
  const agency = 'FYGROUP';

  let hasError = false;

  $('small.text-danger').text('');
  $('.form-control-user').removeClass('is-invalid');
  $('.select2-container').removeClass('select2-error');

  for (const [key, value] of formData.entries()) {
    const input = form.querySelector(`[name="${key}"]`);
    const error = document.getElementById(`error-${key}`);

    const isSelect2 = input && $(input).hasClass('select2-hidden-accessible');
    const isEmpty = !String(value).trim() || value === '-';

    if (isEmpty) {
      if (error) {
        error.textContent = 'Este campo es obligatorio.';
      }

      if (isSelect2) {
        $(input).data('select2').$container.addClass('select2-error');
      } else if (input) {
        input.classList.add('is-invalid');
      }

      hasError = true;
    }
  }

  if (hasError) {
    return;
  }

  $.ajax({
    url: '../controllers/coverMakerController.php',
    type: 'GET',
    data: {
      vessel,
      exporter,
      agency
    },
    xhrFields: {
      responseType: 'blob'
    },

    beforeSend() {
      $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generando...');
      $loader.show();
      $div.hide().empty();
    },

    success(blob) {
      if (currentPdfUrl) {
        URL.revokeObjectURL(currentPdfUrl);
      }

      currentPdfUrl = URL.createObjectURL(blob);

      $div.html(`
        <iframe
          src="${currentPdfUrl}"
          width="100%"
          height="800"
          style="border:none;"
          loading="lazy">
        </iframe>
      `).show();
    },

    error(xhr, status, error) {
      console.error(error);

      $div.html(`
        <div class="alert alert-danger">
            Error al generar el documento.
        </div>
      `).show();
    },

    complete() {
      $loader.hide();
      $btn.prop('disabled', false).html('<i class="fas fa-marker"></i> Generar');
    }
  });
};

$(document).ready(function() {
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
          search: params.term, /* Lo que escribe el usuario */
          fygroup: 1
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
