<?php
require_once __DIR__ . '/config/includes.php';

$start = new DateTime(MAINTENANCE_START);
$end = new DateTime(MAINTENANCE_END);
$interval = $start->diff($end);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FYGroup | Mantención</title>
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>

    <!-- Fonts -->
    <link href="../assets/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900" rel="stylesheet">

    <!-- Estilos del sistema -->
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">

    <style>
        body{
            background:#f4f6fb;
            font-family:'Nunito',sans-serif;
            margin:0;
            padding:0;
        }

        .maintenance-topbar{
            background:#293c74;
            height:70px;
            box-shadow:0 .15rem 1.75rem rgba(58,59,69,.15);
        }

        .maintenance-panel{
            margin-top:50px;
            margin-bottom:50px;
        }

        .maintenance-card{
            border:none;
            border-top:4px solid #293c74;
            border-radius:12px;
            margin-bottom: 50px;
            overflow:hidden;
            box-shadow:0 .15rem 1.75rem rgba(58,59,69,.15);
        }

        .maintenance-banner{
            background:linear-gradient(135deg,#293c74,#3b5297);
            color:#fff;
            padding:50px;
        }

        .maintenance-logo{
            width:100px;
            height:auto;
            margin-bottom:15px;
        }

        .maintenance-banner h1{
            font-weight:800;
            margin-bottom:10px;
        }

        .maintenance-body{
            padding:50px;
        }

        .maintenance-body h3{
            font-weight:800;
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

        .maintenance-schedule{
            margin-top:30px;
            margin-bottom:30px;
            background:#f8f9fc;
            border:1px solid #e3e6f0;
            border-radius:12px;
            overflow:hidden;
        }

        .schedule-header{
            background:#293c74;
            color:#fff;
            padding:15px;
            font-weight:700;
            text-align:center;
        }

        .schedule-header i{
            margin-right:8px;
        }

        .schedule-box{
            padding:25px;
        }

        .schedule-label{
            color:#858796;
            font-size:.85rem;
            text-transform:uppercase;
            font-weight:700;
        }

        .schedule-date{
            color:#293c74;
            font-size:1.3rem;
            font-weight:800;
            margin-top:8px;
        }

        .schedule-time{
            font-size:1rem;
            color:#5a5c69;
            margin-top:5px;
        }

        @media (max-width:768px){
            .maintenance-panel{
                margin-top:20px;
                margin-bottom:20px;
            }

            .maintenance-banner{
                padding:25px;
            }

            .maintenance-body{
                padding:25px;
            }

            .maintenance-banner h1{
                font-size:1.7rem;
            }

            .maintenance-logo{
                width:50px;
            }

            .schedule-box{
                padding:15px;
            }

            .btn-fy{
                width:100%;
            }

            .status-item{
                margin-bottom:15px;
            }
        }
    </style>
</head>

<body>
    <div class="maintenance-topbar"></div>
    <div class="container maintenance-panel">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="card maintenance-card">
                    <div class="maintenance-banner text-center">
                        <img src="../logos/logo-fygroup-circle-v1.png" alt="FYGroup" class="maintenance-logo">
                        <h1>Mantención Programada</h1>

                        <p class="mb-0">
                            Sistema Antepuerto FYGroup
                        </p>
                    </div>

                    <div class="maintenance-body text-center">
                        <i class="fas fa-tools fa-4x mb-4"
                           style="color:#293c74;"></i>

                        <h3 class="text-gray-800">
                            Estamos realizando mejoras
                        </h3>

                        <p class="text-muted mt-3">
                            Nuestro equipo se encuentra trabajando para optimizar la plataforma,
                            mejorar la estabilidad del sistema y entregar una mejor experiencia
                            a nuestros usuarios.
                        </p>

                        <p class="text-muted">
                            El acceso será restablecido una vez finalizadas las tareas de mantención.
                        </p>


                        <div class="alert alert-primary mt-4 mb-4">
                            <i class="fas fa-clock mr-2"></i>

                            Duración estimada:

                            <strong>
                                <?= ($interval->days * 24) + $interval->h ?> horas
                                <?= $interval->i ?> minutos
                            </strong>
                        </div>

                        <div class="maintenance-schedule">
                            <div class="schedule-header">
                                <i class="fas fa-calendar-alt"></i>
                                Ventana de Mantención
                            </div>

                            <div class="row text-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="schedule-box">
                                        <div class="schedule-label">
                                            Inicio
                                        </div>

                                        <div class="schedule-date">
                                            <?= $start->format('d-m-Y') ?>
                                        </div>

                                        <div class="schedule-time">
                                            <?= $start->format('H:i') ?> hrs
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="schedule-box">
                                        <div class="schedule-label">
                                            Fin Estimado
                                        </div>

                                        <div class="schedule-date">
                                            <?= $end->format('d-m-Y') ?>
                                        </div>

                                        <div class="schedule-time">
                                            <?= $end->format('H:i') ?> hrs
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-server"></i>
                                    </div>

                                    <strong>Plataforma</strong>

                                    <div class="text-muted mt-2">
                                        FYGroup
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-cogs"></i>
                                    </div>

                                    <strong>Estado</strong>

                                    <div class="text-warning mt-2">
                                        En Mantención
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-headset"></i>
                                    </div>

                                    <strong>Soporte</strong>

                                    <div class="text-success mt-2">
                                        Disponible
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="button" class="btn btn-fy btn-lg" onclick="location.reload();">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Reintentar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/fygroup.js"></script>
</body>
</html>
