<?php

declare(strict_types=1);

define('APP_MODE', 'DEV');
require_once __DIR__ . '/../config/status_mode.php';

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $myFyUrl = '/fygroup-antepuerto/myFY/login.php';
    $myPortalUrl = '/fygroup-antepuerto/myPortal/login.php';
} else {
    $myFyUrl = 'https://antepuerto.fygroup.cl/myFY/login.php';
    $myPortalUrl = 'https://portalcliente.fygroup.cl/myPortal/login.php';
}

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
    <title>Dev FYGroup | Login</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
</head>

<body class="login-dev">
    <div class="login-page">
        <!-- =====================================================
             PANEL IZQUIERDO
        ====================================================== -->
        <section class="login-visual">
            <canvas class="login-bg" id="canvas"></canvas>
            <div class="visual-overlay"></div>
            <div class="visual-content">
                <img src="../logos/new-logo-fygroup-bg-removed.png" class="visual-logo" alt="FYGroup">

                <div class="visual-line"></div>

                <h1>
                    Construimos<br>
                    <strong>soluciones.</strong>
                </h1>

                <p>
                    Entorno de desarrollo para la
                    administración y evolución del
                    Sistema Integral FYGroup.
                </p>

                <div class="visual-footer">
                    <span>
                        <i class="fas fa-shield-alt"></i>
                        Plataforma segura
                    </span>

                    <span>
                        <i class="fas fa-code"></i>
                        Entorno de Desarrollo
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
                    <img src="../logos/new-logo-fygroup-bg-removed.png" alt="FYGroup">
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
                        Accede a Entorno de Desarrollo
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
                            <i class="fas fa-shield-alt"></i>
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
                     ACCESOS RÁPIDOS
                ================================================== -->
                <div class="quick-access">
                    <div class="quick-access-title">
                        <span>Accesos rápidos</span>
                    </div>

                    <a href="<?= $myFyUrl ?>" class="quick-access-btn">
                        <span class="quick-icon">
                            <i class="fas fa-ship"></i>
                        </span>

                        <span>
                            <strong>FYGroup</strong>
                            <small>Sistema Integral</small>
                        </span>

                        <i class="fas fa-arrow-right"></i>
                    </a>

                    <a href="<?= $myPortalUrl ?>" class="quick-access-btn">
                        <span class="quick-icon">
                            <i class="fas fa-user"></i>
                        </span>

                        <span>
                            <strong>Portal Cliente</strong>
                            <small>Acceso clientes</small>
                        </span>

                        <i class="fas fa-arrow-right"></i>
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
                        Entorno de Desarrollo
                    </span>
                </footer>
            </div>
        </section>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

  var canvas = document.getElementById("canvas"),
  ctx = canvas.getContext('2d');

  // Ajustar el número de estrellas según el tamaño de la ventana
  var screenWidth = window.innerWidth;
  var x = 100; // Número de estrellas por defecto

  if (screenWidth < 768) {
    // Si la pantalla es menor a 768px (típico de un teléfono), reducimos los puntos
    x = 20; // Reducir el número de estrellas
  } else if (screenWidth < 480) {
    // Si es menor a 480px (teléfonos más pequeños)
    x = 15; // Aún menos estrellas
  }

  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;

  var stars = [],
    FPS = 60,
    mouse = {
      x: 0,
      y: 0
    };  // Ubicación del mouse

    // Añadir estrellas al array
  for (var i = 0; i < x; i++) {
    stars.push({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      radius: Math.random() * 1 + 1,
      vx: Math.floor(Math.random() * 50) - 25,
      vy: Math.floor(Math.random() * 50) - 25
    });
  }

    // Dibuja la escena
  function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.globalCompositeOperation = "lighter";

    for (var i = 0, x = stars.length; i < x; i++) {
      var s = stars[i];

      ctx.fillStyle = "#ff6f00";
      ctx.beginPath();
      ctx.arc(s.x, s.y, s.radius, 0, 2 * Math.PI);
      ctx.fill();
      ctx.fillStyle = 'black';
      ctx.stroke();
    }

    ctx.beginPath();
    for (var i = 0, x = stars.length; i < x; i++) {
      var starI = stars[i];
      ctx.moveTo(starI.x, starI.y);
      if (distance(mouse, starI) < 150) ctx.lineTo(mouse.x, mouse.y);
        for (var j = 0, x = stars.length; j < x; j++) {
          var starII = stars[j];
          if (distance(starI, starII) < 150) {
            ctx.lineTo(starII.x, starII.y);
          }
        }
    }
    ctx.lineWidth = 0.05;
    ctx.strokeStyle = 'white';
    ctx.stroke();
  }

  function distance(point1, point2) {
    var xs = 0;
    var ys = 0;

    xs = point2.x - point1.x;
    xs = xs * xs;

    ys = point2.y - point1.y;
    ys = ys * ys;

    return Math.sqrt(xs + ys);
  }

    // Actualizar la ubicación de las estrellas
  function update() {
    for (var i = 0, x = stars.length; i < x; i++) {
      var s = stars[i];

      s.x += s.vx / FPS;
      s.y += s.vy / FPS;

      if (s.x < 0 || s.x > canvas.width) s.vx = -s.vx;
      if (s.y < 0 || s.y > canvas.height) s.vy = -s.vy;
    }
  }

  canvas.addEventListener('mousemove', function (e) {
    mouse.x = e.clientX;
    mouse.y = e.clientY;
  });

  // Actualizar y dibujar
  function tick() {
    draw();
    update();
    requestAnimationFrame(tick);
  }

  tick();

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

    $.post('../controllers/loginDevController.php',
      $('#loginForm').serialize()
    )
    .done((res) => {
      res = res.trim();

      switch (res) {
        case 'OK':
          window.location.href = 'loginDataUser.php';
          break;

        case 'NOOK3':
          showError(
            'Tu usuario se encuentra <b>inhabilitada</b>.<br>Contacta al administrador.',
            'info'
          );
          break;

        case 'NOOK2':
          showError('Tu perfil no pertenece a una cuenta de Desarrollador.');
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
