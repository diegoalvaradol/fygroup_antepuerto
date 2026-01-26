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
  <meta http-equiv="refresh" content="5;url=dashboard.php">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Cargando...</title>

  <link rel="icon" type="image/png" href="../favicon/apple-touch-icon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(135deg, #eef2f7, #d6e0eb);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .container {
      background: white;
      padding: 2rem 3rem;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
    }
    h2 {
      margin-bottom: 1rem;
      color: #333;
    }
    p {
      color: #555;
      font-size: 1rem;
      margin: 0.5rem 0;
      opacity: 0;
      transition: opacity 0.5s ease-in;
    }
    p.visible {
      opacity: 1;
    }
    .loader {
      margin: 1.5rem auto 0;
      border: 4px solid #ddd;
      border-top: 4px solid #2563eb;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Bienvenido, <?=htmlspecialchars($_SESSION["user"]["name"] . ' ' . $_SESSION["user"]["last_name"])?> 👋</h2>

    <p id="msg1">Validando sesión...</p>
    <p id="msg2">Cargando datos...</p>
    <p id="msg3">Redirigiendo al panel...</p>

    <div class="loader"></div>
  </div>

  <script>
		const mensajes = ["msg1", "msg2", "msg3"];
		let actual = -1;

		function mostrarSiguiente() {
			if (actual >= 0) {
				const anterior = document.getElementById(mensajes[actual]);
				anterior.style.color = "#888";  // gris para mensajes anteriores
			}

			actual++;
			if (actual < mensajes.length) {
				const actualMsg = document.getElementById(mensajes[actual]);
				actualMsg.classList.add("visible");
				actualMsg.style.color = "#000"; // negro para el mensaje actual
				setTimeout(mostrarSiguiente, 1500);
			}
		}

		mostrarSiguiente();
	</script>
</body>
</html>