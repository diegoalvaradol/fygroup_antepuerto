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
$releasedTime = new DateTime($infoCfg['released_date']);
$updateTime = new DateTime($infoCfg['update_date']);
$arrayDivision = get::getDivisionName();
$sideBarSSL = menu::sideBarSSL();
$mainTapBarSSL = menu::mainTapBarSSL();
$footer = menu::footerSSL();
$top = UIComponents::scrollToTopButton();
$modals = new Modals($infoCfg, $arrayDivision, $releasedTime, $updateTime);

$dev = $user->isDev($_SESSION['user']['run']);
$footer = menu::footerSSL();

/* Validar desarrollador */
if (!$dev) {
    $usuario = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . ' (' . $_SESSION['user']['run'] . ')';
    $pag = basename(__FILE__);
    $url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    mostrarAccesoDenegado($usuario, $pag, $url);
}

function ejecutarQuery($user)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['sql_query'])) {
        return '';
    }

    $sql = trim($_POST['sql_query']);

    ob_start();

    try {
        $db = $user->getDb();

        // Necesario para ejecutar varias sentencias separadas por ";"
        $db->setAttribute(PDO::MYSQL_ATTR_MULTI_STATEMENTS, true);

        $stmt = $db->prepare($sql);
        $stmt->execute();

        $huboResultados = false;
        $filasAfectadas = 0;
        $totalRegistros = 0;
        $numeroResultado = 1;

        do {
            // SELECT, SHOW, DESCRIBE, EXPLAIN, SHOW GRANTS, SHOW DATABASES, etc.
            if ($stmt->columnCount() > 0) {
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($resultados)) {
                    $huboResultados = true;
                    $totalRegistros += count($resultados);
                    ?>
                    <div style="margin-bottom: 25px;">
                        <h5 style="margin-bottom: 10px;">
                            Resultado <?= $numeroResultado ?>
                            <small>(<?= count($resultados) ?> registros)</small>
                        </h5>

                        <div class="table-responsive"
                             style="width:100%; max-height:500px; overflow:auto; border:1px solid #dee2e6; border-radius:12px;">
                            <table class="table"
                                   style="min-width:1200px; white-space:nowrap; border-collapse:separate; border-spacing:0;">
                                <thead style="color:white; position:sticky; top:0; z-index:1;">
                                    <tr>
                                        <?php foreach (array_keys($resultados[0]) as $col): ?>
                                            <th><?= htmlspecialchars($col, ENT_QUOTES, 'UTF-8') ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($resultados as $fila): ?>
                                        <tr>
                                            <?php foreach ($fila as $valor): ?>
                                                <td>
                                                    <?= htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                }

                $numeroResultado++;
            } else {
                // INSERT, UPDATE, DELETE, CREATE, DROP, USE, etc.
                $filasAfectadas += $stmt->rowCount();
            }

        } while ($stmt->nextRowset());

        if ($huboResultados) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Consulta ejecutada',
                    text: 'Se encontraron <?= $totalRegistros ?> registros en total.',
                    timer: 2500,
                    showConfirmButton: false
                });
            </script>
            <?php
        } else {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Query ejecutada correctamente',
                    text: 'Filas afectadas: <?= $filasAfectadas ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>
            <?php
        }

    } catch (PDOException $e) {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error al ejecutar',
                text: <?= json_encode($e->getMessage()) ?>,
                confirmButtonText: 'Aceptar'
            });
        </script>
        <?php
    }

    return ob_get_clean();
}

$resultado = ejecutarQuery($user);
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>FYGroup | SQL Administrador</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <h1 class="h3 mb-1 text-gray-800">Consulta SQL</h1>
                    <p class="mb-4">Acá puedes ejecutar consultas SQL en la base de datos.</p>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-lg">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Consulta SQL</h6>
                                </div>

                                <div class="card-body">
                                    <form class="form-container"  method="POST">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <textarea name="sql_query" id="sql_query" class="form-control" rows="5" placeholder="Escribe aquí tu consulta SQL..." required><?= htmlspecialchars($_POST['sql_query'] ?? '') ?></textarea>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm btn-user"><i class="fas fa-check-circle"></i> Ejecutar</button>
                                        <button type="reset" class='btn btn-warning btn-sm btn-user' onclick='location.href=window.location.href'><i class='fas fa-eraser'></i> Limpiar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-lg-12'>
                            <div class='d-flex justify-content-between align-items-center mb-3 flex-wrap'>
                                <div>
                                    <h1 class='h3 mb-1 text-gray-800 d-inline'>Listado</h1>
                                </div>
                            </div>

                            <div class='card shadow mb-4'>
                                <?= $resultado ?>
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

    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>

<script>
  window.onload = () => {
    const alert = document.querySelector('.alert');
    if (alert) {
      setTimeout(() => {
        alert.style.transition='opacity 0.5s';
        alert.style.opacity='0';
        setTimeout(()=>alert.remove(),500);
      }, 4000);
    }

    if (document.getElementById('sqlResults')) {
      $('#sqlResults').DataTable({
        paging: true,
        searching: true,
        scrollX: true,
        pageLength: 10,
        lengthChange: false,
        info: true,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        dom: '<"d-flex justify-content-between mb-2"i f>rtp'
      });
    }
  };

  const textarea = document.getElementById('sql_query');
  textarea.addEventListener('input', () => {
    textarea.style.height = 'auto';
    textarea.style.height = (textarea.scrollHeight) + 'px';
  });
</script>
