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
$admin = $user->isAdmin($_SESSION['user']['run']);
$releasedTime = new DateTime($infoCfg['released_date']);
$updateTime = new DateTime($infoCfg['update_date']);
$arrayDivision = get::getDivisionName();
$sideBarSSL = menu::sideBarSSL();
$mainTapBarSSL = menu::mainTapBarSSL();
$footer = menu::footerSSL();
$top = UIComponents::scrollToTopButton();
$modals = new Modals($infoCfg, $arrayDivision, $releasedTime, $updateTime);
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>FYGroup | Layout Antepuerto</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Layout Antepuerto</h1>
                    <p class="mb-4">Acá podrás visualizar el transito en tiempo real en el antepuerto.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <!-- MAP -->
                            <div id="map">
                                <!-- HEADER -->
                                <div class="topbar-layout">
                                    <div class="logo">
                                        <h2>CONTROL ANTEPUERTO</h2>
                                        <span>Visualización logística de antepuerto</span>
                                    </div>

                                    <div class="stats">
                                        <div class="stat-card">
                                            <div class="card-title">CAMIONES</div>
                                            <div class="card-value" id="truckCounter">0 / 0</div>
                                        </div>

                                        <div class="stat-card">
                                            <div class="card-title">ESTADO</div>
                                            <div class="card-value" id="statusCounter">NORMAL</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- APARCADERO -->
                                <div class="parking">
                                    <div class="parking-header">
                                        <div class="parking-title">
                                            APARCADERO
                                        </div>

                                        <div class="stat-card">
                                            <div class="card-title">PERIODO DE ESTADÍA</div>
                                            <div class="parking-status">
                                                <div class="badge green">< 2 hrs</div>
                                                <div class="badge yellow">2 a 4 hrs</div>
                                                <div class="badge red">> 4 hrs</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="parkingGrid"></div>
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
const ZONES = {
  PARKING: {
    slots: createSlots(0.05, 0.30, 9, 2)
  }
};

/* Crea posiciones */
function createSlots(startX, startY, cols, rows){
  const slots = [];
  const stepX = 0.074;
  const stepY = 0.115;

  for(let y = 0; y < rows; y++){
    for(let x = 0; x < cols; x++){
      slots.push({
        x: startX + (x * stepX),
        y: startY + (y * stepY),
        used:false
      });
    }
  }

  return slots;
}

/* Slot Libre */
function getSlot(zone){
  const slot = zone.slots.find(s => !s.used);

  if(slot){
    slot.used = true;

    return slot;
  }

  return zone.slots[0];
}

/* Mapa */
var loadMap = function () {
  fetch('../controllers/layoutAntepuertoController.php?action=data')
    .then(r => r.json())
    .then(data => {
    const parkingGrid = document.getElementById('parkingGrid');
    parkingGrid.innerHTML = '';

    let total = data.length;

    data.forEach((t, index) => {
      let originClass = '';
      let originText = '';

      if(t.origin === 1){
        originClass = 'container';
        originText = 'Contenedor';
      } else if(t.origin === 2){
        originClass = 'thermo';
        originText = 'Thermo';
      }

      const div = document.createElement('div');
      div.className = `truck-slot ${t.status}`;
      div.innerHTML = `
        <div class="slot-number">${index + 1}</div>

        <div class="slot-type ${originClass}">
          ${originText}
        </div>

        <div class="truck-plate">
          ${t.carplate}
        </div>

        <div class="truck-info">
          <div><b>Guía:</b> ${t.guide}</div>
          <div><b>Exportador:</b> ${t.exporter}</div>
          <div><b>Nave:</b> ${t.ship}</div>
          <div><b>Contenedor:</b> ${t.container || '-'}</div>
          <div><b>Ingreso:</b> ${t.arrival}</div>
          <div style="color: #16a34a;"><b>Estadía:</b> ${t.staytime}</div>
        </div>
      `;

      parkingGrid.appendChild(div);
    });

    const capacidad = <?php echo (int) $infoCfg['goals']; ?>;

    for(let i = total; i < capacidad; i++){
      const empty = document.createElement('div');
      empty.className = 'truck-slot empty';
      empty.innerHTML = `
        <div class="slot-number">${i + 1}</div>
        <div class="empty-label">
          DISPONIBLE
        </div>
      `;

      parkingGrid.appendChild(empty);
    }

    document.getElementById('truckCounter').innerHTML = `${total} <small>/ ${capacidad}</small>`;
    updateHeatmap(total, capacidad);
  }).catch(error => {
    console.error(error);

    Swal.fire({
      title: 'Error',
      text: 'No se pudo cargar el mapa. Intenta recargando la página.',
      icon: 'error',
      confirmButtonColor: '#4e73df'
    });
  });
}

/* Heatmap  */
var updateHeatmap = function(total, capacidad){
  const parking = document.querySelector('.parking');
  const status = document.getElementById('statusCounter');
  const occupancy = (total / capacidad) * 100;

  parking.classList.remove(
    'low',
    'medium',
    'high'
  );

  if(occupancy <= 50){
    parking.classList.add('low');
    status.innerHTML = 'NORMAL';
    status.style.color = '#16a34a';
  } else if(occupancy <= 75){
    parking.classList.add('medium');
    status.innerHTML = 'MEDIA';
    status.style.color = '#f59e0b';
  } else{
    parking.classList.add('high');
    status.innerHTML = 'ALTA';
    status.style.color = '#dc2626';
  }
}

$(document).ready(function() {
  loadMap();
  setInterval(() => {
    loadMap();
  }, 1000);
});
</script>
