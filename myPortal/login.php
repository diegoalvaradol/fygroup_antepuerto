
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
    background-image: url("../img/coquimbo_port_background.jpg");
    background-size: cover;
    }
</style>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5 mx-auto" style="max-width: 500px;">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12 text-center p-4">
                                <img src="../img/ssl-logo-azul.png" alt="SSL Chile" class="img-fluid mb-3" style="max-height: 120px;">
                                <small class="text-success" style="font-size: xx-large;">Sistema Integral SSL.</small>
                                <br>
                                <small class="text-success" style="font-size: x-large;">Portal Cliente.</small>
                            </div>
                            <div class="col-lg-12">
                                <div class="px-5 pb-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">¡Bienvenido!</h1>
                                    </div>
                                    <form id="loginForm">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="run" name="run" oninput="formatearRut(this)" maxlength="12" onblur="validaRut(this.value)" placeholder="12.345.678-9">
                                            <small id="info-run" class="text-danger"></small>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Contraseña">
                                        </div>
                                        <button type="button" class="btn btn-success btn-user btn-block" onclick="loadSession()">
                                            <i class="fas fa-right-to-bracket"></i> Iniciar Sesión
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot_password.php" style="font-size: medium;">¿Olvidaste la contraseña?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/sb-admin-2.min.js"></script>
</body>

</html>

<!-- JAVASCRIPT -->
<script>
  function formatearRut(inputRun) {
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
      $('#info-run').html('¡Run valido!').addClass('text-success');
    }else{
      $('#info-run').html('¡Run invalido!').addClass('text-danger');
    }
  }

  var loadSession = function() {
    const run = $('#run').val();
    const password = $('#password').val();
    var division = 'portal'; /* Division portal cliente */

    if (!run || !password) {
      Swal.fire({
        title: 'Campos incompletos',
        text: 'Por favor, ingresa un RUN y una contraseña.',
        icon: 'warning',
        confirmButtonText: 'Aceptar'
      });

      return;
    }

    $.ajax({
      url: '../controllers/loginController.php',
      data: $('#loginForm').serialize() + '&division=' + encodeURIComponent(division),
      type: "POST",
    }).done(function(x) {
      if(x == 'OK'){
        setTimeout(function() {
          window.location = "dashboard.php";
        }, 3000);
      }else if(x == 'NOOK'){
        Swal.fire({
          title: 'Oops...',
          text: 'El correo electrónico y/o contraseña ingresados son invalidos, favor reintenta nuevamente.',
          icon: 'error',
          cancelButtonColor: '#d33',
        });
      }else if(x == 'NOOK2'){
        Swal.fire({
          title: 'Oops...',
          text: 'Tu perfil no se encuentra asociado a Portal Cliente, por favor contacta al administrador.',
          icon: 'info',
          cancelButtonColor: '#d33',
        });
      }else{
        Swal.fire({
          title: 'Oops...',
          text: x,
          icon: 'error',
          cancelButtonColor: '#d33',
        });
      }
    });
  }
</script>
