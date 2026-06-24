<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$timeout = 1800;

if (isset($_SESSION['last_session']) && (time() - $_SESSION['last_session'] > $timeout)) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['ip'])) {
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
} elseif ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

$_SESSION['last_session'] = time();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>
    <title>Cargando...</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../assets/css/all.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
</head>

<body>
    <div class="container preload-panel">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="card preload-card">
                    <div class="preload-banner text-center">
                        <img src="../logos/logo-fygroup-circle-v1.png" class="page-logo">

                        <h1>Bienvenido</h1>

                        <p class="mb-0">
                            <h4><?= htmlspecialchars($_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name']) ?></h4>
                        </p>
                    </div>

                    <div class="preload-body text-center">
                        <div class="loading-spinner"></div>

                        <h3 class="mb-4">
                            Iniciando Sistema
                        </h3>

                        <p id="status" class="loading-step">
                            Inicializando sistema...
                        </p>

                        <div class="progress mt-4 mb-5">
                            <div id="bar" class="progress-bar" role="progressbar"style="width:0%"></div>
                        </div>

                        <div class="alert alert-success mt-4 mb-4">
                            <i class="fas fa-clock mr-2"></i>
                            Ingresndo en <strong id="countdown">5</strong> segundos
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-user-check"></i>
                                    </div>

                                    <strong>Usuario</strong>

                                    <div class="text-success mt-2">
                                        Validado
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>

                                    <strong>Sesión</strong>

                                    <div class="text-success mt-2">
                                        Activa
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-server"></i>
                                    </div>

                                    <strong>Sistema</strong>

                                    <div class="text-primary mt-2">
                                        Cargando
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/fygroup.js"></script>
</body>
</html>

<script>
const steps = [
    { text: "Cargando...", progress: 0 },
    { text: "Validando datos...", progress: 25 },
    { text: "Validando sesión...", progress: 50 },
    { text: "Cargando preferencias...", progress: 75 },
    { text: "Entrando al sistema...", progress: 100 }
];

let i = 0;

function nextStep() {
    if (i < steps.length) {
        document.getElementById("status").innerText = steps[i].text;
        document.getElementById("status").style.fontStyle = "italic";
        document.getElementById("bar").style.width = steps[i].progress + "%";
        i++;

        setTimeout(nextStep, 1000);
    } else {
        setTimeout(() => {
            window.location.href = "dashboard.php";
        }, 500);
    }
}

nextStep();

let seconds = 5;
const countdownElement = document.getElementById('countdown');
const timer = setInterval(() => {
    seconds--;

    if (countdownElement) {
        countdownElement.textContent = seconds;
    }

    if (seconds <= 0) {
        clearInterval(timer);
        window.location.href = "dashboard.php";
    }
}, 1000);
</script>
