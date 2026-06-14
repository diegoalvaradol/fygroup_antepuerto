<?php
require_once __DIR__ . '/config/includes.php';

$footer = menu::footerSSL();
$top = UIComponents::scrollToTopButton();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="/ssl-chile/favicon/apple-touch-icon.png"/>

    <title>FYGroup | Página en Mantención</title>

    <!-- Fonts -->
    <link href="/ssl-chile/assets/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Estilos FYGroup -->
    <link href="/ssl-chile/assets/css/fygroup.css" rel="stylesheet">
    <link href="/ssl-chile/assets/css/app.css" rel="stylesheet">

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
        }

        .topbar-maintenance {
            margin: 0;
            height: 80px;
            background: #293c74;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
        }

        .maintenance-container {
            padding-top: 60px;
            padding-bottom: 60px;
        }

        .maintenance-card {
            background: #ffffff;
            border-left: 6px solid #293c74;
            border-radius: .35rem;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
            padding: 3rem;
        }

        .logo-maintenance {
            max-width: 280px;
            margin-bottom: 25px;
        }

        .maintenance-title {
            color: #293c74;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .maintenance-subtitle {
            color: #5a5c69;
            font-size: 1.25rem;
            margin-bottom: 30px;
        }

        .maintenance-text {
            color: #858796;
            font-size: 1rem;
            line-height: 1.8;
        }

        .img-maintenance {
            max-width: 320px;
            width: 100%;
            margin: 25px auto;
        }

        .btn-fygroup {
            background: #293c74;
            border-color: #293c74;
            color: #fff;
            padding: .6rem 1.5rem;
            font-weight: 600;
        }

        .btn-fygroup:hover {
            background: #1f2e5a;
            border-color: #1f2e5a;
            color: #fff;
        }

        .contact-box {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e3e6f0;
        }

        @media (max-width: 768px) {
            .maintenance-title {
                font-size: 2rem;
            }

            .maintenance-card {
                padding: 2rem;
            }

            .logo-maintenance {
                max-width: 220px;
            }
        }
    </style>
</head>

<body id="page-top">
    <div class="topbar-maintenance"></div>
    <div class="container maintenance-container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="maintenance-card text-center">
                    <img src="/ssl-chile/logos/logo-fygroup-bg-removed.png"
                         alt="FYGroup"
                         class="logo-maintenance">

                    <h1 class="maintenance-title">
                        Página en Mantención
                    </h1>

                    <p class="maintenance-subtitle">
                        Estamos realizando mejoras para entregarte una mejor experiencia.
                    </p>

                    <img src="/ssl-chile/images/img-maintenance.jpg"
                         alt="Mantención"
                         class="img-fluid img-maintenance">

                    <p class="maintenance-text">
                        Nuestro equipo se encuentra trabajando para optimizar el sistema.
                    </p>

                    <p class="maintenance-text">
                        El servicio volverá a estar disponible tan pronto como finalicen las tareas de actualización.
                    </p>

                    <div class="contact-box">
                        <p class="maintenance-text mb-4">
                            Si tienes alguna consulta urgente, por favor contacta a nuestro equipo de soporte.
                        </p>

                        <button type="button"
                                class="btn btn-fygroup"
                                onclick="window.location.reload();">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Reintentar acceso
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <?php echo $footer; ?>
    <?php echo $top; ?>

    <!-- Scripts -->
    <script src="/ssl-chile/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/ssl-chile/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/ssl-chile/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/ssl-chile/assets/js/fygroup.js"></script>
</body>
</html>
