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
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>FYGroup | Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/fygroup.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <!-- Custom styles FYGroup-->
    <link rel="stylesheet" href="../assets/css/app.css">
    <script src="../assets/js/sidebar.js"></script>

    <style>
      body {
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
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Contenedores -->
                        <div class="col-xl-2 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #4e73df !important;border-radius:8px;">
                              <div class="text-center">
                                <i class="fas fa-boxes-stacked fa-2x text-primary mb-2"></i>
                                <h6 class="text-primary text-uppercase mb-3">Contenedores</h6>
                              </div>
                              <div class="d-flex justify-content-center">
                                <div class="text-center mr-5">
                                  <div class="text-muted small">Contenedores</div>
                                  <div class="h5 font-weight-bold text-dark"><?=$port->getTotalContainer($admin);?></div>
                                </div>

                                <div class="text-center">
                                  <div class="text-muted small">Pallets</div>
                                  <div class="h5 font-weight-bold text-dark"><?=$port->getTotalContainerPallets($admin);?></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Termos -->
                        <div class="col-xl-2 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #1cc88a !important;border-radius:8px;">
                              <div class="text-center">
                                <i class="fas fa-snowflake fa-2x text-success mb-2"></i>
                                <h6 class="text-success text-uppercase mb-3">Termos</h6>
                              </div>
                              <div class="d-flex justify-content-center">
                                <div class="text-center mr-5">
                                    <div class="text-muted small">Camiones</div>
                                    <div class="h5 font-weight-bold text-dark">
                                        <?=$port->getTotalThermo($admin);?>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <div class="text-muted small">Pallets</div>
                                    <div class="h5 font-weight-bold text-dark">
                                        <?=$port->getTotalPallets($admin);?>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Camiones por día -->
                        <div class="col-xl-2 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #5a5c69 !important;border-radius:8px;">
                              <div class="text-center">
                                <i class="fas fa-ranking-star fa-2x text-dark mb-2"></i>
                                <h6 class="text-dark text-uppercase mb-3">Camiones por día</h6>
                              </div>
                              <div class="text-center">
                                <?php $totalTrucks = $port->avgTrucksPerDay(); ?>
                                <div class="text-muted small">Promedio por día</div>
                                <div class="h5 font-weight-bold text-dark"><?=$totalTrucks?></div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Arrivos -->
                        <div class="col-xl-2 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #f6c23e !important;border-radius:8px;">
                              <div class="text-center">
                                <i class="fas fa-truck-moving fa-2x text-warning mb-2"></i>
                                <h6 class="text-warning text-uppercase mb-3">Arrivos</h6>
                              </div>
                              <div class="text-center">
                                <?php $totalTrucks = $port->getTotalTrucks($admin); ?>
                                <?php $trucksInAntepuerto = $port->getTotalTrucksInAnpuerto($admin); ?>
                                <?php $trucksArrivedTrucks = $port->getTotalArrivedTrucks($admin); ?>
                                <div class="text-muted small">Total Camiones Arrivados</div>
                                <div class="h5 font-weight-bold text-dark"><?=$totalTrucks?></div>

                                <div class="mb-1">
                                  <small class="text-success font-weight-bold">
                                    Solicitados: <?=$trucksArrivedTrucks?>
                                    <i class="fas fa-info-circle text-info"
                                        role="button"
                                        data-toggle="popover"
                                        data-trigger="hover focus"
                                        data-placement="right"
                                        data-content="Camiones solicitados por terminal.">
                                    </i>
                                  </small>
                                </div>
                                <div>
                                  <small class="text-danger font-weight-bold">
                                    Pendientes: <?=$trucksInAntepuerto?>
                                    <i class="fas fa-info-circle text-info"
                                        role="button"
                                        data-toggle="popover"
                                        data-trigger="hover focus"
                                        data-placement="right"
                                        data-content="Camiones que se encuentran en antepuerto.">
                                    </i>
                                  </small>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Capacidad -->
                        <div class="col-xl-2 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #36b9cc !important;border-radius:8px;">
                              <div class="text-center mb-3">
                                <i class="fas fa-chart-pie fa-2x text-info mb-2"></i>
                                <h6 class="text-info text-uppercase">Ocupación</h6>
                              </div>
                              <div class="text-center mb-2">
                                <div class="text-muted small">Ocupación Antepuerto</div>
                                <div class="h5 font-weight-bold text-dark">
                                  <?php $percentUsage = $port->getPercentUsage($infoCfg['goals'], $admin); ?>
                                  <?php echo $percentUsage . '%'; ?>
                                </div>
                              </div>
                              <div class="progress mb-2" style="height: 8px;">
                                <div id="myProgressBar" class="progress-bar bg-info" role="progressbar"
                                  style="width: <?=$percentUsage?>%;" aria-valuenow="<?=$percentUsage?>" aria-valuemin="0" aria-valuemax="100">
                                </div>
                              </div>
                              <small class="d-block text-muted text-center">
                                <?=$port->getTotalTrucksInAnpuerto($admin)?> camiones de un total de <?=$infoCfg['goals']?>.
                              </small>
                            </div>
                          </div>
                        </div>

                        <!-- Camiones Enviados -->
                        <div class="col-xl-2 col-md-6 mb-4">
                          <div class="card bg-light shadow-sm h-100">
                            <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #e74a3b !important;border-radius:8px;">
                              <div class="text-center mb-3">
                                <i class="fas fa-anchor-circle-check fa-2x text-danger mb-2"></i>
                                <h6 class="text-danger text-uppercase">Últimos Camiones Enviados</h6>
                              </div>
                              <small class="d-block text-muted text-center">
                                Muestra los últimos 5 camiones enviados.
                              </small>
                              <small class="d-block text-dark text-center" style="font-size: 11px;">
                                <?=$port->getLastSentTrucks();?>
                              </small>
                            </div>
                          </div>
                        </div>

                        <!-- Gráfico de Camiones Por Día -->
                        <?php if ($admin): ?>
                          <div class="col-xl-12 col-md-6 mb-4">
                            <div class="card bg-light shadow-sm h-100">
                              <div class="card-body" style="border:1px solid #e5e7eb;border-left:4px solid #36b9cc !important;border-radius:8px;">
                                <div class="text-center mb-3">
                                  <i class="fas fa-chart-column fa-2x text-info mb-2"></i>
                                  <h6 class="text-info text-uppercase">Movimientos por Día</h6>
                                </div>
                                <div class="form-group row justify-content-center">
                                  <div class="col-12 col-md-auto me-md-4 mb-3">
                                    <label for="fechaInicio" class="text-dark font-weight-bold">Desde</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaInicio">
                                  </div>
                                  <div class="col-12 col-md-auto me-md-4 mb-3">
                                    <label for="fechaFin" class="text-dark font-weight-bold">Hasta</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaFin">
                                  </div>
                                </div>
                                <div class="row justify-content-center">
                                  <div class="col-12">
                                    <div id="divGraficoCamiones" style="position: relative;">
                                        <div id="mensajeSinDatos" class="col-sm-12 d-none justify-content-center align-items-center">
                                            <?php echo $alerts->customAlert('danger', 'Atención', 'No hay datos disponibles para las fechas seleccionadas. </br> Por favor ajusta el rango e intenta nuevamente.'); ?>
                                        </div>

                                      <canvas id="graficoCamiones"></canvas>
                                    </div>
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

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/fygroup.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Chat flotante expandible estilo WhatsApp - Responsive -->
    <?php echo $whatsAppBtn; ?>
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

var saveNewGoals = function() {
  $.ajax({
    url: '../controllers/configSaveController.php',
    data: $('#addGoalForm').serialize(),
    type: 'POST',
  }).done(function(x) {
    if(x == 'OK'){
      Swal.fire({
        title: '¡Éxito!',
        text: '¡Ocupación actualizada con éxito!',
        icon: 'success',
        confirmButtonColor: '#4CAF50'
      }).then((result) => {
        window.location = 'dashboard.php';
      });
    } else {
      Swal.fire({
        title: 'Oops...',
        text: 'Error al actualizar la ocupación.',
        icon: 'error',
        cancelButtonColor: '#d33',
      });
    }
  });
}
</script>
