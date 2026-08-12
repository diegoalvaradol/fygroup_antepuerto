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

/* Validar desarrollador */
if (!$dev) {
    $usuario = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . ' (' . $_SESSION['user']['run'] . ')';
    $pag = basename(__FILE__);
    $url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    mostrarAccesoDenegado($usuario, $pag, $url);
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../favicon/fygroup.ico" rel="icon">
    <link href="../favicon/fygroup-256x256.png" rel="apple-touch-icon">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <title>FYGroup | Información PHP</title>

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
                    <h1 class="h3 mb-1 text-gray-800">PHP</h1>
                    <p class="mb-4">Acá puedes revisar la información de la instalación de PHP.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <div class="container-fluid-custom">
                                <div class="row">
                                    <!-- PHP -->
                                    <div class="col-xl-2 col-md-4 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #4e73df;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fab fa-php fa-2x text-primary mb-2"></i>
                                                    <h6 class="text-primary text-uppercase">PHP</h6>

                                                    <div class="h5 font-weight-bold">
                                                        <?= phpversion(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Apache -->
                                    <div class="col-xl-2 col-md-4 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #1cc88a;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fas fa-server fa-2x text-success mb-2"></i>
                                                    <h6 class="text-success text-uppercase">Servidor</h6>

                                                    <small class="font-weight-bold">
                                                        <?= $_SERVER['SERVER_SOFTWARE']; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Usuario -->
                                    <div class="col-xl-2 col-md-4 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #36b9cc;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fas fa-user-shield fa-2x text-info mb-2"></i>
                                                    <h6 class="text-info text-uppercase">Usuario</h6>

                                                    <div class="font-weight-bold">
                                                        <?= $_SESSION['user']['name']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hora -->
                                    <div class="col-xl-2 col-md-4 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #f6c23e;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                                    <h6 class="text-warning text-uppercase">Hora</h6>

                                                    <div class="font-weight-bold" id="clock"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Memoria -->
                                    <div class="col-xl-2 col-md-4 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #e74a3b;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fas fa-memory fa-2x text-danger mb-2"></i>
                                                    <h6 class="text-danger text-uppercase">Memoria PHP</h6>

                                                    <div class="font-weight-bold">
                                                        <?= ini_get('memory_limit'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sistema -->
                                    <div class="col-xl-2 col-md-4 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #858796;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fas fa-code-branch fa-2x text-secondary mb-2"></i>
                                                    <h6 class="text-secondary text-uppercase">Sistema</h6>

                                                    <div class="font-weight-bold">
                                                        DEV
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Versión -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #4e73df;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fas fa-tag fa-2x text-primary mb-2"></i>
                                                    <h6 class="text-primary text-uppercase">Versión</h6>

                                                    <div class="h5">
                                                        <?= $infoCfg['version']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Release -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #1cc88a;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fas fa-rocket fa-2x text-success mb-2"></i>
                                                    <h6 class="text-success text-uppercase">Release</h6>

                                                    <div class="h6">
                                                        <?= $releasedTime->format('d-m-Y'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actualización -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #36b9cc;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fas fa-arrows-rotate fa-2x text-info mb-2"></i>
                                                    <h6 class="text-info text-uppercase">Actualización</h6>

                                                    <div class="h6">
                                                        <?= $updateTime->format('d-m-Y'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Estado -->
                                    <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card bg-light shadow-sm h-100">
                                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #f6c23e;border-radius:8px;">
                                                <div class="text-center">
                                                    <i class="fas fa-circle-check fa-2x text-warning mb-2"></i>

                                                    <h6 class="text-warning text-uppercase">Estado</h6>

                                                    <span class="badge badge-success p-2">OPERATIVO</span>
                                                    <i class="fas fa-circle fa-fade text-success ml-2"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
/* Inicializa el popover */
document.addEventListener('DOMContentLoaded', function () {
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'));
  popoverTriggerList.forEach(function (el) {
    new bootstrap.Popover(el);
  });
});

function actualizarHora() {
  const ahora = new Date();

  document.getElementById("clock").innerHTML = ahora.toLocaleTimeString("es-CL", {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: false
  });
}

actualizarHora();
setInterval(actualizarHora, 1000);
</script>
