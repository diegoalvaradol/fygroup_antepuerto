<?php
session_start();

/* ===============================
Base path automático
=============================== */
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$basePath = str_replace(['/myPortal', '/myFY'], '', $basePath);

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
$uri = $_SERVER['REQUEST_URI'];
$seconds = 5;
$redirect = "{$basePath}/login.php";
$forbidden = false;

/* ===============================
Reglas de acceso
=============================== */
if (strpos($uri, '/myPortal') !== false && ($division !== 'terminal' && $division !== 'shipper')) {
    $redirect = "{$basePath}/myPortal/login.php";
    $forbidden = true;
}

if (strpos($uri, '/myFY') !== false && $division !== 'fy') {
    $redirect = "{$basePath}/myFY/login.php";
    $forbidden = true;
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
    <meta charset="UTF-8">
    <title>Acceso no autorizado</title>
    <meta http-equiv="refresh" content="<?= $seconds ?>;url=<?= $redirect ?>">
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
      }
      .card h1 {
        font-size: 3rem;
        margin-bottom: .5rem;
      }
      .card p {
        opacity: .85;
        margin-bottom: 1.5rem;
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
    </style>
  </head>
  <body>
    <div class="card">
      <div style="justify-self: center;">
        <img src="../images/logo-fygroup-v1.png" style="height: 50%; width: 75%;">
      </div>

      <h1>403</h1>
      <p>No tienes permisos para acceder a esta sección.</p>
      <p>Por favor contacta a soporte.</p>
      <small>Serás redirigido en <?= $seconds ?> segundos…</small>
      <a class="btn btn-sm btn-primary" href="<?= $redirect ?>">Ir ahora</a>
    </div>
  </body>
  </html>
  <?php

    exit();
}
