<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$cfg = new cfg();
$user = new user();

$infoCfg = json_decode($cfg->getInfo(1), true);
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
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>FYGroup | Programación MSC</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Live Map MarineTraffic</h1>
                    <p class="mb-4">Acá puedes revisar el live map del Puerto de Coquimbo, el cual te mostrará las naves que están actualmente en la zona.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <!-- Custom Text Color Utilities -->
                            <div style="width:100%;">
                                <div style="position:relative; width:100%; padding-bottom:56.25%; overflow:hidden; border-radius:10px;">
                                    <iframe
                                        src="https://www.marinetraffic.com/es/ais/embed/centerx:-71.323/centery:-29.947/zoom:14/maptype:2/shownames:true/mmsi:0/shipid:0/fleet:/fleet_id:/vtypes:/showmenu:false/remember:false"
                                        style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;"
                                        loading="lazy">
                                    </iframe>
                                </div>
                            </div>

                            <div class="text-center mb-4 logo-div">
                                <img src="../logos/logo-marinetraffic.png" class="logo-responsive">
                                <h6 class="m-0 font-weight-bold text-center small text-primary">
                                    Powered by MarineTraffic.
                                </h6>
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
        window.location = '<?php echo generateMkey('marinetraffic_live_map'); ?>';
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
