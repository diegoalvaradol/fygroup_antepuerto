<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$db   = (new Database())->getConnection();
$user = new user($db);

$admin = $user->isAdmin($_SESSION["user"]["run"]);

/* Validar superadmin */
if (!$admin) {
  mostrarAccesoDenegado();
}

/* Función que ejecuta la consulta en el servidor */
function ejecutarQuery($db)
{
  $resultado = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['sql_query'])) {
    $sql = trim($_POST['sql_query']);
    try {
      $stmt = $db->prepare($sql);
      $stmt->execute();

      if (stripos($sql, 'SELECT') === 0) {
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultados) {
          $resultado .= "<table class='table table-bordered table-sm mt-3'><thead><tr>";
          foreach (array_keys($resultados[0]) as $col) {
            $resultado .= "<th>" . htmlspecialchars($col) . "</th>";
          }
          $resultado .= "</tr></thead><tbody>";
          foreach ($resultados as $fila) {
            $resultado .= "<tr>";
            foreach ($fila as $val) {
              $resultado .= "<td>" . htmlspecialchars($val) . "</td>";
            }
            $resultado .= "</tr>";
          }
          $resultado .= "</tbody></table>";
        } else {
          $resultado = "<div class='alert alert-warning mt-3'>Consulta ejecutada, sin resultados.</div>";
        }
      } else {
        $resultado = "<div class='alert alert-success mt-3'>Query ejecutada correctamente.</div>";
      }
    } catch (PDOException $e) {
      $resultado = "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
  }

  return $resultado;
}

$resultado = ejecutarQuery($db);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Dasboard" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>SSL | Consola SQL Admin</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
</head>

<body>
	<div id="wrapper">
			<!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">
					<!-- Main Content -->
					<div id="content">
							<!-- Begin Page Content -->
							<div class="container-fluid">
									<!-- Contenido -->
									<div class="text-center">
											<img src="../images/ssl-logo-azul.png">
											<div class="container">
													<h3>Ejecutar Consulta SQL</h3>
													<?=$resultado?>

													<form method="POST" class="mb-3">
															<textarea name="sql_query" class="form-control" rows="5" placeholder="Escribe aquí tu consulta SQL..." required></textarea>
															<button type="submit" class="btn btn-primary mt-2">Ejecutar</button>
													</form>
											</div>

											</br>

											<button type="button" class="btn btn-primary btn-sm" onclick="location.href='dashboard.php'">
													<i class="fas fa-arrow-left me-1"></i> Volver al Inicio
											</button>
									</div>
							</div>
							<!-- /.container-fluid -->
					</div>
					<!-- End of Main Content -->
			</div>
			<!-- End of Content Wrapper -->
  </div>
</body>
</html>

<script>
  window.onload = () => {
    const alert = document.querySelector('.alert');
    if (alert) {
      setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      }, 4000); /* Desaparece después de 4 segundos */
    }
  };
</script>
