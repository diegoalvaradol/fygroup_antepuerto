<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login.php');

  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Redirigiendo...</title>

  <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      text-align: center;
      flex-direction: column;
    }

    .contenedor {
      max-width: 90%;
      padding: 30px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .mensaje {
      font-size: 1.3rem;
      color: #888;
      opacity: 0;
      transition: opacity 0.5s ease-in-out, color 0.3s, transform 0.3s;
      margin: 15px 0;
      font-weight: 500;
    }

    .visible {
      opacity: 1;
      transform: translateY(0);
    }

    .mensaje-actual {
      color: #111;
      font-weight: bold;
      font-size: 1.5rem;
    }

    .spinner {
      margin: 30px auto 0;
      width: 40px;
      height: 40px;
      border: 4px solid #ddd;
      border-top: 4px solid #000;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>

<body>
  <div class="contenedor">
    <h2>Bienvenido, <?=htmlspecialchars($_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"])?> 👋</h2>
    <div id="msg1" class="mensaje">Verificando sesión...</div>
    <div id="msg2" class="mensaje">Cargando configuración...</div>
    <div id="msg3" class="mensaje">Redirigiendo al panel...</div>
    <div id="spinner" class="spinner"></div>
  </div>

  <script>
    function mostrarSiguiente() {
      if (actual >= 0) {
        const anterior = document.getElementById(mensajes[actual]);
        anterior.classList.remove("mensaje-actual");
        anterior.style.color = "#888";
      }

      actual++;
      if (actual < mensajes.length) {
        const el = document.getElementById(mensajes[actual]);
        el.classList.add("visible", "mensaje-actual");
        setTimeout(mostrarSiguiente, 1500);
      } else {
        setTimeout(() => {
          window.location.href = "dashboard.php";
        }, 1000);
      }
    }
  </script>
</body>
</html>