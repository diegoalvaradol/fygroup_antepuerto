<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

/* Validación de URL */
$module = $_GET['pag'] ?? '';
$time = $_GET['t'] ?? '';
$ttl = $_GET['ttl'] ?? '';
$sig = $_GET['sig'] ?? '';

if (!validateSecureLink($module, $time, $ttl, $sig)) {
    die('Acceso inválido o expirado');
}

$port = new outerPort();
$cfg = new cfg();
$sideBarPortal = menu::sideBarPortal();
$tapBarPortal = menu::secondTapBarPortal();
$footer = menu::footerSSL();
$top = UIComponents::scrollToTopButton();
$infoCfg = json_decode($cfg->getInfo(1), true);

/* Establece Limite de 30 minutos para el usuario pueda visitar el portal cliente */
$tiempoMaximo = 1800; /* 30 minutos */
if (time() - $_SESSION['last_session'] > $tiempoMaximo) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>Portal - FYGroup | Listado de Contenedores</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <?php echo $sideBarPortal; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php echo $tapBarPortal; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid-custom">
                    <!-- Breadcrumb -->
                    <?= menu::breadcrumb(); ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Reporte de Contenedores</h1>

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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white py-2 px-3">
                    <h6 class="modal-title font-weight-bold mb-0" id="exampleModalLabel">¿Deseas cerrar sesión?</h6>
                    <button type="button" class="close text-white p-0 m-0" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">Selecciona 'Cerrar sesión' si realmente deseas hacerlo.</div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger" href="logout.php" onclick="finishCountDown()"><i class='fas fa-sign-out-alt'></i> Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
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

var exportExcel = function(nave, condicion, exportador, division, cliente) {
  var division = '<?php echo $_SESSION['user']['division']; ?>';
  var cliente = '<?php echo $_SESSION['user']['run']; ?>';
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../controllers/containerDownloadExcelController.php';
  form.style.display = 'none';

  const fields = {
    nave: nave,
    condicion: condicion,
    exportador: exportador,
    division: division,
    cliente: cliente
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
  const contador = setInterval(actualizarConteo, 1000); /* Llama a la función cada segundo (1000ms) */

  setInterval(actualizarReloj, 1000);
  actualizarReloj(); /* Primera llamada */
  startCountDown();

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
          search: params.term,
          current: 1
        };
      },
      processResults: function (data) {
        return { results: data };
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

$(document).on('select2:open', function () {
  let searchField = document.querySelector('.select2-container--open .select2-search__field');
  if (searchField) {
    searchField.focus();
  }
});
</script>
