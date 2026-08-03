<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$port = new outerPort();
$cfg = new cfg();
$user = new user();
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
$services = $cfg->getServicesStatus();

$allOnline = true;
foreach ($services as $service) {
    if (!$service['status']) {
        $allOnline = false;
        break;
    }
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
    <link rel="manifest" href="../favicon/site.webmanifest">
    <title>FYGroup | Dashboard</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <style>
      body {
        background: #f7f9fc;
      }

      .progress-bar{
        background: #f7f9fc;
      }
    </style>
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
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

                        <span class="badge badge-success p-2">AMBIENTE: DEV</span>
                    </div>

                    <div class="container-fluid-custom">
                        <!-- Resumen -->
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            PHP
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= phpversion(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            DB
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $cfg->getMysqlVersion(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            TABLAS
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $cfg->getTotalTables(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            BASE DE DATOS
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?= $cfg->getDatabaseName(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BD + Disco -->
                        <div class="row">
                            <!-- BD -->
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Base de Datos</h6>
                                    </div>

                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Estado</th>
                                                <td>
                                                    <span class="badge badge-success">ONLINE</span>
                                                    <i class="fas fa-circle fa-fade text-success ml-2"></i>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Versión</th>
                                                <td><?= $cfg->getMysqlVersion(); ?></td>
                                            </tr>

                                            <tr>
                                                <th>Tablas</th>
                                                <td><?= $cfg->getTotalTables(); ?></td>
                                            </tr>

                                            <tr>
                                                <th>Tamaño</th>
                                                <td><?= $cfg->getDatabaseSize(); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Disco -->
                            <div class="col-lg-6 mb-4">
                                <?php $disk = $cfg->getDiskUsage(); ?>

                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-info">Almacenamiento</h6>
                                    </div>

                                    <div class="card-body">
                                        <div class="progress mb-3" style="height:15px;">
                                            <div class="progress-bar
                                                <?= $disk['percent'] < 70 ? 'bg-success' : ($disk['percent'] < 90 ? 'bg-warning' : 'bg-danger'); ?>" style="width: <?= $disk['percent'] ?>%;">
                                                <?= $disk['percent'] ?>%
                                            </div>
                                        </div>

                                        <table class="table table-sm">
                                            <tr>
                                                <th>Usado</th>
                                                <td><?= $disk['used_gb'] ?> GB</td>
                                            </tr>

                                            <tr>
                                                <th>Libre</th>
                                                <td><?= $disk['free_gb'] ?> GB</td>
                                            </tr>

                                            <tr>
                                                <th>Total</th>
                                                <td><?= $disk['total_gb'] ?> GB</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seguridad + Servicios -->
                        <div class="row">
                            <!-- Seguridad -->
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-danger">Seguridad</h6>
                                    </div>

                                    <div class="card-body">
                                        <?php foreach ($cfg->getSecurityStatus() as $k => $v): ?>
                                            <div class="d-flex justify-content-between border-bottom py-2">
                                                <strong><?= $k ?></strong>
                                                <span><?= $v ? '<i class="fas fa-check-circle fa-lg text-success"></i>' : '<i class="fas fa-times-circle fa-lg text-danger"></i>' ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Servicios -->
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow">
                                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 font-weight-bold text-success">Servicios</h6>

                                        <?php if ($allOnline): ?>
                                            <span class="badge badge-success px-3 py-2">
                                                <i class="fas fa-circle fa-fade mr-1"></i>
                                                Todos los servicios operativos
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-warning px-3 py-2">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Uno o más servicios presentan problemas
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-body">
                                        <?php foreach ($cfg->getServicesStatus() as $service): ?>
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <strong><?= $service['name'] ?></strong>
                                            <span>
                                                <span class="badge badge-<?= $service['badge'] ?>">
                                                    <?= $service['text'] ?>
                                                </span>

                                                <?= $service['icon'] ?>
                                            </span>
                                        </div>
                                        <?php endforeach; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
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

async function loadMetrics() {
  const res = await fetch('../controllers/metricsController.php');
  const data = await res.json();

  // System
  document.getElementById('phpVersion').innerText = data.system.php;

  // DB
  document.getElementById('dbStatus').innerHTML =
  data.database.status ? '<i class="fas fa-circle fa-fade text-success ml-2"></i>' : '<i class="fas fa-circle  text-danger ml-2"></i>';

  document.getElementById('dbTables').innerText = data.database.tables;

  document.getElementById('diskUsage').innerText =
  data.disk.percent + '%';

  document.getElementById('servicesBox').innerHTML = html;
}

// loop tipo observabilidad real
loadMetrics();
setInterval(loadMetrics, 5000);
</script>
