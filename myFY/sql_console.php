<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$user   = new user();
$admin  = $user->isAdmin($_SESSION["user"]["run"]);
$footer = menu::footerSSL();

if (!$admin) {
  $usuario = $_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"] . ' (' . $_SESSION["user"]["run"] . ')';
  $pag     = basename(__FILE__);
  $url     = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
  mostrarAccesoDenegado($usuario, $pag, $url);
}

function ejecutarQuery($user)
{
  $resultado = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['sql_query'])) {
    $sql = trim($_POST['sql_query']);

    if (preg_match('/\b(DROP|DELETE|TRUNCATE)\b/i', $sql)) {
      return "<div class='alert alert-danger mt-3'>Error: Instrucción no permitida.</div>";
    }

    try {
      $stmt = $user->getDb()->prepare($sql);
      $stmt->execute();

      if (stripos($sql, 'SELECT') === 0) {
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultados) {
          $resultado .= "<div class='table-responsive'><table class='table table-bordered table-sm mt-3'><thead><tr>";
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
          $resultado .= "</tbody></table></div>";
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

$resultado = ejecutarQuery($user);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Vista Formulario de Registro de Nuevo Usuario" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/favicon-256x256.png"/>
    <title>FYGroup | SQL Administrador</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <style>
    textarea {
      resize: none;
      overflow: hidden;
    }
  </style>
</head>

<body>
  <div id="wrapper">
    <div id="content-wrapper" class="d-flex flex-column min-vh-100">
      <div id="content">
        <div class="container py-4">
          <div class="text-center mb-4">
            <img src="../images/logo-fygroup-v1_bg_removed.png" class="img-fluid" style="max-width:180px;">
          </div>

          <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
              <h4 class="text-center mb-3">Ejecutar Consulta SQL</h4>

              <?=$resultado?>

              <form method="POST" class="mb-4">
                <textarea name="sql_query" id="sql_query" class="form-control mb-2" rows="5" placeholder="Escribe aquí tu consulta SQL..." required></textarea>
                <div class="d-grid" style="justify-self: center;">
                  <button type="submit" class="btn btn-primary btn-user">
                    <i class="fas fa-solid fa-check-circle"></i> Ejecutar
                  </button>
                </div>
              </form>

              <div class="text-center mt-4">
                <a href="dashboard.php" class="btn btn-sm btn-primary">
                  <i class="fas fa-arrow-left"></i> Volver al Inicio
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?=$footer?>
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
  <script src="../assets/js/sb-admin-2.min.js"></script>

  <!-- Bootstrap JS (necesario para popover) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>

<script>
  /* Auto ocultar alertas */
  window.onload = () => {
    const alert = document.querySelector('.alert');
    if (alert) {
      setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      }, 4000);
    }
  };

  /* Expansión automática del textarea */
  const textarea = document.getElementById('sql_query');
  textarea.addEventListener('input', () => {
    textarea.style.height = 'auto';
    textarea.style.height = (textarea.scrollHeight) + 'px';
  });
</script>