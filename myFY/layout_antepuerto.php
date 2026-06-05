<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

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
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>FYGroup | Layout Antepuerto</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/fygroup.css" rel="stylesheet">

    <!-- Custom styles FYGroup-->
    <link rel="stylesheet" href="../assets/css/app.css">
    <script src="../assets/js/sidebar.js"></script>
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
                <div class="container-fluid">
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
                                        <h2>CONTROL PORTUARIO</h2>
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

                                <!-- CARRETERA -->
                                <div class="highway"></div>

                                <!-- PORTÓN -->
                                <div class="access-lane"></div>

                                <!-- ZONA DE ACCESO -->
                                <div class="access-zone">
                                    <!-- GARITA -->
                                    <div class="gate">
                                        <div class="gate-window left"></div>
                                        <div class="gate-window right"></div>
                                        <div class="gate-door"></div>
                                        <div class="gate-label">CONTROL</div>
                                    </div>

                                    <!-- CAMINO INTERIOR -->
                                    <div class="road-entry">
                                        <div class="barrier"></div>
                                    </div>
                                </div>

                                <!-- APARCADERO -->
                                <div class="parking">
                                    <div class="parking-header">
                                        <div class="parking-title">
                                            APARCADERO
                                        </div>

                                        <div class="parking-status">
                                            <div class="badge green">NORMAL</div>
                                            <div class="badge yellow">MEDIA</div>
                                            <div class="badge red">ALTA</div>
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
</body>
</html>

<!-- JAVASCRIPT -->
<script>
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
        window.location = '<?php echo generateMkey('layout_antepuerto'); ?>';
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

var saveInfoUser = function() {
  const password = $('#password').val();
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
  let hasError = false;

  /* Revisa que la contraseña tenga los caracteres obligatorios */
  if (!regex.test(password)) {
    Swal.fire({
      title: 'Oops...',
      text: 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.',
      icon: 'error',
      cancelButtonColor: '#d33',
    });

    hasError = true;
  }

  if(!hasError){
    $.ajax({
      url: '../controllers/userSaveController.php',
      data: $('#editUserInfoForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          html: '¡Información actualizada con éxito! </br> Por motivos de seguridad deberás iniciar sesión nuevamente.',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = 'logout.php';
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: 'Error al actualizar la información.',
          icon: 'error',
          cancelButtonColor: '#d33',
        });
      }
    });
  }
}

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
function loadMap() {
  fetch('../controllers/layoutAntepuertoController.php?action=data').then(r => r.json()).then(data => {
    const parkingGrid = document.getElementById('parkingGrid');
    parkingGrid.innerHTML = '';

    let total = data.length;

    data.forEach((t, index) => {
      let statusClass = 'normal';

      if(t.status === 'MEDIA'){
        statusClass = 'medium';
      }

      if(t.status === 'ALTA'){
        statusClass = 'high';
      }

      const div = document.createElement('div');
      div.className = `truck-slot ${statusClass}`;
      div.innerHTML = `
        <div class="slot-number">${index + 1}</div>

        <div class="truck-plate">
          ${t.patente}
        </div>

        <div class="truck-info">
          <div><b>Guía:</b> ${t.guide || '-'}</div>
          <div><b>Nave:</b> ${t.ship || '-'}</div>
          <div><b>Contenedor:</b> ${t.container || '-'}</div>
          <div><b>Ingreso:</b> ${t.arrival || '-'}</div>
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
    updateHeatmap(total);
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
function updateHeatmap(total){
  const parking = document.querySelector('.parking');
  const status = document.getElementById('statusCounter');

  parking.classList.remove(
    'low',
    'medium',
    'high'
  );

  if(total <= 5){
    parking.classList.add('low');
    status.innerHTML = 'NORMAL';
  } else if(total <= 10){
    parking.classList.add('medium');
    status.innerHTML = 'MEDIA';
  } else{
    parking.classList.add('high');
    status.innerHTML = 'ALTA';
  }
}

$(document).ready(function() {
  //init map
  loadMap();
});
</script>
