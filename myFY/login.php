<?php

define('APP_MODE', 'FYGROUP');
require_once __DIR__ . '/../config/status_mode.php';

if (isset($_SESSION['user'])) {
    header('Location: loginDataUser.php');

    exit();
}

require_once __DIR__ . '/../config/includes.php';
?>

<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>FYGroup | Login</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
</head>

<body class="login-fygroup">
    <video autoplay muted loop playsinline id="bg-video">
        <source src="../images/fygroup_port.mov" type="video/mp4">
    </video>

    <div class="video-overlay"></div>
    <div class="container login-wrapper d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-xl-4 col-lg-5 col-md-7">
            <div class="card login-card p-4">
                <div class="text-center mb-4">
                    <img src="../logos/logo-fygroup-circle-v1.png" class="logo-img mb-3">
                    <h4 class="font-weight-bold text-dark mb-1">Sistema Integral FYGroup</h4>
                    <small class="text-muted">Acceso Personal</small>
                </div>

                <form id="loginForm">
                    <div class="form-group mb-3">
                        <label class="small text-muted">R.U.N</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control text-center" id="run" name="run" autocomplete="run" maxlength="12" placeholder="12.345.678-9" oninput="formatearRun(this)" onblur="validaRun(this.value)">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="small text-muted">Contraseña</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control text-center" id="password" name="password" autocomplete="current-password" placeholder="••••••••">
                        </div>
                    </div>

                    <button id="loadBtn" type="button" onclick="loadSession()"class="btn btn-primary btn-login btn-block">
                        <span id="loadBtnText"><i class="fas fa-right-to-bracket mr-2"></i> Iniciar Sesión</span>
                        <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </form>

                <div class="divider">
                    <span>Soporte</span>
                </div>

                <div class="mt-3">
                    <a href="https://wa.me/56923816700?text=Hola%20necesito%20ayuda" target="_blank" class="btn btn-outline-success btn-block">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Soporte por WhatsApp
                    </a>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        ¿Tienes problemas con tu cuenta?
                        <a href="mailto:soporte@fygroup.cl">Escríbenos</a>
                    </small>
                </div>
            </div>
        </div>
    </div>

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
  var formatearRun = function (inputRun) {
    let rut = inputRun.value.replace(/[^0-9kK]/g, '').toUpperCase();
    let cuerpo = rut.slice(0, -1);
    let dv = rut.slice(-1);
    let cuerpoFormateado = '';
    let i = 0;

    for (let j = cuerpo.length - 1; j >= 0; j--) {
      cuerpoFormateado = cuerpo[j] + cuerpoFormateado;
      i++;
      if (i % 3 === 0 && j !== 0) {
        cuerpoFormateado = '.' + cuerpoFormateado;
      }
    }

    inputRun.value = cuerpoFormateado + '-' + dv;
  }

  var validaRun = function (rut) {
    rut = rut.replace(/[^0-9kK]/g, '').toUpperCase();
    if (rut.length < 2) return false;
    const cuerpo = rut.slice(0, -1);
    let suma = 0, multiplo = 2;

    for (let i = cuerpo.length - 1; i >= 0; i--) {
      suma += parseInt(cuerpo[i]) * multiplo;
      multiplo = multiplo < 7 ? multiplo + 1 : 2;
    }
  }

  var loadSession = function () {
    const run      = $('#run').val().trim();
    const password = $('#password').val();
    const division = 'fy';

    const $btn     = $('#loadBtn');
    const $text    = $('#loadBtnText');
    const $spinner = $('#loadBtnSpinner');

    const toggleLoading = (on) => {
      $btn.prop('disabled', on);
      $text.toggleClass('d-none', on);
      $spinner.toggleClass('d-none', !on);
    };

    const showError = (html, icon = 'error') =>
      Swal.fire({ title: 'Oops...', html, icon }).then(() => toggleLoading(false));

    if (!run || !password) {
      Swal.fire({
        title: '¡Atención!',
        html: 'Debes ingresar R.U.N y contraseña.',
        icon: 'warning'
      });
      return;
    }

    toggleLoading(true);

    $.post('../controllers/loginController.php',
      $('#loginForm').serialize() + '&division=' + encodeURIComponent(division)
    )
    .done((res) => {
      res = res.trim();

      switch (res) {
        case 'OK':
          window.location.href = 'loginDataUser.php';
          break;

        case 'NOOK4':
          showError(
            'Tu usuario se emcuentra asociado a perfil de desarrollador.',
            'info'
          );
          break;

        case 'NOOK3':
          showError(
            'Tu usuario se encuentra <b>inhabilitada</b>.<br>Contacta al administrador.',
            'info'
          );
          break;

        case 'NOOK2':
          showError('Tu perfil no se encuentra asociado a FYGroup.');
          break;

        case 'NOOK':
          showError('R.U.N y/o contraseña inválidos.');
          break;

        default:
          showError(res);
      }
    })
    .fail(() => {
      showError('No fue posible conectar con el servidor.');
    });
  };
</script>
