<?php
require_once __DIR__ . '/config/includes.php';

$status = SYSTEM_STATUS[APP_MODE];
$period = getPeriodInfo($status['closed_start'], $status['closed_end']);
?>

<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FYGroup | Fuera de Servicio</title>
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <link rel="manifest" href="../favicon/site.webmanifest">

    <!-- Fonts -->
    <link href="../assets/css/all.min.css" rel="stylesheet">

    <!-- Estilos del sistema -->
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 maintenance-panel">
                <div class="card maintenance-card">
                    <div class="maintenance-banner text-center">
                        <img src="../logos/new-logo-fygroup-bg-removed.png" alt="FYGroup" class="maintenance-logo">
                        <h1>Sistema Temporalmente Fuera de Servicio</h1>

                        <p class="mb-0">
                            Sistema Antepuerto FYGroup
                        </p>
                    </div>

                    <!-- Cuerpo -->
                    <div class="maintenance-body text-center">
                        <i class="fas fa-road-barrier fa-4x mb-4" style="color:#293c74;"></i>

                        <h3 class="text-gray-800">
                            Servicio Temporalmente Suspendido
                        </h3>

                        <p class="text-muted mt-3">
                            El acceso al Sistema Antepuerto FYGroup se encuentra temporalmente cerrado debido a una mantención programada de la plataforma.
                        </p>

                        <p class="text-muted">
                            Nuestro equipo se encuentra ejecutando tareas de actualización, optimización y revisión de componentes internos para mejorar la estabilidad y disponibilidad del sistema.
                        </p>

                        <p class="text-muted mb-0">
                            Agradecemos su comprensión durante este proceso. El sistema será habilitado nuevamente una vez finalizada la ventana de mantención programada.
                        </p>

                        <div class="alert alert-primary mt-4 mb-4">
                            <i class="fas fa-clock mr-2"></i>

                            Tiempo programado de cierre:

                            <strong>
                                <?= $period['duration_days'] > 0 ? $period['duration_days'] . ' días ' : '' ?>
                                <?= $period['duration_hours'] ?> horas
                                <?= $period['duration_minutes'] ?> minutos
                            </strong>
                        </div>

                        <!-- Contador digital -->
                        <div class="maintenance-countdown">
                            <div class="countdown-header">
                                <i class="fas fa-hourglass-half"></i>
                                Tiempo para reapertura
                            </div>

                            <div class="countdown-digital" id="countdown-digital-div">
                                <div class="time-box">
                                    <div class="time-value" id="days"><?= str_pad($period['remaining_days'], 2, '0', STR_PAD_LEFT) ?></div>
                                    <div class="time-text">Días</div>
                                </div>

                                <div class="time-separator">:</div>

                                <div class="time-box">
                                    <div class="time-value" id="hours"><?= str_pad($period['remaining_hours'], 2, '0', STR_PAD_LEFT) ?></div>
                                    <div class="time-text">Horas</div>
                                </div>

                                <div class="time-separator">:</div>

                                <div class="time-box">
                                    <div class="time-value" id="minutes"><?= str_pad($period['remaining_minutes'], 2, '0', STR_PAD_LEFT) ?></div>
                                    <div class="time-text">Min</div>
                                </div>

                                <div class="time-separator">:</div>

                                <div class="time-box">
                                    <div class="time-value" id="seconds"><?= str_pad($period['remaining_seconds'], 2, '0', STR_PAD_LEFT) ?></div>
                                    <div class="time-text">Seg</div>
                                </div>
                            </div>
                        </div>

                        <!-- Barra de progreso -->
                        <div class="maintenance-progress mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">
                                    <i class="fas fa-lock mr-2"></i>
                                    Cierre temporal programado
                                </span>
                            </div>

                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar"
                                    role="progressbar"
                                    style="width: <?= $period['progress'] ?>%;"
                                    aria-valuenow="<?= $period['progress'] ?>"
                                    aria-valuemin="0"
                                    aria-valuemax="100">

                                    <?= $period['progress_text'] ?>
                                </div>
                            </div>

                            <small class="text-muted d-block mt-2">
                                Avance del periodo de cierre programado
                            </small>
                        </div>

                        <!-- Horarios -->
                        <div class="maintenance-schedule">
                            <div class="schedule-header">
                                <i class="fas fa-calendar-alt"></i>
                                Horario de cierre programado
                            </div>

                            <div class="row text-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="schedule-box">
                                        <div class="schedule-label">
                                            Inicio
                                        </div>

                                        <div class="schedule-date">
                                            <?= $period['start_date'] ?>
                                        </div>

                                        <div class="schedule-time">
                                            <?= $period['start_time'] ?> hrs
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="schedule-box">
                                        <div class="schedule-label">
                                            Fin Estimado
                                        </div>

                                        <div class="schedule-date">
                                            <?= $period['end_date'] ?>
                                        </div>

                                        <div class="schedule-time">
                                            <?= $period['end_time'] ?> hrs
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estado de servicios -->
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

                                    <strong>Disponibilidad</strong>

                                    <div class="text-warning mt-2">
                                        Acceso suspendido temporalmente
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-headset"></i>
                                    </div>

                                    <strong>Atención</strong>

                                    <div class="text-success mt-2">
                                        Soporte disponible
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Soporte -->
                        <div class="mt-3">
                            <a href="https://wa.me/56923816700?text=Hola%20necesito%20ayuda" target="_blank" class="btn btn-outline-success btn-lg">
                                <i class="fab fa-whatsapp mr-2"></i>Contactar Soporte
                            </a>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-fy btn-lg" onclick="location.reload();">
                                <i class="fas fa-sync-alt mr-2"></i>Verificar disponibilidad
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
setInterval(function () {
    $('#countdown-digital-div').load(location.href + ' #countdown-digital-div');
}, 1000);
</script>
