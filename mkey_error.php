<?php
session_start();

$max_inactivity = 30 * 60;

/* tomar SOLO el path de la URL real */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

/* romper por segmentos */
$parts = array_values(array_filter(explode('/', $path)));

/* buscar base real del sistema (fygroup-antepuerto, dev, myFY, myPortal) */
$allowed = ['fygroup-antepuerto', 'dev', 'myFY', 'myPortal'];

$base = '/';

foreach ($parts as $i => $p) {
    if (in_array($p, $allowed)) {
        $base = '/' . $p . '/';

        /* si existe segundo nivel tipo /fygroup-antepuerto/dev/ */
        if (isset($parts[$i + 1]) && in_array($parts[$i + 1], $allowed)) {
            $base = '/' . $p . '/' . $parts[$i + 1] . '/';
        }

        break;
    }
}

/* rutas */
$login = $base . 'login.php';
$dashboard = $base . 'dashboard.php';

/* sesión */
$redirect_url = $login;

/* sin sesión */
if (!isset($_SESSION['user_id'])) {
    $redirect_url = $login;
} else {

    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
    }

    if (time() - $_SESSION['last_activity'] > $max_inactivity) {
        session_unset();
        session_destroy();
        $redirect_url = $login;
    } else {
        $_SESSION['last_activity'] = time();
        $redirect_url = $dashboard;
    }
}

http_response_code(401);
?>

<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FYGroup | Error 401</title>
    <meta http-equiv="refresh" content="5;url=<?php echo $redirect_url; ?>">
    <link href="../favicon/fygroup.ico" rel="icon">
    <link href="../favicon/fygroup-256x256.png" rel="apple-touch-icon">
    <link rel="manifest" href="../favicon/site.webmanifest">

    <!-- Fonts -->
    <link href="../assets/css/all.min.css" rel="stylesheet">

    <!-- Estilos del sistema -->
    <link href="../assets/css/fygroup.css" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
</head>
<body>
    <div class="container error-panel">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10" style="margin-bottom: 50px;">
                <div class="card error-card">
                    <div class="error-banner text-center">
                        <img src="../logos/new-logo-fygroup-bg-removed.png" alt="FYGroup" class="error-logo">

                        <h1>Error 401</h1>

                        <p class="mb-0">
                            Acceso No Autorizado
                        </p>
                    </div>

                    <div class="error-body text-center">
                        <i class="fas fa-key fa-4x mb-4" style="color:#293c74;"></i>

                        <h3 class="text-gray-800">
                            Credenciales de acceso inválidas
                        </h3>

                        <p class="text-muted mt-3">
                            La URL consultada requiere una clave de acceso
                            válida o parámetros de autenticación obligatorios.
                        </p>

                        <p class="text-muted">
                            No fue posible validar tu solicitud para acceder
                            al recurso solicitado.
                        </p>

                        <div class="alert alert-warning mt-4 mb-4">
                            <i class="fas fa-clock mr-2"></i>
                            Serás redirigido automáticamente en
                            <strong id="countdown">5</strong> segundos
                        </div>

                        <div class="row mt-5">
                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-key"></i>
                                    </div>

                                    <strong>Acceso</strong>

                                    <div class="text-muted mt-2">
                                        No Validado
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="status-item">
                                    <div class="status-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>

                                    <strong>Estado</strong>

                                    <div class="text-danger mt-2">
                                        Error 401
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
                                        <?= (str_contains($redirect_url, 'dashboard')) ? 'Dashboard' : 'Login' ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
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

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/js/fygroup.js"></script>
</body>
</html>

<script>
let seconds = 5;
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
