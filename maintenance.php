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

                        <div class="maintenance-countdown">
                            <div class="countdown-header">
                                <i class="fas fa-hourglass-half"></i>
                                Tiempo Restante
                            </div>

                            <div class="countdown-digital">
                                <div class="time-box">
                                    <div class="time-value" id="days">00</div>
                                    <div class="time-text">Días</div>
                                </div>

                                <div class="time-separator">:</div>

                                <div class="time-box">
                                    <div class="time-value" id="hours">00</div>
                                    <div class="time-text">Horas</div>
                                </div>

                                <div class="time-separator">:</div>

                                <div class="time-box">
                                    <div class="time-value" id="minutes">00</div>
                                    <div class="time-text">Min</div>
                                </div>

                                <div class="time-separator">:</div>

                                <div class="time-box">
                                    <div class="time-value" id="seconds">00</div>
                                    <div class="time-text">Seg</div>
                                </div>
                            </div>
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
                                        Sistema Antepuerto
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

                        <div class="mt-3">
                            <a href="https://wa.me/56923816700?text=Hola%20necesito%20ayuda" target="_blank" class="btn btn-outline-success btn-lg">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Soporte por WhatsApp
                            </a>
                        </div>

                        <div class="mt-3">
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

<script>
const maintenanceEnd = new Date("<?= $end->format('Y-m-d H:i:s') ?>").getTime();

function updateCountdown() {
    const now = new Date().getTime();
    const distance = maintenanceEnd - now;

    if (distance <= 0) {
        document.getElementById('days').innerHTML = '00';
        document.getElementById('hours').innerHTML = '00';
        document.getElementById('minutes').innerHTML = '00';
        document.getElementById('seconds').innerHTML = '00';

        location.reload();
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById('days').innerHTML = String(days).padStart(2, '0');
    document.getElementById('hours').innerHTML = String(hours).padStart(2, '0');
    document.getElementById('minutes').innerHTML = String(minutes).padStart(2, '0');
    document.getElementById('seconds').innerHTML = String(seconds).padStart(2, '0');
}

updateCountdown();
setInterval(updateCountdown, 1000);
</script>
