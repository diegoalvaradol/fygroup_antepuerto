<!-- HTML -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Vista Formulario de Registro de Nuevo Usuario" content="">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>Portal - SSL | Registro</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-image: url("../img/coquimbo_port_background.jpg");
            background-size: cover;
        }

        .is-invalid {
            border-color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image text-center p-4" style="align-content:space-around">
                        <img src="../img/ssl-logo-azul.png" alt="FY Chile" class="img-fluid mb-3">

                        <div class="text-center">
                          <small class="text-success" style="font-size:xx-large">Sistema Integral SSL.</small>
                          <br>
                          <small class="text-success" style="font-size:x-large; text-align: center; margin: auto;">Portal Cliente.</small>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">¡Crea tu cuenta!</h1>
                            </div>
                            <form id="registerForm">
                                <div class="form-group row">
                                  <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" id="run" name="run" oninput="formatearRut(this)" maxlength="12" onblur="validaRut(this.value), verifyRun(this.value)" placeholder="12.345.678-9">
                                    <small class="text-danger" id="error-run"></small>
                                    <small class="text-success" id="info-run"></small>
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Juan">
                                    <small class="text-danger" id="error-name"></small>
                                  </div>

                                  <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" id="lastname" name="lastname" placeholder="Peréz Soto">
                                    <small class="text-danger" id="error-lastname"></small>
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <div class="col-sm-12">
                                    <input type="email" class="form-control form-control-user" id="email" name="email" onblur="verifyEmail(this.value)" placeholder="correo@dominio.com">
                                    <small class="text-danger" id="error-email"></small>
                                  </div>
                                </div>

                                <div class="form-group row">
                                  <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Contraseña">
                                    <small class="text-danger" id="error-password"></small>
                                  </div>

                                  <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Repetir Contraseña">
                                    <small class="text-danger" id="error-password2"></small>
                                  </div>
                                </div>

                                <button type="button" name="saveuser" class="btn btn-success btn-user btn-block" onclick="saveUser()"><i class='fas fa-solid fa-check-circle'></i> Registrar</button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot_password.php" style="font-size: medium;">¿Olvidaste la contraseña?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php" style="font-size: medium;">¿Tienes cuenta? Inicia Sesión!</a>
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

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
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
      $('#info-run').html('¡Run valido!');
    }else{
      Swal.fire({
        title: 'Oops...',
        text: 'RUN invalido, reintenta nuevamente.',
        icon: 'error',
        cancelButtonColor: '#d33',
      });
    }
  }

  var verifyRun = function(run) {
    $.ajax({
      url: '../controllers/runVerifyController.php',
      data: {
        run: run
      },
      type: "POST",
    }).done(function(x) {
      if(x == 'NOOK'){
        Swal.fire({
          title: 'Oops...',
          text: 'El RUN '+run+' ya se encuentra registrado.',
          icon: 'error',
          cancelButtonColor: '#d33',
        }).then((result) => {
          $('#run').val('').focus();
          $('#info-run').html('');
        });
      }
    });
  }

  var verifyEmail = function(email) {
    $.ajax({
      url: '../controllers/emailVerifyController.php',
      data: {
        email: email
      },
      type: "POST",
    }).done(function(x) {
      if(x == 'NOOK'){
        Swal.fire({
          title: 'Oops...',
          text: 'El correo '+email+' ya se encuentra registrado, inicia sesión pinchando el link de abajo.',
          icon: 'error',
          showConfirmButton: false,
          footer: '<a class="small" href="login.php" style="font-size: medium;">¡Inicia sesión acá!</a><br><a class="small" href="forgot_password.php" style="font-size: medium;">¿Olvidaste la contraseña?</a>'
        }).then((result) => {
          $('#email').val('');
        });
      }
    });
  }

  var saveUser = function() {
    const form = document.getElementById('registerForm');
    const formData = new FormData(form);
    const password = $("#password").val();
    const password2 = $("#password2").val();
    var division = 'portal'; /* Division portal cliente */
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    let hasError = false;

    document.querySelectorAll('small.text-danger').forEach(el => el.innerText = '');
    document.querySelectorAll('.form-control-user').forEach(el => el.classList.remove('is-invalid'));

    /* Validar si algún campo está vacío */
    for (let [key, value] of formData.entries()) {
      if (!value.trim()) {
        const errorElement = document.getElementById('error-' + key);

        if (errorElement) {
          errorElement.innerText = 'Este campo es obligatorio.';
          const inputElement = form.querySelector(`[name="${key}"]`);
          inputElement.classList.add('is-invalid');
        }

        hasError = true;
      }
    }

    /* Revisa que la contraseña tenga los caracteres obligatorios */
    if (!regex.test(password)) {
      Swal.fire({
        title: 'Oops...',
        text: 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.',
        icon: 'error',
        cancelButtonColor: '#d33',
      });

      hasError = true;
    }

    if (!regex.test(password2)) {
      Swal.fire({
        title: 'Oops...',
        text: 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.',
        icon: 'error',
        cancelButtonColor: '#d33',
      });

      hasError = true;
    }

    /* Validar que las contraseñas sean iguales */
    if (password !== password2) {
      document.getElementById('error-password2').innerText = 'Las contraseñas no coinciden.';
      document.getElementById('password2').classList.add('is-invalid');

      hasError = true;
    }

    /* Hace envio de los datos a traves del formulario */
    if(!hasError){
      $.ajax({
        url: '../controllers/userController.php',
        data: $('#registerForm').serialize() + '&division=' + encodeURIComponent(division),
        type: 'POST',
      }).done(function(x) {
        if(x == 'OK'){
          Swal.fire({
            title: '¡Éxito!',
            text: '¡Usuario registrado exitosamente!',
            icon: 'success',
            confirmButtonColor: '#4CAF50'
          }).then((result) => {
            window.location = 'login.php';
          });
        } else {
          Swal.fire({
            title: 'Oops...',
            text: 'Error al registrar usuario.',
            icon: 'error',
            cancelButtonColor: '#d33',
          });
        }
      });
    }
  }
</script>
