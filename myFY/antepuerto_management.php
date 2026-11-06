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

/* 1. Promedio de permanencia */
$avgstay = $port->avgStayInAntepuerto();

/* 2. Capacidad disponible */
$capacity = $port->availableCapacity();

/* 3. Arribos */
$trucksInAntepuerto = $port->getTotalTrucksInAnpuerto($admin);

/* 4. Ocupación */
$percentUsage = $port->getPercentUsage($infoCfg['goals'],$admin);

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
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="../favicon/fygroup.ico" rel="icon">
    <link href="../favicon/fygroup-256x256.png" rel="apple-touch-icon">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <title>FYGroup | Gestión Antepuerto</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Gestión Antepuerto</h1>
                    <p class="mb-4">Acá podrás gestionar y controlar las operaciones en el antepuerto.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->

                        <!-- 1. Promedio de permanencia -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class=" text-center">
                                        <h6 class="dashboard-title">Promedio de permanencia</h6>

                                        <div class="metric-value metric-value-lg">
                                            <?= htmlspecialchars((string) $avgstay, ENT_QUOTES, 'UTF-8') ?>

                                            <p class="dashboard-chart-subtitle mb-0">
                                                Formato (H:M:S)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Capacidad disponible -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="text-center">
                                        <h6 class="dashboard-title">Capacidad disponible</h6>

                                        <div class="metric-value metric-value-lg">
                                            <?= $capacity ?>

                                            <p class="dashboard-chart-subtitle mb-0">
                                                Total: <?= $infoCfg['goals'] ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Pendientes -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="text-center">
                                        <h6 class="dashboard-title">Pendientes</h6>

                                        <div class="metric-value metric-value-lg">
                                            <?= $trucksInAntepuerto ?>

                                            <p class="dashboard-chart-subtitle mb-0">
                                                Camiones actualmente en antepuerto
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Ocupación -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="text-center">
                                        <h6 class="dashboard-title">Ocupación</h6>

                                        <div class="metric-value metric-value-lg">
                                            <?= $percentUsage ?>%

                                            <p class="dashboard-chart-subtitle mb-0">
                                                Procentaje de ocupación del antepuerto
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Ranking de destinos (POD) -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        Ranking de puertos por naves
                                    </h6>
                                </div>

                                <div class="card-body">
                                    <div style="position:relative; height:300px;">
                                        <canvas id="rankingPortsChart"></canvas>
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
var loadRanking = function () {
  const canvas = document.getElementById('rankingPortsChart');

  if (!canvas) {
    console.error('No existe el canvas #rankingPortsChart');
    return;
  }

  if (typeof Chart === 'undefined') {
    console.error('Chart.js no está cargado');
    return;
  }

  fetch('../controllers/rankingPortsController.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Error HTTP: ' + response.status);
      }
      return response.json();
    })
    .then(data => {
      if (!Array.isArray(data)) {
        console.error('Respuesta inesperada del controlador:', data);
        return;
      }

      if (data.length === 0) {
        console.warn('No hay datos para mostrar.');
        return;
      }

      // Evitar duplicar el gráfico al recargar
      if (canvas.chartInstance) {
        canvas.chartInstance.destroy();
      }

      canvas.chartInstance = new Chart(canvas, {
        type: 'bar',
        data: {
          labels: data.map(item => item.ciudad),
          datasets: [{
            label: 'Cantidad de naves',
            data: data.map(item => Number(item.total_pod)),
            backgroundColor: 'rgba(0, 49, 82, 0.85)',
            borderColor: '#003152',
            borderWidth: 1,
            barPercentage: 0.7
          }]
        },
        plugins: [ChartDataLabels],
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return 'Naves: ' + context.parsed.x;
                }
              }
            },
            datalabels: {
              color: '#ffffff',
              anchor: 'center',
              align: 'center',
              font: {
                weight: 'bold',
                size: 12
              },
              formatter: function (value) {
                return value;
              }
            }
          },
          scales: {
            x: {
              beginAtZero: true,
              ticks: {
                precision: 0
              },
              title: {
                display: true,
                text: 'Cantidad de naves'
              }
            },
            y: {
              title: {
                display: true,
                text: 'Puerto'
              }
            }
          }
        }
      });

      console.log('Ranking cargado correctamente:', data);
    })
    .catch(error => {
      console.error('Error al cargar el ranking:', error);
    });
};

document.addEventListener('DOMContentLoaded', loadRanking);
</script>
