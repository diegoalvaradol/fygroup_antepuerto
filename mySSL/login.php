<?php
if (isset($_SESSION['user'])) {
  header("Location: dashboard.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SSL | Login</title>

  <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
  <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
  <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
  .glass-card {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.3);
  }

  body {
    background-image: url("../images/coquimbo_port_background_3.jpg");
    background-size: cover;
    background-position: center;
  }
  @media (max-width: 576px) {
    .glass-card {
      margin: 1rem;
    }
    .text-primary {
      font-size: medium !important;
    }
  }
</style>

<body>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row justify-content-center w-100">
      <div class="col-xl-6 col-lg-8 col-md-10">
        <div class="card o-hidden border-0 shadow-lg my-5 glass-card">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-12 text-center pt-4">
                <img src="../images/ssl-logo-azul.png" alt="SSL Chile" class="img-fluid" style="max-height:120px;">
                <div>
                  <small class="text-primary" style="font-size:xx-large;">Sistema Integral SSL.</small><br>
                  <small class="text-primary" style="font-size:x-large;">Personal SSL.</small>
                </div>
              </div>

              <div class="col-12">
                <div class="px-4 pb-5 pt-3">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">¡Bienvenido!</h1>
                  </div>

                  <form id="loginForm">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-append" style="width: 40px;">
                          <span class="input-group-text bg-white border-left-0"><i class="faw fa-solid fa-id-card-clip"></i></span>
                        </div>
                        <input type="text" class="form-control text-center" id="run" name="run" oninput="formatearRut(this)" maxlength="12" onblur="validaRut(this.value)" placeholder="12.345.678-9">
                        <div class="input-group-append">
                          <span class="input-group-text bg-white border-left-0" id="info-run"></span>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-append">
                          <span class="input-group-text bg-white border-left-0"><i class="faw fa-solid fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control text-center" id="password" name="password" oninput="validaPassword(this.value)" placeholder="Contraseña">
                        <div class="input-group-append">
                          <span class="input-group-text bg-white border-left-0" id="info-password"></span>
                        </div>
                      </div>
                    </div>

                    <button id="loadBtn" type="button" class="btn btn-primary btn-user btn-block" onclick="loadSession()">
                      <span id="loadBtnText"><i class="fas fa-right-to-bracket"></i> Iniciar Sesión</span>
                      <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                  </form>

                  <hr>

                  <div class="text-center mt-3">
                    <a href="forgot_password.php" class="btn btn-outline-warning btn-sm m-1">
                      ¿Olvidaste la contraseña?
                    </a>
                    <a href="register.php" class="btn btn-outline-success btn-sm m-1">
                      ¿No tienes una cuenta? ¡Crea una!
                    </a>
                  </div>
                </div>
              </div>
            </div> <!-- End row -->
          </div> <!-- End card-body -->
        </div> <!-- End card -->
      </div> <!-- End col -->
    </div> <!-- End row -->
  </div> <!-- End container -->

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

  var validaPassword = function (password){
    $('#info-password').html(password !== ''
      ? '<i class="fas fa-check-circle text-success"></i>'
      : '<i class="fa-solid fa-triangle-exclamation text-warning"></i>');
  }

  var validaRut = function (rut) {
    rut = rut.replace(/[^0-9kK]/g, '').toUpperCase();
    if (rut.length < 2) return false;
    const cuerpo = rut.slice(0, -1);
    const dvIngresado = rut.slice(-1);
    let suma = 0, multiplo = 2;
    for (let i = cuerpo.length - 1; i >= 0; i--) {
      suma += parseInt(cuerpo[i]) * multiplo;
      multiplo = multiplo < 7 ? multiplo + 1 : 2;
    }
    const dvEsperado = 11 - (suma % 11);
    let dvCalculado = (dvEsperado === 11) ? '0' : (dvEsperado === 10 ? 'K' : dvEsperado.toString());

    $('#info-run').html(dvCalculado === dvIngresado
      ? '<i class="fas fa-check-circle text-success"></i>'
      : '<i class="fas fa-circle-xmark text-danger"></i>');
  }

  var loadSession = function () {
    const run = $('#run').val();
    const password = $('#password').val();
    const division = 'ssl';
    const btn = $('#loadBtn');
    const text = $('#loadBtnText');
    const spinner = $('#loadBtnSpinner');

    if (!run || !password) {
      Swal.fire({
        title: 'Campos incompletos',
        text: 'Por favor, ingresa un RUN y una contraseña.',
        icon: 'warning',
        confirmButtonText: 'Aceptar'
      });
      return;
    }

    text.addClass('d-none');
    spinner.removeClass('d-none');
    btn.prop('disabled', true);

    $.ajax({
      url: '../controllers/loginController.php',
      data: $('#loginForm').serialize() + '&division=' + encodeURIComponent(division),
      type: "POST",
    }).done(function(x) {
      if (x == 'OK') {
        Swal.fire({
          title: '¡Bienvenido!',
          html: 'Estamos cargando las preferencias de tu cuenta 🚀 </br> Por favor se paciente.',
          icon: 'info',
          timer: 3000,
          showConfirmButton: false,
          allowOutsideClick: false
        }).then(() => {
          window.location.href = "dashboard.php";
        });
      } else {
        let msg = (x == 'NOOK') ? 'El run y/o contraseña ingresados son inválidos.' :
                  (x == 'NOOK2') ? 'Tu perfil no se encuentra asociado a SSL.' : x;
        Swal.fire({ title: 'Oops...', html: msg, icon: 'error' }).then(() => {
          text.removeClass('d-none');
          spinner.addClass('d-none');
          btn.prop('disabled', false);
        });
      }
    });
  }
</script>
