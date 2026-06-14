<?php
require_once __DIR__ . '/config/includes.php';
session_start();

// Tiempo máximo de inactividad en segundos (30 minutos)
$max_inactivity = 30 * 60;

// Por defecto, redirigir a login
$redirect_url = 'myFY/login.php';

// Verifica si hay sesión activa y no expirada
if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['last_activity']) || (time() - $_SESSION['last_activity'] <= $max_inactivity)) {
        $redirect_url = 'dashboard.php';
    } else {
        // Sesión expirada
        session_unset();
        session_destroy();
        $redirect_url = 'myFY/login.php';
    }
}

// Actualiza última actividad si la sesión sigue activa
if ($redirect_url === 'dashboard.php') {
    $_SESSION['last_activity'] = time();
}

// Código HTTP 404
http_response_code(404);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FYGroup | Error 404</title>
    <meta http-equiv="refresh" content="5;url=<?php echo $redirect_url; ?>">
    <link rel="icon" type="image/png" href="favicon/apple-touch-icon.png"/>

    <!-- Fonts -->
    <link href="assets/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900" rel="stylesheet">

    <!-- Estilos del sistema -->
    <link href="assets/css/fygroup.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <style>
        body{
            background:#f4f6fb;
            font-family:'Nunito',sans-serif;
            margin:0;
            padding:0;
        }

        .error-card{
            border:none;
            border-top:4px solid #293c74;
            border-radius:12px;
            margin-bottom: 50px;
            overflow:hidden;
            box-shadow:0 .15rem 1.75rem rgba(58,59,69,.15);
        }

        .error-banner{
            background:linear-gradient(135deg,#293c74,#3b5297);
            color:#fff;
            padding:50px;
        }

        .error-banner h1{
            font-weight:800;
            margin-bottom:10px;
        }

        .error-topbar{
            background:#293c74;
            height:70px;
            box-shadow:0 .15rem 1.75rem rgba(58,59,69,.15);
        }

        .error-panel{
            margin-top:50px;
            margin-bottom:50px;
        }

        .error-body{
            padding:50px;
        }

        .status-item{
            background:#f8f9fc;
            border-radius:10px;
            padding:25px;
            transition:.3s;
            height:100%;
            border:1px solid #e3e6f0;
        }

        .status-item:hover{
            transform:translateY(-3px);
        }

        .status-icon{
            font-size:2rem;
            color:#293c74;
            margin-bottom:15px;
        }

        .btn-fy{
            background:#293c74;
            border-color:#293c74;
            color:#fff;
            font-weight:600;
            padding:12px 25px;
        }

        .btn-fy:hover{
            background:#1f2e5a;
            border-color:#1f2e5a;
            color:#fff;
        }

        .page-logo{
            width:100px;
            height:auto;
            margin-bottom:15px;
        }

        .page-icon{
            font-size:4rem;
            color:#293c74;
            margin-bottom:25px;
        }

        @media (max-width:768px){
            .error-panel{
                margin-top:20px;
                margin-bottom:20px;
            }

            .error-body{
                padding:25px;
            }

            .error-banner{
                padding:25px;
            }

            .page-logo{
                width:60px;
            }

            .btn-fy{
                width:100%;
            }

            .status-item{
                margin-bottom:15px;
            }

            .page-body{
                padding:25px;
            }

            .page-logo{
                width:60px;
            }

            .btn-fy{
                width:100%;
            }
        }
    </style>
</head>
<body>
    <div class="error-topbar"></div>
    <div class="container error-panel">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="card error-card">
                    <div class="error-banner text-center">
                        <img src="logos/logo-fygroup-circle-v1.png" alt="FYGroup" class="page-logo">

                        <h1>Error 404</h1>

                        <p class="mb-0">
                            Página No Encontrada
                        </p>
                    </div>

                    <div class="error-body text-center">
                        <i class="fas fa-exclamation-triangle fa-4x mb-4" style="color:#293c74;"></i>

                        <h3 class="text-gray-800">
                            No hemos podido encontrar la página solicitada
                        </h3>

                        <p class="text-muted mt-3">
                            La dirección ingresada no existe,
                            fue eliminada o no tienes permisos
                            para acceder a ella.
                        </p>

                        <div class="alert alert-warning mt-4 mb-4">
                            <i class="fas fa-clock mr-2"></i>
                            Serás redirigido automáticamente en
                            <strong>5 segundos</strong>
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-link"></i>
                                    </div>

                                    <strong>URL</strong>

                                    <div class="text-muted mt-2">
                                        Inválida
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>

                                    <strong>Estado</strong>

                                    <div class="text-danger mt-2">
                                        Error 404
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-home"></i>
                                    </div>

                                    <strong>Destino</strong>

                                    <div class="text-success mt-2">
                                        <?= ($redirect_url === 'dashboard.php') ? 'Dashboard' : 'Login' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="<?= $redirect_url; ?>" class="btn btn-fy btn-lg">
                                <i class="fas fa-home mr-2"></i>
                                Ir Ahora

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/fygroup.js"></script>
</body>
</html>
