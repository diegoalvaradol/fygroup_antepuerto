<?php
session_start();

$seconds = 5;
$redirect_url = $_SESSION['redirect_after_403'] ?? 'login.php';

unset($_SESSION['redirect_after_403'], $_SESSION['redirect_seconds_403']);

if (!is_string($redirect_url) || $redirect_url === '') {
    $redirect_url = 'login.php';
}

http_response_code(403);
?>

<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FYGroup | Error 403</title>
    <meta http-equiv="refresh" content="<?= (int) $seconds ?>;url=<?= htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="icon" type="image/png" href="../favicon/fygroup.png"/>

    <!-- Fonts -->
    <link href="../assets/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900" rel="stylesheet">

    <!-- Estilos -->
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6fb;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
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
                        <img src="../logos/logo-fygroup-circle-v1.png" alt="FYGroup" class="page-logo">

                        <h1>Error 403</h1>

                        <p class="mb-0">
                            Área Inválida
                        </p>
                    </div>

                    <div class="error-body text-center">
                        <i class="fas fa-ban fa-4x mb-4" style="color:#293c74;"></i>

                        <h3 class="text-gray-800">
                            Acceso no autorizado
                        </h3>

                        <p class="text-muted mt-3">
                            Estás intentando acceder a un directorio o recurso
                            que no se encuentra disponible para tu perfil.
                        </p>

                        <p class="text-muted">
                            Si consideras que esto es un error, contacta al
                            administrador del sistema.
                        </p>

                        <div class="alert alert-warning mt-4 mb-4">
                            <i class="fas fa-clock mr-2"></i>
                            Serás redirigido automáticamente en
                            <strong id="countdown"><?= $seconds ?></strong> segundos
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-folder-open"></i>
                                    </div>

                                    <strong>Área</strong>

                                    <div class="text-muted mt-2">
                                        Restringida
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
                                        Error 403
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
                                        Redirección
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="<?= htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-fy btn-lg">
                                <i class="fas fa-home mr-2"></i>
                                Ir Ahora
                            </a>
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
let seconds = <?= (int) $seconds ?>;
const redirectUrl = <?= json_encode($redirect_url) ?>;
const countdownElement = document.getElementById('countdown');

const timer = setInterval(() => {
    seconds--;

    if (countdownElement) {
        countdownElement.textContent = seconds;
    }

    if (seconds <= 0) {
        clearInterval(timer);
        window.location.href = redirectUrl;
    }
}, 1000);
</script>
