<?php
session_start();

if (!isset($_SESSION['user'])) {
  header("Location: /ssl-chile/login.php");
  exit();
}

$division = $_SESSION['user']['division'] ?? '';
$uri      = $_SERVER['REQUEST_URI'];
$redirect = '/ssl-chile/login.php';
$seconds  = 5;

$forbidden = false;

if (strpos($uri, '/ssl-chile/myPortal') !== false && ($division !== 'terminal' && $division !== 'shipper')) {
  $redirect  = '/ssl-chile/myPortal/login.php';
  $forbidden = true;
}

if (strpos($uri, '/ssl-chile/mySSL') !== false && $division !== 'ssl') {
  $redirect  = '/ssl-chile/mySSL/login.php';
  $forbidden = true;
}

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
      <h1>403</h1>
      <p>No tienes permisos para acceder a esta sección.</p>
      <small>Serás redirigido en <?= $seconds ?> segundos…</small>
      <a href="<?= $redirect ?>">Ir ahora</a>
    </div>
  </body>
  </html>
  <?php

  exit();
}
