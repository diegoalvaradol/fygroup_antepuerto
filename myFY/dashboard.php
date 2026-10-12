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
$whatsAppBtn = UIComponents::whatsappChatBox();
$modals = new Modals($infoCfg, $arrayDivision, $releasedTime, $updateTime);
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
    <title>FYGroup | Dashboard</title>

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
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

                        <span class="badge badge-success p-2">Estado: Operativo
                        <i class="fas fa-circle fa-fade text-success ml-2"></i>
                        </span>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Contenedores -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card dashboard-card card-containers h-100">
                                <div class="card-body">
                                    <div class="dashboard-header text-center">
                                        <div class="dashboard-icon">
                                            <i class="fas fa-boxes-stacked"></i>
                                        </div>
                                        <h6 class="dashboard-title">Contenedores</h6>
                                    </div>

                                    <div class="row no-gutters dashboard-metrics">
                                        <div class="col-6 text-center metric-divider">
                                            <div class="metric-label">Contenedores</div>
                                            <div class="metric-value">
                                                <?=$port->getTotalContainer($admin);?>
                                            </div>
                                        </div>
                                        <div class="col-6 text-center">
                                            <div class="metric-label">Pallets</div>
                                            <div class="metric-value">
                                                <?=$port->getTotalContainerPallets($admin);?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Termos -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card dashboard-card card-thermos h-100">
                                <div class="card-body">
                                    <div class="dashboard-header text-center">
                                        <div class="dashboard-icon">
                                            <i class="fas fa-snowflake"></i>
                                        </div>
                                        <h6 class="dashboard-title">Termos</h6>
                                    </div>

                                    <div class="row no-gutters dashboard-metrics">
                                        <div class="col-6 text-center metric-divider">
                                            <div class="metric-label">Camiones</div>
                                            <div class="metric-value">
                                                <?=$port->getTotalThermo($admin);?>
                                            </div>
                                        </div>
                                        <div class="col-6 text-center">
                                            <div class="metric-label">Pallets</div>
                                            <div class="metric-value">
                                                <?=$port->getTotalPallets($admin);?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Camiones por día -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card dashboard-card card-daily h-100">
                                <div class="card-body">
                                    <div class="dashboard-header text-center">
                                        <div class="dashboard-icon">
                                            <i class="fas fa-ranking-star"></i>
                                        </div>
                                        <h6 class="dashboard-title">Camiones por día</h6>
                                    </div>

                                    <div class="text-center dashboard-single-metric">
                                        <div class="metric-label">Promedio por día</div>
                                        <?php $totalTrucks = $port->avgTrucksPerDay(); ?>
                                        <div class="metric-value metric-value-lg">
                                            <?=$totalTrucks?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Arribos -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card dashboard-card card-arrivals h-100">
                                <div class="card-body">
                                    <div class="dashboard-header text-center">
                                        <div class="dashboard-icon">
                                            <i class="fas fa-truck-moving"></i>
                                        </div>
                                        <h6 class="dashboard-title">Arribos</h6>
                                    </div>

                                    <?php
                                    $totalTrucks = $port->getTotalTrucks($admin);
                                    $trucksArrivedTrucks = $port->getTotalArrivedTrucks($admin);
                                    $trucksInAntepuerto = $port->getTotalTrucksInAnpuerto($admin);
                                    ?>

                                    <div class="text-center dashboard-single-metric">
                                        <div class="metric-label">Total camiones arribados</div>
                                        <div class="metric-value metric-value-lg">
                                            <?=$totalTrucks?>
                                        </div>
                                    </div>

                                    <div class="dashboard-status status-success">
                                        <span>Solicitados</span>
                                        <strong><?=$trucksArrivedTrucks?></strong>
                                        <i class="fas fa-info-circle text-info"
                                        role="button"
                                        tabindex="0"
                                        data-toggle="popover"
                                        data-trigger="hover focus"
                                        data-placement="top"
                                        data-content="Camiones solicitados por terminal."
                                        aria-label="Información sobre camiones solicitados"></i>
                                    </div>

                                    <div class="dashboard-status status-danger">
                                        <span>Pendientes</span>
                                        <strong><?=$trucksInAntepuerto?></strong>
                                        <i class="fas fa-info-circle text-info"
                                        role="button"
                                        tabindex="0"
                                        data-toggle="popover"
                                        data-trigger="hover focus"
                                        data-placement="top"
                                        data-content="Camiones que se encuentran en antepuerto."
                                        aria-label="Información sobre camiones en antepuerto"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ocupación -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card dashboard-card card-capacity h-100">
                                <div class="card-body">
                                    <div class="dashboard-header text-center">
                                        <div class="dashboard-icon">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                        <h6 class="dashboard-title">Ocupación</h6>
                                    </div>

                                    <?php
                                    $percentUsage = $port->getPercentUsage(
                                        $infoCfg['goals'],
                                        $admin
                                    );
                                    $trucksInAntepuerto = $port->getTotalTrucksInAnpuerto($admin);
                                    $progressWidth = max(0, min(100, (float) $percentUsage));
                                    ?>

                                    <div class="text-center dashboard-single-metric">
                                        <div class="metric-label">Ocupación antepuerto</div>
                                        <div class="metric-value metric-value-lg">
                                            <?=htmlspecialchars((string) $percentUsage, ENT_QUOTES, 'UTF-8')?>%
                                        </div>
                                    </div>

                                    <div class="progress dashboard-progress">
                                        <div id="myProgressBar"
                                            class="progress-bar bg-info"
                                            role="progressbar"
                                            style="width: <?=$progressWidth?>%;"
                                            aria-valuenow="<?=$progressWidth?>"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>

                                    <div class="capacity-caption text-center">
                                        <strong><?=$trucksInAntepuerto?></strong>
                                        camiones de un total de
                                        <strong><?=$infoCfg['goals']?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Últimos camiones enviados -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card dashboard-card card-dispatched h-100">
                                <div class="card-body">
                                    <div class="dashboard-header text-center">
                                        <div class="dashboard-icon">
                                            <i class="fas fa-anchor-circle-check"></i>
                                        </div>
                                        <h6 class="dashboard-title">Últimos camiones enviados</h6>
                                    </div>

                                    <div class="dispatched-caption">
                                        Últimos 5 camiones enviados
                                    </div>

                                    <div class="dispatched-list">
                                        <?=$port->getLastSentTrucks();?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Camiones Por Día -->
                        <?php if ($admin): ?>
                        <div class="col-xl-12 col-md-12 mb-4">
                            <div class="card dashboard-card dashboard-chart-card card-capacity h-100">
                                <div class="card-body">
                                    <!-- Cabecera -->
                                    <div class="dashboard-chart-header">
                                        <div class="dashboard-icon">
                                            <i class="fas fa-chart-column"></i>
                                        </div>

                                        <div>
                                            <h6 class="dashboard-title">
                                                Movimientos por Día
                                            </h6>
                                            <p class="dashboard-chart-subtitle mb-0">
                                                Consulta y analiza los movimientos por período
                                            </p>
                                        </div>
                                    </div>

                                    <div class="dashboard-chart-divider"></div>

                                    <!-- Filtros -->
                                    <div class="chart-filters">
                                        <div class="form-group mb-3">
                                            <label for="fechaInicio">Desde</label>
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="fechaInicio">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="fechaFin">Hasta</label>
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="fechaFin">
                                        </div>
                                    </div>

                                    <!-- Gráfico -->
                                    <div class="dashboard-chart-container">
                                        <div id="divGraficoCamiones">
                                            <div
                                                id="mensajeSinDatos"
                                                class="d-none justify-content-center align-items-center">
                                                <?php
                                                echo $alerts->customAlert(
                                                    'danger',
                                                    'Atención',
                                                    'No hay datos disponibles para las fechas seleccionadas. Por favor, ajusta el rango e intenta nuevamente.'
                                                );
                                                ?>
                                            </div>

                                            <canvas id="graficoCamiones"></canvas>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

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

    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header modal-color text-white py-2 px-3">
                    <h6 class="modal-title font-weight-bold mb-0"id="exampleModalLabel">
                        <i class="fas fa-info-circle mr-2"></i> Ocupación en Antepuerto
                    </h6>
                    <button type="button" class="close text-white p-0 m-0" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <?php $total = $port->getTotalTrucksInAnpuerto($admin); ?>
                    <?php $color = $total == 0 ? 'success' : 'danger';?>
                    <?php $message = $total > 0 ? '<br> <b>Por favor regulariza la situación lo antes posible.</b>' : '';?>
                    <?php echo $alerts->customAlert($color, 'Atención', 'Cuentas con un total de <b>' . $total . '</b> camiones en antepuerto.' . $message);?>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat flotante expandible estilo WhatsApp - Responsive -->
    <?php echo $whatsAppBtn; ?>

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

  var modal = new bootstrap.Modal(document.getElementById('messageModal'));
    modal.show();
});

/* Porcentaje de ocupación del antepuerto */
setProgress(<?=$percentUsage;?>); //cambia a 30%

function setProgress(value) {
  const progressBar = document.getElementById('myProgressBar');
  const percentage = Math.min(Math.max(value, 0), 100); /* Entre 0 y 100 */

  if (percentage < 75) {
    progressBar.classList.add("bg-success");
  }else if(percentage >= 75 && percentage < 90){
    progressBar.classList.add("bg-warning");
  }else if(percentage >= 90){
    progressBar.classList.add("bg-danger");
  }

  progressBar.style.width = percentage + '%';
  progressBar.setAttribute('aria-valuenow', percentage);
}

/* Dibuja el gráfico de barras */
<?php if ($admin): ?>
const ctx = document.getElementById('graficoCamiones').getContext('2d');
let chart;

const today = new Date().toLocaleString("en-CA", { timeZone: "America/Santiago", year: "numeric", month: "2-digit", day: "2-digit" });
document.getElementById('fechaInicio').setAttribute('max', today);
document.getElementById('fechaFin').setAttribute('max', today);

/* Obtener datos desde PHP */
async function cargarDatos(fechaInicio, fechaFin) {
  if (fechaInicio > fechaFin) {
    Swal.fire({
      title: 'Error',
      text: 'La fecha de inicio no puede ser posterior a la fecha de fin.',
      icon: 'error',
      confirmButtonColor: '#d33'
    });
    return [];
  }

  const res = await fetch('../controllers/getTrucksPerDayController.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ fechaInicio, fechaFin })
  });

  return await res.json();
}

/* Dibujar el gráfico */
async function filtrarYActualizar(fechaInicio, fechaFin) {
  const datos = await cargarDatos(fechaInicio, fechaFin);
  const mensaje = document.getElementById('mensajeSinDatos');
  const canvas = document.getElementById('graficoCamiones');
  const divCanvas = document.getElementById('divGraficoCamiones');
  const hayDatos = Array.isArray(datos) && datos.length > 0;

  // UI estado
  mensaje.style.display = hayDatos ? 'none' : 'flex';
  canvas.style.display = hayDatos ? 'block' : 'none';
  divCanvas.style.height = hayDatos ? '400px' : '120px';

  if (hayDatos) {
    mensaje.classList.add('d-none');
    canvas.style.display = 'block';
    divCanvas.style.height = '400px';
  } else {
    mensaje.classList.remove('d-none');
    canvas.style.display = 'none';
    divCanvas.style.height = '120px';

    if (chart) {
      chart.destroy();
      chart = null;
    }

    return;
  }

  const labels = datos.map(d => d.Fecha);
  const config = {
    type: 'bar',
    data: {
      labels,
      datasets: [
        {
          label: 'Ingresados',
          data: datos.map(d => d.Ingresos),
          backgroundColor: 'rgba(75,192,192,0.7)',
          borderColor: 'rgba(75,192,192,1)',
          borderWidth: 1
        },
        {
          label: 'Despachados',
          data: datos.map(d => d.Egresos),
          backgroundColor: 'rgba(255,99,132,0.7)',
          borderColor: 'rgba(255,99,132,1)',
          borderWidth: 1
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        datalabels: {
          align: 'top',
          anchor: 'end',
          font: { weight: 'bold' },
          color: '#000'
        },
        tooltip: {
          callbacks: {
            title: ctx => ctx[0].label,
            label: ctx => `${ctx.dataset.label}: ${ctx.raw}`
          }
        }
      },
      scales: {
        x: { title: { display: true, text: 'Fecha' } },
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Cantidad de Camiones' },
          ticks: { stepSize: 1, precision: 0 }
        }
      }
    },
    plugins: [ChartDataLabels]
  };

  if (chart) chart.destroy();
  chart = new Chart(ctx, config);
}

/* Setear valores por defecto (últimos 7 días) */
function formatDate(date) {
  return date.toISOString().split('T')[0];
}

const hoy = new Date();
const hace7dias = new Date();
hace7dias.setDate(hoy.getDate() - 7);

document.getElementById('fechaInicio').value = formatDate(hace7dias);
document.getElementById('fechaFin').value = formatDate(hoy);

/* Cargar gráfico inicial */
filtrarYActualizar(formatDate(hace7dias), formatDate(hoy));

/* Eventos de filtros */
document.getElementById('fechaInicio').addEventListener('change', e => {
  const fin = document.getElementById('fechaFin').value;
  filtrarYActualizar(e.target.value, fin);
});
document.getElementById('fechaFin').addEventListener('change', e => {
  const inicio = document.getElementById('fechaInicio').value;
  filtrarYActualizar(inicio, e.target.value);
});
<?php endif; ?>
</script>
