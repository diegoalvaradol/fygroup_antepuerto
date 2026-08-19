<?php

declare(strict_types=1);

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
    <link href="../favicon/fygroup.ico" rel="icon">
    <link href="../favicon/fygroup-256x256.png" rel="apple-touch-icon">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <title>FYGroup | Login</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
</head>

<body class="login-fygroup">
    <div class="login-page">
        <!-- =====================================================
             PANEL IZQUIERDO
        ====================================================== -->
        <section class="login-visual">
            <video autoplay muted loop playsinline>
                <source src="../images/fygroup_port.mov" type="video/mp4">
            </video>

            <div class="visual-overlay"></div>
            <div class="visual-content">
                <img src="../logos/new-logo-fygroup-bg-removed.png" class="visual-logo" alt="FYGroup">

                <div class="visual-line"></div>

                <h1>
                    Conectamos<br>
                    <strong>operaciones.</strong>
                </h1>

                <p>
                    Gestión integral para logística,
                    transporte y operaciones portuarias.
                </p>

                <div class="visual-footer">
                    <span>
                        <i class="fas fa-shield-alt"></i>
                        Plataforma segura
                    </span>

                    <span>
                        <i class="fas fa-circle"></i>
                        Sistema Integral
                    </span>
                </div>
            </div>
        </section>

        <!-- =====================================================
             PANEL DERECHO
        ====================================================== -->
        <section class="login-panel">
            <div class="login-content">
                <!-- Logo mobile -->
                <div class="mobile-logo">
                    <img src="../logos/new-logo-fygroup-bg-removed.png"alt="FYGroup">
                </div>

                <!-- Header -->
                <div class="login-heading">
                    <span class="welcome">
                        BIENVENIDO
                    </span>

                    <h2>
                        Iniciar sesión
                    </h2>

                    <p>
                        Accede a Sistema Integral FYGroup
                    </p>
                </div>

                <!-- =================================================
                     FORMULARIO
                ================================================== -->
                <form id="loginForm">
                    <!-- R.U.N -->
                    <div class="field">
                        <label for="run">
                            R.U.N
                        </label>

                        <div class="field-input">
                            <i class="fas fa-id-card"></i>
                            <input type="text" id="run" name="run" autocomplete="username" maxlength="12" placeholder="12.345.678-9" oninput="formatearRun(this)" onblur="validaRun(this.value)">
                        </div>
                    </div>

                    <!-- CONTRASEÑA -->
                    <div class="field">
                        <label for="password">
                            Contraseña
                        </label>

                        <div class="field-input">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" autocomplete="current-password" placeholder="Ingresa tu contraseña">
                            <button type="button" class="show-password" onclick="togglePassword()" tabindex="-1">
                                <i id="passwordIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- OPCIONES -->
                    <div class="login-options">
                        <span>
                            <i class="fas fa-lock"></i>
                            Acceso protegido
                        </span>

                        <a href="mailto:soporte@fygroup.cl">
                            ¿Problemas para ingresar?
                        </a>
                    </div>

                    <!-- LOGIN -->
                    <button id="loadBtn" type="button" onclick="loadSession()" class="login-button">
                        <span id="loadBtnText">
                            Iniciar Sesión
                            <i class="fas fa-arrow-right"></i>
                        </span>

                        <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </form>

                <!-- =================================================
                     SOPORTE
                ================================================== -->
                <div class="support">
                    <div class="support-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>

                    <div class="support-text">
                        <strong>
                            ¿Necesitas asistencia?
                        </strong>

                        <span>
                            Nuestro equipo está disponible para ayudarte.
                        </span>
                    </div>

                    <a href="https://wa.me/56923816700?text=Hola%20necesito%20ayuda" target="_blank"rel="noopener noreferrer">
                        Contactar
                    </a>
                </div>

                <!-- =================================================
                     FOOTER
                ================================================== -->
                <footer class="login-footer">
                    <span>
                        © <?= date('Y') ?> FYGroup
                    </span>

                    <span>•</span>

                    <span>
                        Sistema Integral
                    </span>
                </footer>
            </div>
        </section>
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
  function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('passwordIcon');

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }

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
