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
                <!-- =====================================================
                    DASHBOARD - INDICADORES OPERACIONALES
                ===================================================== -->

                <div class="container-fluid-custom">
                    <!-- Breadcrumb -->
                    <?= menu::breadcrumb(); ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-1 text-gray-800">Gestión Antepuerto</h1>
                    <p class="mb-4">Indicadores y seguimiento operacional.</p>

                    <!-- =================================================
                        INDICADORES DE PRODUCTIVIDAD
                    ================================================== -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Ingresos <em>(últimos 7 días)</em>
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="avgEntries">0</div>
                                    <small class="text-muted">Camiones ingresados</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Despachos <em>(últimos 7 días)</em>
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="avgDepartures">0</div>
                                    <small class="text-muted">Camiones despachados</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Ciclo promedio <em>(últimos 7 días)</em>
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="avgCycle">00:00:00</div>
                                    <small class="text-muted">Tiempo de permanencia</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Camiones críticos
                                    </div>
                                    <div class="h4 mb-0 font-weight-bold text-gray-800"
                                        id="criticalCount">0</div>
                                    <small class="text-muted">Más de 4 horas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =================================================
                        GRÁFICOS OPERACIONALES
                    ================================================== -->
                    <div class="row">
                        <!-- MOVIMIENTOS DIARIOS -->
                        <div class="col-xl-8 col-lg-7 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Movimientos <em>(últimos 7 días)</em>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div style="height:300px">
                                        <canvas id="dailyMovementsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DISTRIBUCIÓN DE CARGA -->
                        <div class="col-xl-4 col-lg-5 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Distribución de carga
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div style="height:300px">
                                        <canvas id="cargoDistributionChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RANKING EMPRESAS -->
                        <div class="col-xl-6 col-lg-6 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Ranking de empresas
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div style="height:300px">
                                        <canvas id="companiesRankingChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MOVIMIENTOS POR HORA -->
                        <div class="col-xl-6 col-lg-6 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Ingresos por hora
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div style="height:300px">
                                        <canvas id="hourlyMovementsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =================================================
                        ALERTAS OPERACIONALES
                    ================================================== -->
                    <div class="row">
                        <div class="col-xl-5 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-danger">
                                        Alertas operacionales
                                    </h6>
                                </div>

                                <div class="card-body" id="operationalAlerts">
                                    <div class="text-center text-muted">
                                        Cargando alertas...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CAMIONES CRÍTICOS -->
                        <div class="col-xl-7 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-danger">
                                        Camiones con permanencia crítica
                                    </h6>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Patente</th>
                                                    <th>Empresa</th>
                                                    <th>Ingreso</th>
                                                    <th>Permanencia</th>
                                                </tr>
                                            </thead>
                                            <tbody id="criticalTrucksBody">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">
                                                        Cargando información...
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =================================================
                        ÚLTIMOS MOVIMIENTOS
                    ================================================== -->
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Últimos movimientos del antepuerto
                                    </h6>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Patente</th>
                                                    <th>Contenedor</th>
                                                    <th>Tipo de carga</th>
                                                    <th>Empresa</th>
                                                    <th>Ingreso</th>
                                                    <th>Salida</th>
                                                </tr>
                                            </thead>
                                            <tbody id="lastMovementsBody">
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        Cargando movimientos...
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
/* CONFIGURACIÓN*/
var API_DASHBOARD = '../controllers/';
var dashboardEndpoints = {
  dailyMovements: 'dailyMovementsController.php',
  cargoDistribution: 'cargoDistributionController.php',
  companiesRanking: 'companiesRankingController.php',
  hourlyMovements: 'hourlyMovementsController.php',
  criticalTrucks: 'criticalTrucksController.php',
  operationalAlerts: 'operationalAlertsController.php',
  productivity: 'productivityController.php',
  lastMovements: 'lastMovementsController.php'
};

var dashboardCharts = {};

/* =====================================================
   PETICIONES FETCH
===================================================== */
function fetchDashboard(action) {
  if (!dashboardEndpoints[action]) {
    return Promise.reject(new Error('Controlador no encontrado'));
  }

  return fetch(API_DASHBOARD + dashboardEndpoints[action])
  .then(function(response) {
    if (!response.ok) {
      throw new Error('Error HTTP: ' + response.status);
    }

    return response.json();
  })
  .then(function(data) {
    if (data && data.error) {
      throw new Error(data.error);
    }

    return data;
  });
}

/* UTILIDADES */
function escapeHTML(value) {
  return String(value == null ? '' : value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function destroyChart(name) {
  if (dashboardCharts[name]) {
    dashboardCharts[name].destroy();
    delete dashboardCharts[name];
  }
}

function showTableMessage(elementId, columns, message) {
  var element = document.getElementById(elementId);

  if (element) {
    element.innerHTML =
      '<tr><td colspan="' + columns +
      '" class="text-center text-muted">' +
      escapeHTML(message) +
      '</td></tr>';
  }
}

/* 1. MOVIMIENTOS DIARIOS */
function loadDailyMovements() {
  fetchDashboard('dailyMovements')
    .then(function(data) {
      var labels = data.map(function(item) {
        return item.fecha;
      });

      var entries = data.map(function(item) {
        return Number(item.ingresos);
      });

      var departures = data.map(function(item) {
        return Number(item.despachos);
      });

      destroyChart('dailyMovements');

      dashboardCharts.dailyMovements = new Chart(
        document.getElementById('dailyMovementsChart'),
        {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Ingresos',
                data: entries,
                backgroundColor: '#168de2'
              },
              {
                label: 'Despachos',
                data: departures,
                backgroundColor: '#159b72'
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0
                }
              }
            }
          }
        }
      );
    })
    .catch(function(error) {
        console.error('Movimientos diarios:', error);
    });
}

/* 2. DISTRIBUCIÓN DE CARGA */
function loadCargoDistribution() {
  fetchDashboard('cargoDistribution')
  .then(function(data) {
    destroyChart('cargoDistribution');

      dashboardCharts.cargoDistribution = new Chart(
        document.getElementById('cargoDistributionChart'),
        {
          type: 'doughnut',
          data: {
            labels: data.map(function(item) {
              return item.tipo;
            }),
            datasets: [{
              data: data.map(function(item) {
                return Number(item.total);
              }),
              backgroundColor: [
                '#168de2',
                '#159b72',
                '#f6c23e',
                '#858796'
              ]
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
              position: 'bottom'
            }
          }
        }
      );
    })
    .catch(function(error) {
      console.error('Distribución de carga:', error);
    });
}

/* 3. RANKING DE EMPRESAS */
function loadCompaniesRanking() {
  fetchDashboard('companiesRanking')
    .then(function(data) {
      destroyChart('companiesRanking');

      dashboardCharts.companiesRanking = new Chart(
        document.getElementById('companiesRankingChart'),
        {
          type: 'bar',
          data: {
            labels: data.map(function(item) {
              return item.empresa;
            }),
            datasets: [{
              label: 'Movimientos',
              data: data.map(function(item) {
                return Number(item.total);
              }),
              backgroundColor: '#168de2',
              borderRadius: 5
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              x: {
                beginAtZero: true,
                ticks: {
                  precision: 0
                }
              }
            }
          }
        }
      );
    })
    .catch(function(error) {
      console.error('Ranking empresas:', error);
    });
}

/* 4. INGRESOS POR HORA */
function loadHourlyMovements() {
  fetchDashboard('hourlyMovements')
    .then(function(data) {
      var labels = data.map(function(item) {
        return String(item.hora).padStart(2, '0') + ':00';
      });

      var totals = data.map(function(item) {
        return Number(item.total);
      });

      destroyChart('hourlyMovements');

      dashboardCharts.hourlyMovements = new Chart(
        document.getElementById('hourlyMovementsChart'),
        {
          type: 'line',
          data: {
            labels: labels,
            datasets: [{
              label: 'Ingresos',
              data: totals,
              borderColor: '#168de2',
              backgroundColor: 'rgba(22,141,226,0.12)',
              fill: true,
              lineTension: 0.3
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  precision: 0
                }
              }
            }
          }
        }
      );
    })
    .catch(function(error) {
      console.error('Ingresos por hora:', error);
    });
}

/* 5. CAMIONES CRÍTICOS */
function loadCriticalTrucks() {
  fetchDashboard('criticalTrucks')
    .then(function(data) {
      var body = document.getElementById('criticalTrucksBody');

      document.getElementById('criticalCount').textContent = data.length;

      if (!data.length) {
        showTableMessage(
          'criticalTrucksBody',
          4,
          'No hay camiones con permanencia crítica.'
        );

        return;
      }

      body.innerHTML = '';
      data.forEach(function(item) {
      body.innerHTML +=
        '<tr>' +
          '<td><strong>' +
            escapeHTML(item.patente) +
            '</strong></td>' +
          '<td>' +
            escapeHTML(item.empresa) +
          '</td>' +
          '<td>' +
            escapeHTML(item.ingreso) +
          '</td>' +
          '<td><span class="badge badge-danger">' +
            escapeHTML(item.permanencia) +
          '</span></td>' +
        '</tr>';
      });
    })
    .catch(function(error) {
      console.error('Camiones críticos:', error);
    });
}

/* 6. ALERTAS OPERACIONALES */
function loadOperationalAlerts() {
  fetchDashboard('operationalAlerts')
    .then(function(data) {
      var container = document.getElementById('operationalAlerts');

      if (!data.length) {
        container.innerHTML =
          '<div class="alert alert-success mb-0">' +
            'No existen alertas operacionales.' +
          '</div>';
        return;
      }

      container.innerHTML = '';

      data.forEach(function(item) {
        var isCritical = item.tipo === 'critical';
        var cssClass = isCritical ? 'alert-danger' : 'alert-warning';
        var icon = isCritical ? 'fa-exclamation-circle': 'fa-exclamation-triangle';

        container.innerHTML +=
          '<div class="alert ' + cssClass + '">' +
            '<div class="font-weight-bold">' +
                '<i class="fas ' + icon + ' mr-2"></i>' +
                escapeHTML(item.titulo) +
            '</div>' +
            '<small>' +
                escapeHTML(item.descripcion) +
            '</small>' +
          '</div>';
      });
    })
    .catch(function(error) {
      console.error('Alertas:', error);
    });
}

/* 7. PRODUCTIVIDAD */
function loadProductivity() {
  fetchDashboard('productivity')
    .then(function(data) {
      document.getElementById('avgEntries').textContent = Number(data.avgEntries || 0).toLocaleString('es-CL');
      document.getElementById('avgDepartures').textContent = Number(data.avgDepartures || 0).toLocaleString('es-CL');
      document.getElementById('avgCycle').textContent = data.avgCycle || '00:00:00';
    })
    .catch(function(error) {
      console.error('Productividad:', error);
    });
}

/* 8. ÚLTIMOS MOVIMIENTOS */
function loadLastMovements() {
  fetchDashboard('lastMovements')
    .then(function(data) {
      var body = document.getElementById('lastMovementsBody');

      if (!data.length) {
        showTableMessage(
            'lastMovementsBody',
            6,
            'No existen movimientos registrados.'
        );
        return;
      }

      body.innerHTML = '';

      data.forEach(function(item) {
        body.innerHTML +=
          '<tr>' +
            '<td>' + escapeHTML(item.patente) + '</td>' +
            '<td>' + escapeHTML(item.contenedor) + '</td>' +
            '<td>' + escapeHTML(item.tipo_carga) + '</td>' +
            '<td>' + escapeHTML(item.empresa) + '</td>' +
            '<td>' + escapeHTML(item.ingreso || '-') + '</td>' +
            '<td>' + escapeHTML(item.salida || 'Pendiente') + '</td>'
          '</tr>';
      });
    })
    .catch(function(error) {
      console.error('Últimos movimientos:', error);
    });
}

/* INICIALIZAR DASHBOARD */
function loadDashboardAnalytics() {
  loadDailyMovements();
  loadCargoDistribution();
  loadCompaniesRanking();
  loadHourlyMovements();
  loadCriticalTrucks();
  loadOperationalAlerts();
  loadProductivity();
  loadLastMovements();
}

/* EJECUCIÓN */
document.addEventListener('DOMContentLoaded', function() {
  loadDashboardAnalytics();

  // Actualización automática cada 60 segundos.
  setInterval(function() {
    loadDashboardAnalytics();
  }, 60000);
});
</script>
