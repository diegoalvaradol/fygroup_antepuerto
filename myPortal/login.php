
<?php
if (isset($_SESSION['user'])) {
  header("Location: dashboard.php");
  exit();
}
?>

<!-- HTML. -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Vista Inicio de Sesión" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>Portal - SSL | Login</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
    body {
      background-image: url("../images/coquimbo_port_background_4.jpg");
      background-size: cover;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
</style>

<body>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row justify-content-center w-100">
      <div class="col-xl-6 col-lg-8 col-md-10">
        <div class="card glass-card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-12 text-center pt-4">
                <img src="../images/ssl-logo-azul.png" alt="SSL Chile" class="img-fluid mb-3" style="max-height: 120px;">
                <div>
                  <small style="font-size:xx-large;color:forestgreen;">Sistema Integral SSL.</small><br>
                  <small style="font-size:x-large;color:forestgreen;">Portal Cliente.</small>
                </div>
              </div>

              <div class="col-12">
                <div class="px-4 pb-5 pt-3">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">¡Bienvenido!</h1>
                  </div>

                  <form id="loginForm">
                    <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                      <select class="form-control select2" id="division" name="division" style="margin-left:25%;margin-right:25%;">
                        <option value="-" selected>Seleccione una División...</option>
                        <option value="terminal">Terminal</option>
                        <option value="shipper">Naviera</option>
                      </select>
                    </div>

                    <div class="form-group" style="display:flex;align-items:center;justify-content:center;gap:10px;">
                      <input type="text" class="form-control form-control-user" id="run" name="run" oninput="formatearRut(this)" maxlength="12" onblur="validaRut(this.value)" placeholder="12.345.678-9" style="text-align:center;margin-left:5%;">
                      <span id="info-run"></span>
                    </div>

                    <div class="form-group" style="display:flex;align-items:center;justify-content:center;gap:10px;">
                      <input type="password" class="form-control form-control-user" id="password" name="password" oninput="validaPassword(this.value)" placeholder="Contraseña" style="text-align:center;margin-left:5%;">
                      <span id="info-password"></span>
                    </div>

                    <button id="loadBtn" type="button" class="btn btn-success btn-user btn-block" onclick="loadSession()">
                      <span id="loadBtnText"><i class="fas fa-solid fa-right-to-bracket"></i> Iniciar Sesión</span>
                      <span id="loadBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                  </form>
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
</html>

<!-- JAVASCRIPT -->
<script>
  var formatearRut = function (inputRun) {
    let rut = inputRun.value.replace(/[^0-9kK]/g, '').toUpperCase();

    /* Separar cuerpo y DV */
    let cuerpo = rut.slice(0, -1);
    let dv = rut.slice(-1);

    /* Agregar puntos cada 3 dígitos desde la derecha */
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
    if(password !== ''){
      $('#info-password').html('<i class="fas fa-solid fa-check-circle fa-lg" style="color: #63E6BE;"></i>');
    }else{
      $('#info-password').html('<i class="fa-solid fa-triangle-exclamation fa-lg" style="color: #FFD43B;"></i>');
    }
  }

  var validaRut = function(rut) {
    rut = rut.replace(/[^0-9kK]/g, '').toUpperCase();

    if (rut.length < 2) return false;
    const cuerpo = rut.slice(0, -1);
    const dvIngresado = rut.slice(-1);

    let suma = 0;
    let multiplo = 2;

    /* Recorrer el cuerpo del RUT de derecha a izquierda */
    for (let i = cuerpo.length - 1; i >= 0; i--) {
      suma += parseInt(cuerpo[i]) * multiplo;
      multiplo = multiplo < 7 ? multiplo + 1 : 2;
    }

    const dvEsperado = 11 - (suma % 11);
    let dvCalculado = '';

    if (dvEsperado === 11) dvCalculado = '0';
    else if (dvEsperado === 10) dvCalculado = 'K';
    else dvCalculado = dvEsperado.toString();

    if(dvCalculado === dvIngresado){
      $('#info-run').html('<i class="fas fa-solid fa-check-circle fa-lg" style="color: #63E6BE;"></i>');
    }else{
      $('#info-run').html('<i class="fas fa-solid fa-circle-xmark fa-lg" style="color: #EA5353;"></i>');
    }
  }

  var loadSession = function () {
    const run      = $('#run').val().trim();
    const password = $('#password').val();
    const division = $('#division').val();

    const $btn     = $('#loadBtn');
    const $text    = $('#loadBtnText');
    const $spinner = $('#loadBtnSpinner');

    const toggleLoading = (on) => {
      $btn.prop('disabled', on);
      $text.toggleClass('d-none', on);
      $spinner.toggleClass('d-none', !on);
    };

    const showMsg = (opts) =>
      Swal.fire(opts).then(() => toggleLoading(false));

    if (!run || !password) {
      Swal.fire({
        title: 'Campos incompletos',
        text: 'Debes ingresar RUN y contraseña.',
        icon: 'warning'
      });
      return;
    }

    if (division === '-') {
      Swal.fire({
        title: 'Campos incompletos',
        text: 'Debes seleccionar una división.',
        icon: 'warning'
      });
      return;
    }

    toggleLoading(true);

    $.post(
      '../controllers/loginController.php',
      $('#loginForm').serialize() + '&division=' + encodeURIComponent(division)
    )
    .done((res) => {
      res = res.trim();

      switch (res) {
        case 'OK':
          Swal.fire({
            title: '¡Bienvenido!',
            html: 'Cargando preferencias 🚀',
            icon: 'info',
            timer: 3000,
            showConfirmButton: false,
            allowOutsideClick: false
          }).then(() => location.href = 'dashboard.php');
          break;

        case 'NOOK3':
          showMsg({
            title: 'Oops...',
            html: 'Tu usuario se encuentra <b>inhabilitada</b>.<br>Contacta al administrador.',
            icon: 'info'
          });
          break;

        case 'NOOK2':
          showMsg({
            title: 'Oops...',
            html: 'Tu perfil no se encuentra asociado a Portal Cliente.',
            icon: 'info'
          });
          break;

        case 'NOOK':
          showMsg({
            title: 'Oops...',
            html: 'RUN y/o contraseña inválidos.',
            icon: 'error'
          });
          break;

        default:
          showMsg({
            title: 'Error',
            text: res,
            icon: 'error'
          });
      }
    })
    .fail(() => {
      showMsg({
        title: 'Error',
        text: 'No fue posible conectar con el servidor.',
        icon: 'error'
      });
    });
  };
</script>