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
$admin = $user->isAdmin($_SESSION['user']['run']);
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
    <title>FYGroup | Información Servidor</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Servidor</h1>
                    <p class="mb-4">Acá puedes revisar la información del servidor.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Información del sistema -->
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-light shadow-sm h-100">
                                <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #4e73df;border-radius:8px;">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-desktop fa-2x text-primary mb-2"></i>
                                        <h6 class="text-primary text-uppercase">
                                            Información del Sistema
                                        </h6>
                                    </div>

                                    <table class="table table-sm table-hover">
                                        <tr>
                                            <th width="45%">Versión</th>
                                            <td><?= $infoCfg['version']; ?></td>
                                        </tr>

                                        <tr>
                                            <th>Release</th>
                                            <td><?= $releasedTime->format('d-m-Y'); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Última actualización</th>
                                            <td><?= $updateTime->format('d-m-Y'); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Usuario</th>
                                            <td><?= $_SESSION['user']['name']; ?></td>
                                        </tr>

                                        <tr>
                                            <th>RUN</th>
                                            <td><?= $_SESSION['user']['run']; ?></td>
                                        </tr>

                                        <tr>
                                            <th>Administrador</th>
                                            <td>
                                                <?= $admin ? '<span class="badge badge-success">SI</span>' : '<span class="badge badge-secondary">NO</span>'; ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Zona Horaria</th>
                                            <td><?= date_default_timezone_get(); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Hora Servidor</th>
                                            <td id="clock"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Información del servidor -->
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-light shadow-sm h-100">
                                <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #1cc88a;border-radius:8px;">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-server fa-2x text-success mb-2"></i>
                                        <h6 class="text-success text-uppercase">Servidor</h6>
                                    </div>

                                    <table class="table table-sm table-hover">
                                        <tr>
                                            <th width="45%">Software</th>
                                            <td><?= $_SERVER['SERVER_SOFTWARE']; ?></td>
                                        </tr>

                                        <tr>
                                            <th>PHP</th>
                                            <td><?= phpversion(); ?></td>
                                        </tr>

                                        <tr>
                                            <th>Sistema Operativo</th>
                                            <td><?= PHP_OS_FAMILY; ?></td>
                                        </tr>

                                        <tr>
                                            <th>Hostname</th>
                                            <td><?= gethostname(); ?></td>
                                        </tr>

                                        <tr>
                                            <th>IP Servidor</th>
                                            <td><?= $_SERVER['SERVER_ADDR'] ?? '-'; ?></td>
                                        </tr>

                                        <tr>
                                            <th>HTTPS</th>
                                            <td>
                                                <?= isset($_SERVER['HTTPS']) ? '<span class="badge badge-success">ACTIVO</span>' : '<span class="badge badge-danger">INACTIVO</span>'; ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Puerto</th>
                                            <td><?= $_SERVER['SERVER_PORT']; ?></td>
                                        </tr>

                                        <tr>
                                            <th>Document Root</th>
                                            <td style="font-size:11px;">
                                                <?= $_SERVER['DOCUMENT_ROOT']; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración PHP + Extensiones -->
                    <div class="row">
                        <!-- Configuración PHP -->
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-light shadow-sm h-100">
                                <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #36b9cc;border-radius:8px;">
                                    <div class="text-center mb-3">
                                        <i class="fab fa-php fa-2x text-info mb-2"></i>
                                        <h6 class="text-info text-uppercase">Configuración PHP</h6>
                                    </div>

                                    <table class="table table-sm table-hover">
                                        <tr>
                                            <th>memory_limit</th>
                                            <td><?= ini_get('memory_limit'); ?></td>
                                        </tr>

                                        <tr>
                                            <th>max_execution_time</th>
                                            <td><?= ini_get('max_execution_time'); ?></td>
                                        </tr>

                                        <tr>
                                            <th>upload_max_filesize</th>
                                            <td><?= ini_get('upload_max_filesize'); ?></td>
                                        </tr>

                                        <tr>
                                            <th>post_max_size</th>
                                            <td><?= ini_get('post_max_size'); ?></td>
                                        </tr>

                                        <tr>
                                            <th>max_input_vars</th>
                                            <td><?= ini_get('max_input_vars'); ?></td>
                                        </tr>

                                        <tr>
                                            <th>display_errors</th>
                                            <td><?= ini_get('display_errors') ? 'ON' : 'OFF'; ?></td>
                                        </tr>

                                        <tr>
                                            <th>log_errors</th>
                                            <td><?= ini_get('log_errors') ? 'ON' : 'OFF'; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Extensiones -->
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-light shadow-sm h-100">
                                <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #f6c23e;border-radius:8px;">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-puzzle-piece fa-2x text-warning mb-2"></i>
                                        <h6 class="text-warning text-uppercase">Extensiones PHP</h6>
                                    </div>

                                    <?php  $extensiones = ['pdo','mysqli', 'curl','openssl','gd','mbstring','zip','intl','json','fileinfo',];?>
                                    <?php foreach ($extensiones as $ext):?>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <strong><?= strtoupper($ext); ?></strong>

                                            <?php if (extension_loaded($ext)): ?>
                                                <span class="badge badge-success">
                                                    INSTALADA
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">
                                                    NO
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
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
