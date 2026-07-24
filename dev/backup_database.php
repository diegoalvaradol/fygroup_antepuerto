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

$cfg = new cfg();
$user = new user();

$infoCfg = json_decode($cfg->getInfo(1), true);
$dev = $user->isDev($_SESSION['user']['run']);
$releasedTime = new DateTime($infoCfg['released_date']);
$updateTime = new DateTime($infoCfg['update_date']);
$arrayDivision = get::getDivisionName();
$sideBarSSL = menu::sideBarSSL();
$mainTapBarSSL = menu::mainTapBarSSL();
$footer = menu::footerSSL();
$top = UIComponents::scrollToTopButton();
$modals = new Modals($infoCfg, $arrayDivision, $releasedTime, $updateTime);

$dev = $user->isDev($_SESSION['user']['run']);
$footer = menu::footerSSL();

/* Validar desarrollador */
if (!$dev) {
    $usuario = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . ' (' . $_SESSION['user']['run'] . ')';
    $pag = basename(__FILE__);
    $url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    mostrarAccesoDenegado($usuario, $pag, $url);
}

require_once __DIR__ . '/../config/database.php';

$db = Database::get();

?>

<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>FYGroup | Respaldo Base de Datos</title>

    <link href="../assets/css/all.css" rel="stylesheet">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

                    <h1 class="h3 mb-1 text-gray-800">Respaldo de Base de Datos</h1>
                    <p class="mb-4">Genera un respaldo completo de la base de datos incluyendo estructura,tablas y registros almacenados actualmente.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg-6">
                            <div class="card shadow mb-4 border-left-primary">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Base de datos conectada</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php
                                        try {
                                            $database = $db->query( "SELECT DATABASE()")->fetchColumn();
                                            echo htmlspecialchars($database);
                                        } catch(Exception $e){
                                            echo "Error conexión";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card shadow mb-4 border-left-success">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Generar respaldo
                                    </div>

                                    <p class="text-muted">El archivo generado contendrá la estructura de las tablas y los datos registrados.</p>

                                    <button class='btn btn-success btn-sm' onclick='downloadBakup()'><i class='fas fa-download mr-2'></i>Generar respaldo SQL</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle mr-2"></i>Recomendaciones
                            </h6>
                        </div>

                        <div class="card-body">
                            <ul class="text-muted mb-0">
                                <li>Realizar respaldos antes de cambios importantes en producción.</li>
                                <li>Descargar y almacenar el archivo SQL en una ubicación segura.</li>
                                <li>Validar periódicamente la restauración del respaldo.</li>
                            </ul>
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

    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>
<script>
var downloadBakup = function () {
  Swal.fire({
    title: '¿Generar respaldo?',
    text: 'Se descargará un archivo SQL con el respaldo de la base de datos.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, generar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#1cc88a'
  }).then((result) => {
    if (!result.isConfirmed) return;

    Swal.fire({
      title: 'Generando respaldo...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    $.ajax({
      url: '../controllers/backupDatabaseController.php',
      type: 'POST',
      data: {action: 'generate'},
      xhrFields: {responseType: 'blob'}
    })
    .done(function (archivoSql, estado, xhr) {
      const blob = new Blob([archivoSql], { type: 'application/sql' });
      const url = window.URL.createObjectURL(blob);

      const enlace = document.createElement('a');
      enlace.href = url;
      enlace.download = 'Backup_FYGroup_DB_' + new Date().toISOString().replace(/[:.]/g, '-') + '.sql';
      document.body.appendChild(enlace);
      enlace.click();
      enlace.remove();
      window.URL.revokeObjectURL(url);

      Swal.fire('¡Éxito!', 'Respaldo generado correctamente.', 'success');
    })
    .fail(function () {
      Swal.fire('Oops...', 'Error al generar el respaldo.', 'error');
    });
  });
}
</script>
