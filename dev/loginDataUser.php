<?php
require_once __DIR__ . '/../config/includes.php';

$arrayDivision = get::getDivisionName();
session_start();

$loginUrl = 'login.php';

if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
    header('Location: ' . $loginUrl);
    exit;
}

// Tiempo máximo de inactividad: 30 minutos.
$timeout = 1800;

if (
    isset($_SESSION['last_session']) &&
    (time() - $_SESSION['last_session'] > $timeout)
) {
    session_unset();
    session_destroy();

    header('Location: ' . $loginUrl);
    exit;
}

// Validación de IP.
$currentIp = $_SERVER['REMOTE_ADDR'] ?? '';

if (!isset($_SESSION['ip'])) {
    $_SESSION['ip'] = $currentIp;
} elseif ($_SESSION['ip'] !== $currentIp) {
    session_unset();
    session_destroy();

    header('Location: ' . $loginUrl);
    exit;
}

// Validación del navegador.
$currentAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $currentAgent;
} elseif ($_SESSION['user_agent'] !== $currentAgent) {
    session_unset();
    session_destroy();

    header('Location: ' . $loginUrl);
    exit;
}

$_SESSION['last_session'] = time();

$user = $_SESSION['user'];

$name = trim(
    ($user['name'] ?? '') . ' ' .
    ($user['last_name'] ?? '')
);

if ($name === '') {
    $name = 'Usuario';
}


$division = $arrayDivision[$user['division']] ?? 'Usuario autorizado';

function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#003152">
    <link href="../favicon/fygroup.ico" rel="icon">
    <link href="../favicon/fygroup-256x256.png" rel="apple-touch-icon">
    <link rel="manifest" href="../favicon/site.webmanifest">
    <title>Iniciando sesión | FYGroup</title>

    <!-- Recursos existentes -->
    <link href="../assets/css/all.css" rel="stylesheet">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">

    <!-- Diseño de bienvenida -->
    <link href="../assets/css/preload.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="preload-body">
    <main class="preload-page">
        <div class="preload-container">
            <!-- CABECERA -->
            <header class="preload-header">
                <div class="preload-brand">
                    <img
                        src="../logos/new-logo-fygroup-bg-removed.png"
                        alt="FYGroup"
                        class="preload-logo"
                    >
                </div>

                <span class="preload-version">
                    SISTEMA INTEGRAL
                </span>
            </header>

            <!-- TARJETA PRINCIPAL -->
            <section class="preload-card">
                <!-- BIENVENIDA -->
                <div class="preload-banner">
                    <div class="welcome-label">
                        <span class="welcome-label-dot"></span>
                        ACCESO AUTORIZADO
                    </div>

                    <h1>¡Bienvenido!</h1>

                    <p class="welcome-description">
                        Estamos preparando tu espacio de trabajo.
                    </p>

                    <div class="preload-user">
                        <div class="preload-avatar">
                            <i class="fas fa-user"></i>
                        </div>

                        <div class="preload-user-info">
                            <span>Hola,</span>

                            <strong><?= e($name) ?></strong>

                            <small>
                                <i class="fas fa-building"></i>
                                <?= e($division) ?>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- PROCESO DE CARGA -->
                <div class="preload-content">
                    <div class="loading-heading">
                        <div class="loading-spinner">
                            <span></span>
                        </div>

                        <div>
                            <h2>Preparando tu sesión</h2>

                            <p>
                                Por favor, espera un momento.
                            </p>
                        </div>
                    </div>

                    <!-- PROGRESO -->
                    <div class="progress-information">
                        <div class="progress-labels">
                            <span id="status" aria-live="polite">
                                Inicializando acceso...
                            </span>

                            <strong id="progressPercent">0%</strong>
                        </div>

                        <div
                            class="preload-progress"
                            role="progressbar"
                            aria-label="Progreso de inicio"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            aria-valuenow="0"
                            id="progressContainer"
                        >
                            <div class="preload-progress-bar" id="bar"></div>
                        </div>

                    </div>

                    <!-- VALIDACIONES -->
                    <div class="preload-checks">
                        <div class="check-item" id="checkUser">
                            <div class="check-icon">
                                <i class="fas fa-user-check"></i>
                            </div>

                            <div class="check-info">
                                <strong>Usuario</strong>
                                <span>Identidad validada</span>
                            </div>

                            <div class="check-result">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>

                        <div class="check-item" id="checkSession">
                            <div class="check-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>

                            <div class="check-info">
                                <strong>Sesión</strong>
                                <span>Sesión autenticada</span>
                            </div>

                            <div class="check-result">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>

                        <div class="check-item" id="checkSystem">
                            <div class="check-icon">
                                <i class="fas fa-server"></i>
                            </div>

                            <div class="check-info">
                                <strong>Sistema</strong>
                                <span id="systemStatus">
                                    Preparando acceso
                                </span>
                            </div>

                            <div class="check-result pending">
                                <i class="fas fa-circle-notch"></i>
                            </div>
                        </div>
                    </div>

                    <!-- CUENTA REGRESIVA -->
                    <div class="preload-countdown">
                        <div class="countdown-icon">
                            <i class="far fa-clock"></i>
                        </div>

                        <div class="countdown-text">
                            <strong>Ya casi estamos listos</strong>

                            <span>
                                Entrando en
                                <strong id="countdown">5</strong>
                                segundos
                            </span>
                        </div>

                        <div class="countdown-number" id="countdownNumber">
                            5
                        </div>
                    </div>

                    <noscript>
                        <div class="alert alert-warning mt-3">
                            JavaScript está deshabilitado.
                            <a href="dashboard.php">
                                Continuar al sistema
                            </a>
                        </div>
                    </noscript>
                </div>
            </section>

            <!-- PIE -->
            <footer class="preload-footer">
                <div class="footer-brand">
                    <i class="fas fa-code"></i>
                    <strong>Entorno de Desarrollo</strong>
                </div>

                <span>
                    Sistema Integral FYGroup
                </span>

                <span class="footer-security">
                    <i class="fas fa-lock"></i>
                    Acceso protegido
                </span>
            </footer>
        </div>
    </main>

    <script>
        (function () {
            'use strict';

            var steps = [
                {
                    text: 'Validando usuario...',
                    progress: 20,
                    delay: 800
                },
                {
                    text: 'Verificando sesión...',
                    progress: 45,
                    delay: 1000
                },
                {
                    text: 'Preparando entorno...',
                    progress: 70,
                    delay: 1000
                },
                {
                    text: 'Finalizando inicialización...',
                    progress: 90,
                    delay: 1000
                },
                {
                    text: 'Accediendo al sistema...',
                    progress: 100,
                    delay: 1200
                }
            ];

            var status = document.getElementById('status');
            var bar = document.getElementById('bar');
            var percent = document.getElementById('progressPercent');
            var progressContainer =
                document.getElementById('progressContainer');

            var countdown = document.getElementById('countdown');
            var countdownNumber =
                document.getElementById('countdownNumber');

            var systemStatus = document.getElementById('systemStatus');
            var systemCheck = document.getElementById('checkSystem');

            var index = 0;
            var remaining = 5;
            var startedAt = Date.now();
            var redirecting = false;

            function updateProgress(value) {
                bar.style.width = value + '%';
                percent.textContent = value + '%';

                progressContainer.setAttribute(
                    'aria-valuenow',
                    value
                );
            }

            function runStep() {
                if (redirecting || index >= steps.length) {
                    return;
                }

                var step = steps[index];

                status.textContent = step.text;
                updateProgress(step.progress);

                if (index >= 1) {
                    document
                        .getElementById('checkSession')
                        .classList.add('check-complete');
                }

                if (index >= 3) {
                    systemStatus.textContent = 'Acceso preparado';
                    systemCheck.classList.add('check-complete');

                    var result =
                        systemCheck.querySelector('.check-result');

                    result.classList.remove('pending');
                    result.innerHTML =
                        '<i class="fas fa-check-circle"></i>';
                }

                index++;

                if (index < steps.length) {
                    window.setTimeout(runStep, step.delay);
                } else {
                    window.setTimeout(redirectToDashboard, 300);
                }
            }

            function updateCountdown() {
                var elapsed = Math.floor(
                    (Date.now() - startedAt) / 1000
                );

                remaining = Math.max(0, 5 - elapsed);

                countdown.textContent = remaining;
                countdownNumber.textContent = remaining;

                if (remaining > 0) {
                    window.setTimeout(updateCountdown, 250);
                }
            }

            function redirectToDashboard() {
                if (redirecting) {
                    return;
                }

                redirecting = true;
                status.textContent = 'Redirigiendo al dashboard...';
                updateProgress(100);
                window.location.replace('dashboard.php');
            }

            updateProgress(0);
            updateCountdown();
            runStep();
        })();
    </script>
</body>
</html>
