<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/includes.php';

$user = new user();
$admin = $user->isAdmin($_SESSION['user']['run']);
$footer = menu::footerSSL();

if (!$admin) {
    $usuario = $_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name'] . ' (' . $_SESSION['user']['run'] . ')';
    $pag = basename(__FILE__);
    $url = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    mostrarAccesoDenegado($usuario, $pag, $url);
}

function ejecutarQuery($user)
{
    $resultado = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['sql_query'])) {
        $sql = trim($_POST['sql_query']);

        if (!preg_match('/^\s*(SELECT|UPDATE|DELETE|DROP)\b/i', $sql)) {
            return "
        <script>
          Swal.fire({icon:'error', title:'Instrucción no permitida', text:'Solo SELECT, UPDATE, DELETE y DROP son permitidas.', timer:4000, showConfirmButton:false});
        </script>
      ";
        }

        // SweetAlert confirmación para DELETE y DROP
        if (preg_match('/^\s*(DELETE|DROP)\b/i', $sql)) {
            echo "
        <script>
          let ejecutar = confirm('Esta operación puede modificar o eliminar datos. ¿Deseas continuar?');
          if(!ejecutar){ window.history.back(); }
        </script>
      ";
        }

        try {
            $stmt = $user->getDb()->prepare($sql);
            $stmt->execute();

            if (stripos($sql, 'SELECT') === 0) {
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($resultados) {
                    $resultado .= '<div class="table-responsive mt-3 mb-3"><table id="sqlResults" class="table table-bordered table-striped table-sm"><thead><tr>';

                    foreach (array_keys($resultados[0]) as $col) {
                        $resultado .= '<th>' . htmlspecialchars($col) . '</th>';
                    }

                    $resultado .= '</tr></thead><tbody>';

                    foreach ($resultados as $fila) {
                        $resultado .= '<tr>';

                        foreach ($fila as $val) {
                            $resultado .= '<td>' . htmlspecialchars($val ?? '') . '</td>';
                        }

                        $resultado .= '</tr>';
                    }

                    $resultado .= '</tbody></table></div>';
                } else {
                    $resultado .= "
              <script>
                Swal.fire({icon:'info', title:'Consulta ejecutada', text:'No se encontraron resultados.', timer:3500, showConfirmButton:false});
              </script>
            ";
                }
            } else {
                $resultado .= "
            <script>
              Swal.fire({icon:'success', title:'Query ejecutada correctamente', timer:3000, showConfirmButton:false});
            </script>
          ";
            }
        } catch (PDOException $e) {
            $resultado .= "
          <script>
            Swal.fire({icon:'error', title:'Error al ejecutar', text:'" . htmlspecialchars($e->getMessage()) . "', showConfirmButton:true});
          </script>
        ";
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
  <title>FYGroup | SQL Administrador</title>
  <link rel="icon" type="image/png" href="../favicon/favicon-256x256.png"/>

  <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>

  <style>
  textarea {
    resize: none; overflow: hidden; transition: height 0.2s; margin-bottom: 1rem;
  }

  .btn-submit-wrapper {
    display:flex; justify-content:center; margin-bottom:2rem;
  }

  .dataTables_wrapper .dataTables_info {
    float:left; margin-bottom:0.5rem;
  }

  .dataTables_wrapper .dataTables_paginate {
    display:flex !important; justify-content:center; margin-top:1rem; float:none !important;
  }

  .dataTables_wrapper .dataTables_filter {
    float:right; margin-bottom:0.5rem; text-align:right;
  }

  .table-responsive {
    margin-bottom:1rem;
  }
  </style>

</head>

<body>
<div id="wrapper">
  <div id="content-wrapper" class="d-flex flex-column min-vh-100">
    <div id="content">
      <div class="container py-4">
        <div class="text-center mb-4">
          <img src="../images/logo-fygroup-bg-removed.png" class="img-fluid" style="max-width:180px;">
        </div>
        <div class="row justify-content-center">
          <div class="col-12 col-md-10 col-lg-8">
            <h4 class="text-center mb-3">Ejecutar Consulta SQL</h4>

            <?=$resultado?>

            <form method="POST" class="mb-4">
              <textarea name="sql_query" id="sql_query" class="form-control" rows="5" placeholder="Escribe aquí tu consulta SQL..." required></textarea>
              <div class="btn-submit-wrapper">
                <button type="submit" class="btn btn-primary btn-user px-4">
                  <i class="fas fa-check-circle"></i> Ejecutar
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/js/sb-admin-2.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
  /* Conteo regresivo para cierre de sesion */
  let inactivityTime = function () {
    let time;
    let warningTimeout = 30 * 60 * 1000; /* Minutos a convenir */
    let countdownTime = 30; /* 30 segundos para responder */

    function startTimer() {
      window.addEventListener('mousemove', resetTimer, false);
      window.addEventListener('keypress', resetTimer, false);
      window.addEventListener('click', resetTimer, false);
      window.addEventListener('scroll', resetTimer, false);
      resetTimer();
    }

    function logoutCountdown() {
      let timerInterval;
      Swal.fire({
        title: "¿Sigues ahí?",
        html: `Serás desconectado en <b></b> segundos por inactividad.`,
        icon: "warning",
        timer: countdownTime * 1000,
        timerProgressBar: true,
        showCancelButton: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#d33',
        confirmButtonText: "¡Sigo aquí!",
        cancelButtonText: "Cerrar sesión",
        didOpen: () => {
          const b = Swal.getHtmlContainer().querySelector("b");
          timerInterval = setInterval(() => {
            b.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
          }, 1000);
        },
        willClose: () => {
          clearInterval(timerInterval);
        }
      }).then((result) => {
        if (result.isConfirmed) {
          resetTimer(); /* Usuario activo, reiniciar contador */
        } else {
          window.location = 'login.php?msg=sesion_expirada';
        }
      });
    }

    function resetTimer() {
      clearTimeout(time);
      time = setTimeout(logoutCountdown, warningTimeout);
    }

    startTimer();
  };

  window.onload = function () {
    inactivityTime();
  };

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
</body>
</html>
