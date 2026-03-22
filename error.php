<?php
http_response_code(403);
?>

<!DOCTYPE html>
  <html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Diego Alvarado López." content="">
    <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
    <title>SSL | Área Inválida.</title>
    <meta http-equiv="refresh" content="5;url=dashboard.php">
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
            <img src="../images/ssl-logo-azul.png" style="height: 50%; width: 100%;">
        </div>

        <h1>Área Inválida.</h1>
        <p>Estas tratando de acceder en un directorio no autorizado.</p>
        <p>Por favor contacta a soporte.</p>
        <small>Serás redirigido en 5 segundos…</small>
        <a class="btn btn-sm btn-primary" onclick="location.href='dashboard.php'">Ir ahora</a>
    </div>
  </body>
  </html>
