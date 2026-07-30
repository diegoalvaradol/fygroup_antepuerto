<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';
require_once __DIR__ . '/../config/system_status_config.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

error_reporting(E_ALL);

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
$dev = $user->isdev($_SESSION['user']['run']);
$releasedTime = new DateTime($infoCfg['released_date']);
$updateTime = new DateTime($infoCfg['update_date']);
$arrayDivision = get::getDivisionName();
$sideBarSSL = menu::sideBarSSL();
$mainTapBarSSL = menu::mainTapBarSSL();
$footer = menu::footerSSL();
$top = UIComponents::scrollToTopButton();
$modals = new Modals($infoCfg, $arrayDivision, $releasedTime, $updateTime);

/* Rutas de acceso */
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $myFyUrl = '/fygroup-antepuerto/myFY/login.php';
    $myPortalUrl = '/fygroup-antepuerto/myPortal/login.php';
    $myDevlUrl = '/fygroup-antepuerto/dev/login.php';
} else {
    $myFyUrl = 'https://antepuerto.fygroup.cl/myFY/login.php';
    $myPortalUrl = 'https://portalcliente.fygroup.cl/myPortal/login.php';
    $myDevlUrl = 'https://dev.fygroup.cl/dev/login.php';
}

/* Validar desarrollador */
if (!$dev) {
    $usuario = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . ' (' . $_SESSION['user']['run'] . ')';
    $pag = basename(__FILE__);
    $url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    mostrarAccesoDenegado($usuario, $pag, $url);
}

$message = null;
$messageType = null;
$saved = false;
$configFile = __DIR__ . '/../config/system_status_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apps = ['FYGROUP', 'PORTALCLIENTE', 'DEV'];
    $newConfig = [];
    $errors = [];

    foreach ($apps as $app) {
        $mode = $_POST[$app]['mode'] ?? 'online';
        $rawStart = $_POST[$app]['start'] ?? '';
        $rawEnd = $_POST[$app]['end'] ?? '';
        $start = !empty($rawStart) ? str_replace('T', ' ', $rawStart) . ':00' : null;
        $end = !empty($rawEnd) ? str_replace('T', ' ', $rawEnd) . ':00' : null;

        // Validaciones
        if ($mode !== 'online') {
            if (empty($start)) {
                $errors[$app]['start'] = 'Debe ingresar fecha inicio.';
            }

            if (empty($end)) {
                $errors[$app]['end'] = 'Debe ingresar fecha término.';
            }

            if (!empty($start) && !empty($end)) {
                if (strtotime($end) <= strtotime($start)) {
                    $errors[$app]['end'] = 'La fecha término debe ser mayor a la fecha inicio.';
                }
            }

            if (isset($errors[$app])) {
                continue;
            }
        }

        $newConfig[$app] = [
            'maintenance' => $mode === 'maintenance',
            'maintenance_start' => $mode === 'maintenance' ? $start : null,
            'maintenance_end' => $mode === 'maintenance' ? $end : null,
            'closed' => $mode === 'closed',
            'closed_start' => $mode === 'closed' ? $start : null,
            'closed_end' => $mode === 'closed' ? $end : null,
        ];
    }

    // Si existen errores
    if (!empty($errors)) {
        $message = 'Revise los campos marcados.';
        $messageType = 'error';
        $saved = false;
    } else {
        $content = "<?php\n\n";
        $content .= "declare(strict_types=1);\n\n";
        $content .= 'const SYSTEM_STATUS = ';
        $content .= var_export($newConfig, true);
        $content .= ";\n";

        if (file_put_contents($configFile, $content) !== false) {
            $message = 'Configuración guardada correctamente.';
            $messageType = 'success';
            $saved = true;
        } else {
            $message = 'No se pudo guardar el archivo.';
            $messageType = 'error';
            $saved = false;
        }
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
    <title>FYGroup | Estado Sistemas</title>

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
                    <h1 class="h3 mb-1 text-gray-800">Estado Sistemas</h1>
                    <p class="mb-4">Acá puedes revisar el estado de cada uno de los sistemas y realizar cambios en su programación.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <!-- Custom Text Color Utilities -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Formulario de Programación</h6>
                                </div>

                                <div class="card-body">
                                    <form class="form-container"  method="POST">
                                        <?php $apps = ['FYGROUP' => 'FYGroup (Antepuerto)','PORTALCLIENTE' => 'Portal Cliente','DEV' => 'Dev',];?>
                                        <?php $accessLink = null; ?>

                                        <?php foreach ($apps as $key => $title) :?>
                                            <?php $item = SYSTEM_STATUS[$key]; ?>
                                            <?php
                                            if ($item['maintenance']) {
                                                $mode = 'maintenance';
                                            } elseif ($item['closed']) {
                                                $mode = 'closed';
                                            } else {
                                                $mode = 'online';
                                            }

                                            if ($key == 'FYGROUP') {
                                                $accessLink = '
                                                    <a href="' . $myFyUrl . '" class="btn btn-outline-primary btn-block" target="_blank">
                                                        <i class="fas fa-ship"></i> Acceso FYGroup
                                                    </a>
                                                ';
                                            } elseif ($key == 'PORTALCLIENTE') {
                                                $accessLink = '
                                                    <a href="' . $myPortalUrl . '" class="btn btn-outline-success btn-block" target="_blank">
                                                        <i class="fas fa-user"></i> Acceso Portal Cliente
                                                    </a>
                                                ';
                                            } elseif ($key == 'DEV') {
                                                $accessLink = '
                                                    <a href="' . $myDevlUrl . '" class="btn btn-outline-primary btn-block" target="_blank">
                                                        <i class="fas fa-code"></i> Acceso Developers
                                                    </a>
                                                ';
                                            }
                                            ?>

                                            <div class="form-group row">
                                                <div class="col-sm-2">
                                                    <h6 class="m-0 font-weight-bold text-primary">
                                                        <i class="fas fa-desktop"></i>
                                                        <?= $title ?>
                                                    </h6>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-3">
                                                    <label for="<?= $key ?>[mode]" class="text-gray-800 font-weight-bold">Estado</label>
                                                    <select class="form-control" name="<?= $key ?>[mode]">
                                                        <option value="online"
                                                            <?= $mode == 'online' ? 'selected' : '' ?>>
                                                            Operativo
                                                        </option>

                                                        <option value="maintenance"
                                                            <?= $mode == 'maintenance' ? 'selected' : '' ?>>
                                                            Mantención
                                                        </option>

                                                        <option value="closed"
                                                            <?= $mode == 'closed' ? 'selected' : '' ?>>
                                                            Cerrado
                                                        </option>
                                                    </select>
                                                    <small class="text-danger" id="<?= $key ?>[mode]"></small>
                                                </div>

                                                <div class="col-sm-3">
                                                    <label for="<?= $key ?>[start]" class="text-gray-800 font-weight-bold">Inicio</label>
                                                    <div class="input-group">
                                                        <input type="datetime-local"
                                                            class="form-control"
                                                            id="<?= $key ?>[start]"
                                                            name="<?= $key ?>[start]"
                                                            value="<?= $item['maintenance'] ? date('Y-m-d\TH:i', strtotime($item['maintenance_start'])) : ($item['closed'] && !empty($item['closed_start']) ? date('Y-m-d\TH:i', strtotime($item['closed_start'])) : '') ?>">

                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-outline-danger clear-datetime"
                                                                data-target="<?= $key ?>[start]"
                                                                title="Eliminar fecha">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger" id="<?= $key ?>[start]-error"></small>
                                                </div>

                                                <div class="col-sm-3">
                                                    <label for="<?= $key ?>[end]" class="text-gray-800 font-weight-bold">Término</label>
                                                    <div class="input-group">
                                                        <input type="datetime-local"
                                                            class="form-control"
                                                            id="<?= $key ?>[end]"
                                                            name="<?= $key ?>[end]"
                                                            value="<?= $item['maintenance'] ? date('Y-m-d\TH:i', strtotime($item['maintenance_end'])) : ($item['closed'] && !empty($item['closed_end']) ? date('Y-m-d\TH:i', strtotime($item['closed_end'])) : '') ?>">

                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-outline-danger clear-datetime"
                                                                data-target="<?= $key ?>[end]"
                                                                title="Eliminar fecha">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger" id="<?= $key ?>[end]-error"></small>
                                                </div>

                                                <div class="col-sm-3">
                                                    <label for="accessLinks" class="text-gray-800 font-weight-bold">Link de Accesos</label>
                                                    <?php echo  $accessLink; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <button type="submit" class="btn btn-primary btn-sm btn-user"><i class="fas fa-check-circle"></i> Guardar</button>
                                        <button type="reset" class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-eraser'></i> Limpiar</button>
                                    </form>
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

<script>
document.addEventListener('click', function (event) {
  const button = event.target.closest('.clear-datetime');

  if (!button) return;

  const input = document.getElementById(button.dataset.target);
  if (input) {
    input.value = '';
    input.dispatchEvent(new Event('change', { bubbles: true }));
  }
});

document.addEventListener('DOMContentLoaded', function () {
  const errors = <?= json_encode($errors ?? []) ?>;
  // Mostrar errores en campos
  Object.keys(errors).forEach(app => {

    if (errors[app].start) {
      const input =document.getElementById(app + '[start]');
      const error =document.getElementById(app + '[start]-error');

      if (input) {
        input.classList.add('is-invalid');
      }

      if (error) {
        error.textContent = errors[app].start;
      }
    }

    if (errors[app].end) {
      const input =document.getElementById(app + '[end]');
      const error =document.getElementById(app + '[end]-error');

      if (input) {
        input.classList.add('is-invalid');
      }

      if (error) {
        error.textContent = errors[app].end;
      }
    }
  });

  // Mostrar Swal SOLO después de enviar formulario
  <?php if ($saved): ?>
    Swal.fire({
      icon: <?= json_encode($messageType) ?>,
      title: <?= $messageType === 'success' ? "'Éxito'" : "'Error'" ?>,
      text: <?= json_encode($message) ?>,
      confirmButtonText: 'Aceptar'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = '<?= generateSecureLink('system_status_manager'); ?>';
      }
    });
  <?php endif; ?>
});
</script>
