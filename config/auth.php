<?php
session_start();

// Si el usuario no está autenticado, lo redirigimos al login
if (!isset($_SESSION['user'])) {
  $redirectBack = urlencode($_SERVER['REQUEST_URI']); // Página que intentaba acceder
  header("Location: login.php?redirect=$redirectBack");
  exit();
}
