<?php
session_start();
include_once __DIR__ . '/../includes/config.php';

$currentFile = basename($_SERVER['PHP_SELF']);
$exemptFiles = ['login.php', 'register.php'];

// Si el usuario NO está autenticado y NO está en una página exenta
if (!isset($_SESSION['user']) && !in_array($currentFile, $exemptFiles)) {

  // Evitamos bucles infinitos: si ya hay un parámetro "redirect", no seguimos redireccionando
  if (!isset($_GET['redirect'])) {
    $redirectBack = urlencode($_SERVER['REQUEST_URI']);
    header("Location: " . BASE_URL . "/login.php?redirect=$redirectBack");
    exit();
  }
}
