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
  <title>Cargando...</title>

  <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
  <link href="../assets/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
  <link href="../assets/css/fygroup.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        margin: 0;
        font-family: system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .card {
        backdrop-filter: blur(14px);
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.08);
        padding: 2.5rem 2rem;
        border-radius: 18px;
        text-align: center;
        color: #fff;
        width: 320px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }

    h1 {
        margin: 0;
        font-size: 1.6rem;
    }

    h2 {
        font-size: 0.95rem;
        font-weight: 400;
        color: #94a3b8;
        margin-bottom: 1.5rem;
    }

    p {
        margin: 0.3rem 0;
        font-size: 0.9rem;
        color: #94a3b8;
    }

    /* PROGRESS BAR */
    .progress {
        margin-top: 1.5rem;
        width: 100%;
        height: 6px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #38bdf8, #6366f1);
        transition: width 0.2s ease;
    }

    /* LOADER */
    .loader-wrap {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }

    .loader {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.2);
        border-top-color: #38bdf8;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    </style>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg border-0 rounded-lg p-5 text-center" style="max-width: 500px; width: 95%;">
        <div class="mb-4">
            <div class=" d-inline-flex justify-content-center align-items-center" style="width:90px; height:90px; font-size:40px;">
                <img src="../images/logo-fygroup-circle-v1.png" style="width:90%">
            </div>
        </div>

        <h1 class="h3 font-weight-bold text-muted mb-2">
            Bienvenido 👋
        </h1>

        <h2 class="h5 text-primary mb-4">
            <?= htmlspecialchars($_SESSION['user']['name'] . ' ' . $_SESSION['user']['last_name']) ?>
        </h2>

        <p id="status" class="text-muted mb-4">
            Inicializando sistema...
        </p>

        <div class="progress mb-4" style="height: 12px; border-radius: 20px; overflow: hidden;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="bar" role="progressbar" style="width: 0%"></div>
        </div>

        <div class="loader-wrap d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>

        <div class="mt-4 text-muted small">
            Espere un momento mientras cargamos tu información.
        </div>
    </div>
</body>

<script>
    const steps = [
        { text: "Validando datos...", progress: 25 },
        { text: "Validando sesión...", progress: 50 },
        { text: "Cargando preferencias...", progress: 75 },
        { text: "Entrando al sistema...", progress: 100 }
    ];

    let i = 0;

    function nextStep() {
    if (i < steps.length) {
        document.getElementById("status").innerText = steps[i].text;
        document.getElementById("bar").style.width = steps[i].progress + "%";
        i++;

        setTimeout(nextStep, 800);
    } else {
        setTimeout(() => {
        window.location.href = "dashboard.php";
        }, 400);
    }
    }

    nextStep();
</script>
</html>
