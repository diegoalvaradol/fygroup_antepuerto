<?php
if (isset($_SESSION['user'])) {
    header('Location: loginDataUser.php');

    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FYGroup | Login</title>

  <link rel="icon" type="image/png" href="../favicon/favicon-256x256.png"/>
  <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
  body {
    background: url("../images/coquimbo_port_background_3.jpg") no-repeat center center fixed;
    background-size: cover;
    position: relative;
  }

  body::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.55);
  }

  .login-wrapper {
    position: relative;
    z-index: 2;
  }

  .login-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0,0,0,.25);
  }

  .login-card input {
    border-radius: 12px;
    height: 48px;
  }

  .login-card .input-group-text {
    background: #f8f9fc;
    border-radius: 12px 0 0 12px;
  }

  .btn-login {
    height: 48px;
    border-radius: 12px;
    font-weight: 600;
    letter-spacing: .5px;
  }

  .logo-img {
    max-height: 90px;
  }

  @media (max-width: 576px) {
    .login-card {
      margin: 1rem;
      padding: 1rem;
    }
  }
</style>

<body>
  <div class="container login-wrapper d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-xl-4 col-lg-5 col-md-7">
      <div class="card login-card p-4">
        <div class="text-center mb-4">
          <img src="../images/logo-fygroup-bg-removed.png" class="logo-img mb-3">
          <h4 class="font-weight-bold text-dark mb-1">Sistema Integral FYGroup</h4>
          <small class="text-muted">Acceso Personal</small>
        </div>

        <form id="loginForm">
          <div class="form-group mb-3">
            <label class="small text-muted">RUN</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input type="text" class="form-control text-center" id="run" name="run" autocomplete="run" maxlength="12" placeholder="12.345.678-9" oninput="formatearRut(this)" onblur="validaRut(this.value)">
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
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>

<script>
  var formatearRut = function (inputRun) {
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

  var validaRut = function (rut) {
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
        html: 'Debes ingresar RUN y contraseña.',
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
          showError('RUN y/o contraseña inválidos.');
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
