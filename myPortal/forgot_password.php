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
    <title>Portal - SSL | Recuperar Contraseña</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-image: url("../img/coquimbo_port_background.jpg");
            background-size: cover;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-register-image text-center p-5" style="align-content:space-around">
                                <img src="../img/ssl-logo-azul.png" alt="FY Chile" class="img-fluid mb-3">

                                <div class="text-center">
                                    <small class="text-success" style="font-size:x-large">Sistema Integral SSL.</small>
                                    <br>
                                    <small class="text-success" style="font-size:large; text-align: center; margin: auto;">Portal Cliente.</small>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">¿Olvidaste tu contraseña?</h1>
                                        <p class="mb-4">Lo entendemos, a veces pasan cosas. Solo introduce tu correo electrónico a continuación y te enviaremos un enlace para restablecer tu contraseña.</p>
                                    </div>
                                    <form id="resetForm">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="email" name="email" aria-describedby="emailHelp" onblur="validaEmail(this.value)" placeholder="Correo Electrónico">
                                        </div>

                                        <button type="button" id="resetpassword" class="btn btn-success btn-user btn-block" onclick="resetPassword()" disabled><i class='fas fa-solid fa-check-circle'></i> Restablecer Contraseña</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="login.php" style="font-size: medium;">¿Tienes cuenta? Inicia Sesión!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<!-- JAVASCRIPT -->
<script>
    var resetPassword = function() {
        var division = 'portal'; /* Division portal cliente */

        $.ajax({
            url: '../controllers/passwordResetController.php',
            data: $('#resetForm').serialize() + '&division=' + encodeURIComponent(division),
            type: 'POST',
        }).done(function(x) {
            console.log(x);

            if(x == 'OK'){
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Correo enviado correctamente!',
                    icon: 'success',
                    confirmButtonColor: '#4CAF50'
                }).then((result) => {
                    window.location = 'forgot_password.php';
                });
            }else if(x == 'NOOK2') {
                Swal.fire({
                    title: 'Oops...',
                    text: 'Se ha producido un error.',
                    icon: 'error',
                    cancelButtonColor: '#d33',
                });
            }else if(x == 'NOOK'){
                Swal.fire({
                    title: 'Oops...',
                    text: 'Tu correo no esta asoaicdo a una cuenta de Portal Cliente.',
                    icon: 'error',
                    cancelButtonColor: '#d33',
                });
            }
        });
    }

    var validaEmail = function(email) {
        $('#resetpassword').prop('disabled', true);

        if (email == '') {
            Swal.fire({
                title: 'Oops...',
                text: 'Debe ingresar una dirección de correo electrónico para continuar con la solicitud.',
                icon: 'error',
                cancelButtonColor: '#d33'
            });
        }else{
            $('#resetpassword').removeAttr('disabled');
        }
    }
</script>
