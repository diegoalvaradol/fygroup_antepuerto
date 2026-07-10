<?php

define('APP_MODE', 'PORTALCLIENTE');
require_once __DIR__ . '/../config/status_mode.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');

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
    <title>Portal FYGroup | Login</title>

    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/app.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
</head>

<body class="login-portal">
    <div class="container login-wrapper d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-xl-4 col-lg-5 col-md-7">
            <div class="card login-card p-4">
                <div class="text-center mb-4">
                    <img src="../logos/logo-fygroup-circle-v1.png" class="logo-img mb-3">
                    <h4 class="font-weight-bold text-dark mb-1">Sistema Integral FYGroup</h4>
                    <small class="text-muted">Portal Cliente</small>
                </div>

                <form id="loginForm">
                    <div class="form-group mb-3">
                        <label class="small text-muted">División</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                            </div>
                            <select class="form-control text-center" id="division" name="division">
                                <option value="-" selected>Seleccione...</option>
                                <option value="terminal">Terminal</option>
                                <option value="shipper">Naviera</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small text-muted">R.U.N</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control text-center" id="run" name="run" maxlength="12" placeholder="12.345.678-9" oninput="formatearRut(this)" onblur="validaRut(this.value)">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="small text-muted">Contraseña</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control text-center" id="password" name="password" placeholder="••••••••">
                        </div>
                    </div>

                    <button id="loadBtn" type="button" onclick="loadSession()" class="btn btn-success btn-login btn-block">
                        <span id="loadBtnText"><i class="fas fa-right-to-bracket mr-2"></i> Iniciar Sesión</span>
                        <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/fygroup.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    if (i % 3 === 0 && j !== 0) cuerpoFormateado = '.' + cuerpoFormateado;
  }

  inputRun.value = cuerpoFormateado + '-' + dv;
};

var validaRut = function(rut) {
  rut = rut.replace(/[^0-9kK]/g, '').toUpperCase();
  if (rut.length < 2) return;
  const cuerpo = rut.slice(0, -1);
  let suma = 0, multiplo = 2;

  for (let i = cuerpo.length - 1; i >= 0; i--) {
    suma += parseInt(cuerpo[i]) * multiplo;
    multiplo = multiplo < 7 ? multiplo + 1 : 2;
  }
};

var loadSession = function () {
  const run      = $('#run').val().trim();
  const password = $('#password').val();
  const division = $('#division').val();

  if (!run || !password || division === '-') {
    Swal.fire({
      title: 'Campos incompletos',
      text: 'Debes completar todos los campos.',
      icon: 'warning'
    });
    return;
  }

  const $btn     = $('#loadBtn');
  const $text    = $('#loadBtnText');
  const $spinner = $('#loadBtnSpinner');

  $btn.prop('disabled', true);
  $text.addClass('d-none');
  $spinner.removeClass('d-none');

  $.post('../controllers/loginController.php',
    $('#loginForm').serialize()
  )
  .done((res) => {
    res = res.trim();

    if (res === 'OK') {
      window.location.href = 'loginDataUser.php';
      return;
    }

    Swal.fire({
      title: 'Error',
      html: res,
      icon: 'error'
    }).then(() => {
      $btn.prop('disabled', false);
      $text.removeClass('d-none');
      $spinner.addClass('d-none');
    });
  })
  .fail(() => {
    Swal.fire('Error','No fue posible conectar con el servidor.','error');
  });
};
</script>
