<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$corp = new company();
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
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>FYGroup | Empresas</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Empresas</h1>

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
                                    <form class="form-container" id="companyForm">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for='company' class='text-gray-800 font-weight-bold'>Nombre de Empresa</label>
                                                <input type="text" class="form-control form-control-user" id="company" name="company" onblur="verifyCompany(this.value)" placeholder="Exportadora Unifrutti">
                                                <small class="text-danger" id="error-company"></small>
                                            </div>

                                            <div class="col-sm-3">
                                                <label for='exporter' class='text-gray-800 font-weight-bold'>Exportadora</label>
                                                <select class="form-control select2 form-control-user" id="exporter" name="exporter">
                                                    <option value="-">Seleccione..</option>
                                                    <option value="1">Si</option>
                                                    <option value="0">No</option>
                                                </select>
                                                <small class="text-danger" id="error-exporter"></small>
                                            </div>

                                            <div class="col-sm-3">
                                                <label for='agency' class='text-gray-800 font-weight-bold'>Agencia</label>
                                                <select class="form-control select2 form-control-user" id="agency" name="agency">
                                                    <option value="-">Seleccione..</option>
                                                    <option value="1">Si</option>
                                                    <option value="0">No</option>
                                                </select>
                                                <small class="text-danger" id="error-agency"></small>
                                            </div>
                                        </div>

                                        <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
                                        <button id="loadBtn" type="button" class="btn btn-primary btn-sm btn-user" onclick="saveCompany()">
                                          <span id="loadBtnText"><i class="fas fa-check-circle"></i> Guardar</span>
                                          <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                        <button type='button' class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-eraser'></i> Limpiar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Listado de Lineas Navieras -->
                    <?php echo $corp->getTableCompany(); ?>
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

    <!-- Modal Editar Empresa-->
    <div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:998;"></div>
    <div id="editCompanyModal" style="display:none; position:fixed; width: 80%; top:10%; left:50%; transform:translateX(-50%);background:#fff; border-radius:10px; padding:20px; z-index:999; box-shadow:0 0 10px rgba(0,0,0,0.3);">
    <h4>Editar Empresa</h4>
    <form id="editCompanyForm">
        <div class="form-group row">
            <div class="col-sm-12">
              <label>Nombre:</label>
              <input type="text" class="form-control form-control-user" id="companyName" name="companyName">
            </div>
            <div class="col-sm-3">
              <label>Exportador:</label>
              <select class="form-control select2 form-control-user" id="isExporter" name="isExporter">
                <option value="-">Seleccione..</option>
                <option value="1">Si</option>
                <option value="0">No</option>
              </select>
            </div>
            <div class="col-sm-3">
              <label>Agencia:</label>
              <select class="form-control select2 form-control-user" id="isAgency" name="isAgency">
                <option value="-">Seleccione..</option>
                <option value="1">Si</option>
                <option value="0">No</option>
              </select>
            </div>
        </div>

        <input type="hidden" id="companyId" name="companyId">
        <input type="hidden" name="page" value="<?php echo $paginaActual; ?>">
        <button type="button" name="savechanges" class="btn btn-success btn-user btn-sm" onclick="saveChanges()"><i class='fas fa-check-circle'></i> Guardar</button>
        <button type="button" name="closemodal" class="btn btn-danger btn-user btn-sm" onclick="closeModal()">Cancelar</button>
    </form>
    </div>

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
var verifyCompany = function(name) {
  if(name !== ''){
    $.ajax({
      url: '../controllers/companyVerifyController.php',
      data: {
        name: name
      },
      type: "POST",
    }).done(function(x) {
      if(x == 'NOOK'){
        Swal.fire({
          title: 'Oops...',
          html: 'Empresa <b>'+name+'</b> ya se encuentra registrado.',
          icon: 'error',
          cancelButtonColor: '#d33',
        }).then((result) => {
          $('#company').val('').focus();
        });
      }
    });
  }
}

var editCompany = function(id) {
  $.ajax({
    url: '../controllers/companyEditController.php',
     type: 'POST',
     data: { id: id },
     dataType: 'json',
     success: function(data) {
      $('#companyId').val(data.id);
      $('#companyName').val(data.name);
      $('#isExporter').val(data.exporter);
      $('#isAgency').val(data.agency);

      /* Mostrar overlay y modal */
      $('#modalOverlay').fadeIn(200);
      $('#editCompanyModal').fadeIn(200);
    },
    error: function() {
      alert('Error al cargar los datos.');
    }
  });
}

var closeModal = function() {
  $('#editCompanyModal').fadeOut(200);
  $('#modalOverlay').fadeOut(200);
}

var saveChanges = function() {
  var paginaActual = $('input[name="page"]').val();

  $.ajax({
    url: '../controllers/companyUpdateController.php',
    data: $('#editCompanyForm').serialize(),
    type: 'POST',
  }).done(function(x) {
    if (x === 'OK') {
      Swal.fire({
        title: '¡Éxito!',
        text: '¡Empresa actualizada con éxito!',
        icon: 'success',
        confirmButtonColor: '#4CAF50'
      }).then(() => {
        window.location = '<?php echo generateMkey('enter_company'); ?>&page='+ paginaActual;
      });
    } else {
      Swal.fire({
        title: 'Oops...',
        text: 'Error al actualizar la empresa.',
        icon: 'error',
        cancelButtonColor: '#d33',
      });
    }
  });
}

var deleteCompany = function(id, name, exporter, agency) {
  var paginaActual = $('input[name="page"]').val();

  Swal.fire({
    title: 'Eliminar Empresa.',
    html: name,
    text: '¿Estas seguro de eliminar esta empresa?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Si, elimimar!",
    cancelButtonText : 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../controllers/companyDeleteController.php',
        type: 'POST',
        data: {
          id: id,
          name: name,
          exporter: exporter,
          agency: agency
        },
      }).done(function(x) {
        if(x == 'OK'){
          Swal.fire({
            title: '¡Éxito!',
            text: '¡Empresa eliminada con éxito!',
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          }).then((result) => {
            window.location = '<?php echo generateMkey('enter_company'); ?>&page=' + paginaActual;
          });
        } else if(x == 'NOOK'){
          Swal.fire({
            title: 'Oops...',
            text: 'Error al eliminar la empresa.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        }else if(x == 'NOOK2'){
          Swal.fire({
            title: 'Oops...',
            html: 'La empresa </br><b>'+name+'</b></br> no se puede eliminar porque se encuentra asociada a un camión que ya fue visado en el sistema, favor revisa e intenta nuevamente.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        }
      });
    }
  });
}

var saveCompany = function() {
  const form = document.getElementById('companyForm');
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
      url: '../controllers/companyController.php',
      data: $('#companyForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          text: '¡Empresa registrada con éxito!',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = '<?php echo generateMkey('enter_company'); ?>&page=' + paginaActual;
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al registrar la empresa.',
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
</script>
