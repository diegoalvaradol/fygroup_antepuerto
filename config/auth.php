<?php
session_start();

/* ===============================
Base path automático
=============================== */
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$basePath = str_replace(['/dev', '/myFY', '/myPortal'], '', $basePath);

/* ===============================
Validar carpeta permitida
=============================== */
$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$allowedRoots = ['/dev', '/myFY', '/myPortal'];

$isAllowedRoot = false;

foreach ($allowedRoots as $root) {
    $endsWithRoot = substr($uriPath, -strlen($root)) === $root;

    if (strpos($uriPath, $root . '/') !== false || $endsWithRoot) {
        $isAllowedRoot = true;
        break;
    }
}

if (!$isAllowedRoot) {
    http_response_code(403);
    exit('Acceso no permitido.');
}

/* ===============================
Validar sesión
=============================== */
if (!isset($_SESSION['user'])) {
    header("Location: {$basePath}/login.php");
    exit();
}

/* ===============================
Variables
=============================== */
$division = $_SESSION['user']['division'] ?? '';
$seconds = 5;
$redirect = "{$basePath}/login.php";
$forbidden = false;

/* ===============================
Reglas de acceso
=============================== */
if (strpos($uriPath, '/myPortal') === 0) {
    if ($division !== 'terminal' && $division !== 'shipper') {
        $redirect = "{$basePath}/myPortal/login.php";
        $forbidden = true;
    }
}

if (strpos($uriPath, '/myFY') === 0) {
    if ($division !== 'fy') {
        $redirect = "{$basePath}/myFY/login.php";
        $forbidden = true;
    }
}

if (strpos($uriPath, '/dev') === 0) {
    if ($division !== 'fy') {
        $redirect = "{$basePath}/dev/login.php";
        $forbidden = true;
    }
}

/* ===============================
Vista 403 con redirección
=============================== */
if ($forbidden) {
    http_response_code(403);
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>FY Group | Acceso No Autorizado</title>

    <meta http-equiv="refresh" content="<?= $seconds ?>;url=<?= $redirect ?>">

    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1f2933, #111827);
            color: #fff;
        }

        .card {
            background: #1f2937;
            padding: 2.5rem 3rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,.4);
            max-width: 420px;
            width: 90%;
        }

        .card h1 {
            font-size: 3rem;
            margin-bottom: .5rem;
        }

        .card p {
            opacity: .85;
            margin-bottom: 1rem;
        }

        .card small {
            display: block;
            opacity: .6;
            margin-bottom: 2rem;
        }

        .card a {
            display: inline-block;
            padding: .75rem 1.5rem;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .card a:hover {
            background: #1d4ed8;
        }

        .logo {
            width: 120px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="card">

    <img src="../logos/logo-fygroup-circle-v1.png"
         alt="FYGroup"
         class="logo">

    <h1>403</h1>

    <p>No tienes permisos para acceder a esta sección.</p>

    <p>Por favor contacta a soporte.</p>

    <small>
        Serás redirigido en <?= $seconds ?> segundos...
    </small>

    <a href="<?= $redirect ?>">
        Ir ahora
    </a>

</div>

</body>
</html>

<script>
let seconds = <?= $seconds ?>;

const timer = setInterval(() => {
    seconds--;

    if (seconds <= 0) {
        clearInterval(timer);
        window.location.href = "<?= $redirect ?>";
    }
}, 1000);
</script>
<?php
        exit();
}
?>
