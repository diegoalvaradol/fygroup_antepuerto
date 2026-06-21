<?php
require_once __DIR__ . '/../config/maintenance.php';

$now = new DateTime();
$start = new DateTime(MAINTENANCE_START);
$end = new DateTime(MAINTENANCE_END);

if (MAINTENANCE_MODE_FYGROUP && $now >= $start && $now <= $end) {
    require_once __DIR__ . '/../maintenance.php';

    exit;
}

if (isset($_SESSION['user'])) {
    header('Location: loginDataUser.php');

    exit();
}

require_once __DIR__ . '/../config/includes.php';

$footer = menu::footerSSL();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>Dev FYGroup | Login</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
</head>

<body class="login-dev">
    <canvas class="login-bg" id="canvas"></canvas>
    <div class="container login-wrapper d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-xl-4 col-lg-5 col-md-7">
            <div class="card login-card p-4">
                <div class="text-center mb-4">
                    <img src="../logos/logo-fygroup-circle-v1.png" class="logo-img mb-3">
                    <h4 class="font-weight-bold text-dark mb-1">Sistema Integral FYGroup</h4>
                    <small class="text-muted">Acceso Desarrolladores</small>
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

                    <div class="divider">
                        <span>Accesos Rápidos Login</span>
                    </div>

                    <div class="mt-3">
                        <a href="../myFY/login.php" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-ship"></i>
                            Acceso FYGroup
                        </a>
                    </div>

                    <div class="mt-3">
                        <a href="../myPortal/login.php" class="btn btn-outline-success btn-block">
                            <i class="fas fa-user"></i>
                            Acceso Portal Cliente
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <?php echo $footer; ?>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/fygroup.js"></script>
    <script src="../assets/js/sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

<script>
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
