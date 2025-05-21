<!-- HTML -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>Portal - SSL | Ingreso Nueva Contraseña</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
          background-image: url("../img/coquimbo_port_background.jpg");
          background-size: cover;
          font-family: Arial, sans-serif;
          padding: 40px;
        }
    </style>
</head>

<body>
<div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100 justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-0">
                        <div class="row">
                            <!-- Columna de imagen y textos -->
                            <div class="col-lg-5 d-none d-lg-flex flex-column align-items-center justify-content-center bg-light p-4 text-center">
                                <img src="../img/ssl-logo-azul.png" alt="SSL Chile" class="img-fluid mb-3" style="max-height: 120px;">
                                <small class="text-success" style="font-size:x-large">Sistema Integral SSL.</small>
                                <br>
                                <small class="text-success" style="font-size:large;">Portal Cliente.</small>
                            </div>

                            <!-- Columna de formulario -->
                            <div class="col-lg-7 col-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Ingreso Nueva Contraseña</h1>
                                        <p class="mb-4">Favor ingresa tu nueva contraseña.</p>
                                    </div>
                                    <form id="resetPasswordForm">
                                        <input type="hidden" name="token" value="<?=htmlspecialchars($_GET['token'])?>">
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user mb-3" id="password" name="password" placeholder="Nueva contraseña" required>
                                            <input type="password" class="form-control form-control-user" id="password2" name="confirm_password" placeholder="Confirmar contraseña" required>
                                        </div>
                                        <hr>
                                        <button type="button" id="resetpassword" class="btn btn-success btn-user btn-block" onclick="resetPassword()">
                                            <i class="fas fa-check-circle"></i> Restablecer
                                        </button>
                                    </form>
                                </div>
                            </div> <!-- col formulario -->
                        </div> <!-- row -->
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div> <!-- col -->
        </div> <!-- row -->
    </div> <!-- container -->

  <!-- JS scripts -->
  <script src="../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../assets/js/sb-admin-2.min.js"></script>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
var resetPassword = function() {
  const password = $("#password").val();
  const password2 = $("#password2").val();
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
  let hasError = false;

  if (!regex.test(password)) {
    Swal.fire({
      title: 'Oops...',
      text: 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.',
      icon: 'error',
      cancelButtonColor: '#d33'
    });

    hasError = true;
  }

  if (password !== password2) {
    Swal.fire({
      title: 'Oops...',
      text: 'Las contraseñas no coinciden, favor intenta nuevamente.',
      icon: 'error',
      cancelButtonColor: '#d33'
    });

    hasError = true;
  }

  if(!hasError){
    $.ajax({
      url: '../controllers/resetHandler.php',
      data: $('#resetPasswordForm').serialize(),
      type: 'POST',
    }).done(function(x) {
      if(x == 'OK'){
        Swal.fire({
          title: '¡Éxito!',
          text: 'Contraseña reestablecida con éxito.',
          icon: 'success',
          confirmButtonColor: '#4CAF50'
        }).then((result) => {
          window.location = 'login.php';
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: 'Token inválido o expirado.',
          icon: 'error',
          cancelButtonColor: '#d33',
        });
      }
    });
  }

}
</script>
